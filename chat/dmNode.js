/* 설치한 express 모듈 불러오기 */
var app = require('express')();
/* Node.js 기본 내장 모듈 불러오기 */
/* express http 서버 생성 */
var http = require('http').Server(app);

/* 생성된 서버를 socket.io에 바인딩 */
var io = require('socket.io')(http);

const mysql = require('mysql');

//fcm

const admin = require("firebase-admin");
let serviceAccount = require("./food-connect-d51dd-firebase-adminsdk-g34it-1b80e041be.json");

admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
});

const connection = mysql.createConnection({
    host: 'localhost',
    user: '"DB_USER_ID"',
    password: 'DB_PASSWORD',
    database: 'food_connect_db'

});

connection.connect();

//index.html 파일의 위치를 알려줌
// Express.js란? HTTP 요청에 대하여 라우팅 및 미들웨어 기능을 제공하는 웹 프레임워크
//라우팅(routing) 은 기본적으로 어플리케이션 서버에서 경로를 제어하는 목적
//목적지까지 갈 수 있는 여러 경로 중 한 가지 경로를 설정해 주는 과정.
/**
 * METHOD : Http Method(GET, POST, PUT, DELETE, PATCH 등)
 * PATH : [경로]
 * HANDLER : [경로 접근 시, 처리 핸들러]
 */
// url로 접속하므로 get방식
// app.get('/', function(req, res){
//    res.sendfile('index.html');//default page
// });


//생성된 서버가 포트를 바라보게 한다.
http.listen(3000, function(){
   console.log('listening on *:3000');
});


// 클라이언트가 socket.io 채널로 접속이 되었을때에 대한 이벤트를 정의
// io.sockets.on('connection',function(socket){
// 과 같이 클라이언트가 접속이 되면, callback을 수행하는데 이때, 연결된 클라이언트의 socket 객체를 같이 넘긴다
// 이 socket 객체 안에는 접속한 클라이언트의 ip,port번호가 담겨있다

io.sockets.on('connection', function (socket){


  // /*방번호를 설정한다.*/
  // socket.on('user_room_name', function (msg){
  //   socket.join(msg);
  //   //socket.emit('message_from_server', '"' +msg+ '" 방에 입장하셨습니다..');
  //   console.log('message:', 'message_from_server', '"' +msg+ '" 방에 입장하셨습니다.');
  //   //socket.broadcast.emit('message_from_server', msg+ '" 방에 입장하셨습니다..');
  // });



  socket.on('login', function (msg){

    var msgString = msg.toString();
    var msgSplit = msgString.split(",");

    var roomNumber = msgSplit[0];
    var user_tb_id = msgSplit[1];
    var your_user_tb_id = msgSplit[2];
    //방에 입장한다.
    socket.join(roomNumber);


    //socket.emit('message_from_server', '"' +msg+ '" 방에 입장하셨습니다..');
    console.log('message:', '로그인', '"' +user_tb_id+ '"님'+roomNumber+' 방에 입장하셨습니다..');
    socket.name = user_tb_id;
    console.log('message:', '로그인', '"' +socket.name+ '"님 소켓에 이름저장함..');
    //socket.broadcast.emit('message_from_server', msg+ '" 방에 입장하셨습니다..');



    // 유저 접속 시, direct_message_log_tb 업데이트문 실행
    // direct_message_log_tb의 from_user_tb_id 칼럼의 값과 user_tb_id 값이 동일하다면 읽음 처리를 한다.

    var update_sql = 'UPDATE direct_message_log_tb SET message_check = ? WHERE to_user_tb_id = ? AND message_check = ""';
    var params = ['읽음', user_tb_id];

    connection.query(update_sql, params, function(err, rows, fields){
      if(err){
        console.log(err);
      }else{
        console.log("UPDATE OK");
        //상대방이 접속하면 글을 읽었기 때문에 나에게 읽음 표시를 날림.
        console.log("방번호"+roomNumber);
        io.to(roomNumber).emit('new_user_coming',user_tb_id);
      }
    })




    //유저가 DM리스트를 보고 있을때, 어떤 유저가 처음 말을 건다면 이 유저의 채팅리스트가 보이게 만든다.

    var all_client_list= io.sockets.adapter.sids;

    //있는지 없는지 체크
    var isUserTrueOrFalse = false;

    var findSocket ="";

    for (const some_clientId of all_client_list ) {


         const clientSocket = io.sockets.sockets.get(some_clientId[0]);


         console.log("dmlist유저목록"+clientSocket.name);
         console.log("머라나옴"+clientSocket.name+"dm");
         console.log("머라나옴"+clientSocket.name);

         if(clientSocket.name == your_user_tb_id+"dm"){
           console.log("dmlist보는유저발견"+clientSocket.name);
           findSocket = clientSocket;
           isUserTrueOrFalse = true;
         }

    }

    //유저가 DM리스트를 보고 있을때, 어떤 유저가 처음 말을 건다면 이 유저의 신규 채팅이 보이게 만든다.

    if(isUserTrueOrFalse == true){
      findSocket.join(roomNumber);
    }

    socket.broadcast.emit('some_one_login_broadcast', "신규유저로그인");



  });



  //DM 리스트 프레그먼트에 들어왔을 경우, 소켓통신을 통하여 새로고침한다.
  // 들어왔을때, 참여하고 있는 모든 방에 join으로 들어가서 나에게 들어오는 메시지가 있는지 감지한다.
  socket.on('DMFragmentLogin', function (msg){

    var msgString = msg.toString();
    var msgSplit = msgString.split("@");

    var dmFragment_user_tb_id = msgSplit[0];
    var dmFragment_RoomNames = msgSplit[1];



    var dmFragment_RoomNameSplit = dmFragment_RoomNames.split(",");

    for(var i=0; i < dmFragment_RoomNameSplit.length; i++) {
      socket.join(dmFragment_RoomNameSplit[i]);
      console.log(dmFragment_RoomNameSplit[i]);
      socket.name = dmFragment_user_tb_id+"dm";
    }

    console.log('message:', '로그인', '"' +socket.name+ '"님 소켓에 이름저장함..');



    //
    // //socket.emit('message_from_server', '"' +msg+ '" 방에 입장하셨습니다..');
    // console.log('message:', '로그인', '"' +user_tb_id+ '"님'+roomNumber+' 방에 입장하셨습니다..');

    // //socket.broadcast.emit('message_from_server', msg+ '" 방에 입장하셨습니다..');



  });


  socket.on('disconnect', () => {
      console.log(`Socket disconnected : ${socket.name}`)
    })


   // socket.on('newUser', function(name) {
   //   console.log(name + ' 님이 접속하였습니다.')
   //
   //   /* 소켓에 이름 저장해두기 */
   //   socket.name = name
   //
   //   /* 모든 소켓에게 전송 */
   //   io.sockets.emit('update', {type: 'connect', name: 'SERVER', message: name + '님이 접속하였습니다.'})
   // })

  //메세지가 들어 오면 응답
   socket.on('message_from_client', function (msg){
     console.log('message:', msg);




     const obj = JSON.parse(msg);

      console.log(obj.room_name);

      let today = new Date();

      let year = today.getFullYear(); // 년도
      let month = today.getMonth() + 1;  // 월
      let date = today.getDate();  // 날짜
      let hours = today.getHours(); // 시
      let minutes = today.getMinutes();  // 분
      let seconds = today.getSeconds();



      if (month < 10) {
        month = "0" + month;
      }

      if (date < 10) {
        date = "0" + date;
      }

      if (hours < 10) {
        hours = "0" + hours;
      }

      if (minutes < 10) {
        minutes = "0" + minutes;
      }

      if (seconds < 10) {
        seconds = "0" + seconds;
      }

      var today_date =  year+"-"+month+"-"+date;


      var nowTime = year+"-"+month+"-"+date+" "+hours+":"+minutes+":"+seconds;


      //날짜 변경선이 있는지 확인한다.

      var sql = "SELECT * FROM direct_message_log_tb WHERE room_name = ? AND content = ? AND text_or_image_or_dateline = ?";

      connection.query(sql,[obj.room_name,today_date,"DateLine"], function(err, rows, fields){
      	if(err){
      		console.log(err);
      	} else {
      		if(rows.length==0){
            //데이트라인이 없으므로 DB에 Date라인을 추가한다.
            console.log("dateLine X");

            var sql = "INSERT INTO direct_message_log_tb(room_name, from_user_tb_id, to_user_tb_id, content, text_or_image_or_dateline,send_time,message_check)VALUES(?,?,?,?,?,?,?)";

            connection.query(sql,[obj.room_name,obj.from_user_tb_id ,obj.to_user_tb_id , today_date ,"DateLine", nowTime, "읽음"], function(err, rows, fields){
            	if(err){
            		console.log(err);
            	} else {
            		console.log("날짜변경선 저장완료")

                // 날짜변경선 객체 생성
                var data = new Object() ;
                data.dm_log_tb_id = obj.dm_log_tb_id;
                data.room_name = obj.room_name;
                data.from_user_tb_id = obj.from_user_tb_id;
                data.to_user_tb_id = obj.to_user_tb_id;
                data.content = today_date;
                data.text_or_image_or_dateline = "DateLine";
                data.send_time = nowTime;
                data.message_check ="읽음";

                //날짜 변경선 생성됨을 알림.
                io.to(obj.room_name).emit('message_from_server',data);

            	}
            });



          }else{


            console.log("dateLine O");
            //날짜 변경선이 있다


          }//dd


          // 객체 생성
          var data = new Object() ;
          data.dm_log_tb_id = obj.dm_log_tb_id;
          data.room_name = obj.room_name;
          data.from_user_tb_id = obj.from_user_tb_id;
          data.to_user_tb_id = obj.to_user_tb_id;
          data.content = obj.content;
          data.text_or_image_or_dateline = obj.text_or_image_or_dateline;
          data.send_time = nowTime;
          data.message_check ="";



          //방에 접속한 목록을 clients에 저장한다.
          // 만약 클라이언트 아이디와 받는사람이 일치하면 메시지를 읽음처리 한다.
          const clients = io.sockets.adapter.rooms.get(obj.room_name);

          for (const clientId of clients ) {

               //this is the socket of each client in the room.
               const clientSocket = io.sockets.sockets.get(clientId);


               console.log("유저목록"+clientSocket.name);
               if(data.to_user_tb_id == clientSocket.name){
                  data.message_check = "읽음";


                  console.log("발견"+clientSocket.name);

              }



          }


          //fcm보내기

          if(data.message_check != "읽음"){

            var fromUserProfileImage = "";
            var fromUserNickname = "";


            var fcmsql = `SELECT * From user_tb WHERE id =${data.from_user_tb_id}`;
            connection.query(fcmsql, function (error, result) {
                if (error) throw error
                result.forEach(function (fcmrow) {
                    fromUserProfileImage = fcmrow.profile_image;
                    fromUserNickname = fcmrow.nick_name;


                })
            })



            var fcmsql = `SELECT * From user_tb WHERE id =${data.to_user_tb_id}`;
            connection.query(fcmsql, function (error, result) {
                if (error) throw error
                result.forEach(function (fcmrow) {

                    if(data.text_or_image_or_dateline == "Text"){
                      if (fcmrow.user_token != null) {
                          var registrationToken = fcmrow.user_token;
                          var message = {
                              token: registrationToken,
                              android: {
                                  priority: 'high'
                              },
                              data: {
                                  title: fromUserNickname+'님이 메시지를 보내셨습니다.',
                                  body: data.content,
                                  roomId:data.room_name,
                                  fromUserProfileImage:fromUserProfileImage,
                                  fromUserNickname: fromUserNickname,
                                  fromUserTbId:data.from_user_tb_id+"",
                                  isdm: 'true'

                              }
                          }
                          admin.messaging().send(message)
                              .then((response) => {
                                  // Response is a message ID string.
                                  console.log('Successfully sent message:', response);
                              })
                              .catch((error) => {
                                  console.log('Error sending message:', error);
                              });
                      }
                    }

                    else if(data.text_or_image_or_dateline == "Image"){
                      if (fcmrow.user_token != null) {
                          var registrationToken = fcmrow.user_token;
                          var message = {
                              token: registrationToken,
                              android: {
                                  priority: 'high'
                              },
                              data: {
                                  title: fromUserNickname+'님이 이미지를 보내셨습니다.',
                                  body: `이미지`,
                                  roomId:data.room_name,
                                  fromUserProfileImage:fromUserProfileImage,
                                  fromUserNickname: fromUserNickname,
                                  fromUserTbId:data.from_user_tb_id+"",
                                  isdm: 'true'
                              }
                          }
                          admin.messaging().send(message)
                              .then((response) => {
                                  // Response is a message ID string.
                                  console.log('Successfully sent message:', response);
                              })
                              .catch((error) => {
                                  console.log('Error sending message:', error);
                              });
                      }
                    }



                    console.log(`토큰 : ${fcmrow.user_token}`)
                })
            })


          }




          console.log("읽음 :"+data.message_check);


          console.log(data);

          var sql = "INSERT INTO direct_message_log_tb(room_name, from_user_tb_id, to_user_tb_id, content, text_or_image_or_dateline,send_time,message_check)VALUES(?,?,?,?,?,?,?)";

          connection.query(sql,[data.room_name,data.from_user_tb_id ,data.to_user_tb_id ,data.content,data.text_or_image_or_dateline, data.send_time,data.message_check],function (err,result){
            if(err){
              console.log(err);
            } else {
              console.log("메시지 저장완료");

              io.to(obj.room_name).emit('message_from_server',data); // 방번호에 속한 인원들에게 메시지를 보냄.
              //socket.emit('message_from_server', '"' +msg+ '" 라고하셨군요.');
              //socket.broadcast.emit('message_from_server', msg);




              //방목록 새로고침을 위해 보냄.
              io.to(obj.room_name).emit('dm_fragment_refresh',data); // 방번호에 속한 인원들에게 메시지를 보냄.
            }


          })




      	}
      });










   });

});




//
// var app = require('express')();
// var http = require('http').Server(app);
// var io = require('socket.io')(http);
//
// app.get('/', function(req, res){
//    res.sendfile('index.html');//default page
// });
//
// http.listen(3000, function(){
//    console.log('listening on *:3000');
// });
//
// io.sockets.on('connection', function (socket){
//    //원격에서 접속이 되면 기본 응답
//    socket.emit('message_from_server', 'hello, world');
//
//   //메세지가 들어 오면 응답
//    socket.on('message_from_client', function (msg){
//      console.log('message:', msg);
//      /*받은 메세지를 되돌려 주자.
//      아니면 받은 데이터를 이용 라즈베리파에서 뭐든 할 수 있다.
//      */
//      socket.emit('message_from_server', '"' +msg+ '" 라고하셨군요.');
//    });
//
// });

//
// var app = require('express')();
// var http = require('http').Server(app);
// var io = require('socket.io')(http);
//
// app.get('/', function(req, res){
//    res.sendfile('index.html');//default page
// });
//
// http.listen(3000, function(){
//    console.log('listening on *:3000');
// });
//
// io.sockets.on('connection', function (socket){
//    //원격에서 접속이 되면 기본 응답
//    socket.emit('message_from_server', 'hello, world');
//
//    /* 새로운 유저가 접속했을 경우 다른 소켓에게도 알려줌 */
//
//
//
//   socket.on('user_room_number', function (msg){
//
//     /*방번호를 설정한다.
//     */
//
//     socket.join('msg');
//     socket.emit('message_from_server', '"' +msg+ '" 방에 입장하셨습니다..');
//     console.log('message:', 'message_from_server', '"' +msg+ '" 방에 입장하셨습니다..');
//     socket.broadcast.emit('message_from_server', 'message_from_server', '"' +msg+ '" 방에 입장하셨습니다..');
//   });
//
//
//    var name = "익명";
//
//    socket.on('newUser', function(name) {
//      console.log(name + ' 님이 접속하였습니다.')
//
//      /* 소켓에 이름 저장해두기 */
//      socket.name = name
//
//      /* 모든 소켓에게 전송 */
//      io.sockets.emit('update', {type: 'connect', name: 'SERVER', message: name + '님이 접속하였습니다.'})
//    })
//
//   //메세지가 들어 오면 응답
//    socket.on('message_from_client', function (msg){
//      console.log('message:', msg);
//      /*받은 메세지를 되돌려 주자.
//      아니면 받은 데이터를 이용 라즈베리파에서 뭐든 할 수 있다.
//      */
//      socket.emit('message_from_server', '"' +msg+ '" 라고하셨군요.');
//      socket.broadcast.emit('message_from_server', msg);
//    });
//
// });
