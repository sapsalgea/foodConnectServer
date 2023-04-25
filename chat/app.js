const express = require('express')
const mysql = require('mysql')
const http = require('http')
const app = express()
const admin = require("firebase-admin");
let serviceAccount = require("./food-connect-d51dd-firebase-adminsdk-g34it-1b80e041be.json");
app.use(express.static('images')) // images 폴더를 읽도록 함
const server = http.createServer(app)
const io = require('socket.io')(server)
var HashMap = require('hashmap');
console.log(__dirname)

admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
});


const connection = mysql.createConnection({
    host: 'localhost',
    user: '"DB_USER_ID"',
    password: 'DB_PASSWORD',
    database: 'food_connect_db'

})


var roomListUser = new HashMap();

// connection.query('SELECT * from room_tb', (error, rows, fields) => {
//     if (error) throw error;
//     console.log('User info is: ', rows)
//   });
connection.connect()

const multer = require('multer')
const randomstring = require('randomstring')
const imageUpload = multer({
    storage: multer.diskStorage({
        destination: (req, file, cb) => {
            cb(null, `${__dirname}/images`) // images 폴더에 저장
        },
        filename: (req, file, cb) => {
            var fileName = randomstring.generate(25); // 랜덤 25자의 파일 이름
            var mimetype;
            switch (file.mimetype) {
                case 'image/jpeg':
                    mimeType = 'jpg';
                    break;
                case 'image/png':
                    mimeType = 'png';
                    break;
                case 'image/gif':
                    mimeType = 'gif';
                    break;
                case 'image/bmp':
                    mimeType = 'bmp';
                    break;
                default:
                    mimeType = 'jpg';
                    break;
            }
            cb(null, fileName + '.' + mimeType);
        },
    }),
    limits: {
        fileSize: 5 * 1024 * 1024, // 5MB 로 크기 제한
    },
})

// 이미지 업로드
app.post('/upload', imageUpload.single('image'), (req, res) => {
    console.log(req.file)

    const imageData = {
        result: 1,
        imageUri: res.req.file.filename
    }
    res.send(JSON.stringify(imageData))
})

// 소켓 연결 코드
io.sockets.on('connection', (socket) => {
    console.log(`Socket connected : ${socket.id}`)

    socket.on('enter', (data) => {
        const roomData = JSON.parse(data)

        const username = roomData.userName
        socket.nickname = username
        socket.userIndex = roomData.userIndex
        socket.InRoomNum = roomData.roomId
        const roomNumber = roomData.roomId
        console.log(`닉네임 : ${socket.nickname}`)
        socket.join(`${roomNumber}`)
        //방에 접속한 유저목록 관리
        if (roomListUser.has(`${roomNumber}`)) {
            var ss = roomListUser.get(`${roomNumber}`)
            ss.add(`${socket.userIndex}`)
            ss.forEach(index => {
                console.log(`인덱스2 : ${index}`)
            })
        } else {            
           
            
            roomListUser.set(`${roomNumber}`, new Set(`${socket.userIndex}`))
           
        }

        const roomlist = io.sockets.adapter.rooms.get(`${roomNumber}`)
        const num = roomlist ? roomlist.size : 0
        for (const clientId of roomlist) {

            const clientSocket = io.sockets.sockets.get(clientId);
            console.log(`${roomNumber}유저 : ${clientSocket.nickname}\n인덱스:${clientSocket.userIndex}`)
        }
        console.log(`유저숫자 : ${num}`)
        console.log(`[Username : ${username}] entered [room number : ${roomNumber}]`)

        const enterData = {
            type: "ENTER",
            content: `${username} entered the room`
        }
        // socket.broadcast.to(`${roomNumber}`).emit('update', JSON.stringify(enterData))
    })

    socket.on('read', (data) => {
        const roomData = JSON.parse(data)
        const roomNumber = roomData.roomId
        console.log(`리드 로그`)
        io.sockets.to(`${roomNumber}`).emit('read')
    })
    socket.on('listIn', (data) => {
        const roomData = JSON.parse(data)

        const username = roomData.userName
        socket.nickname = username
        socket.userIndex = roomData.userIndex
        const roomNumber = roomData.roomId
        // console.log(`닉네임 : ${socket.nickname}`)
        socket.join(`${roomNumber}`)

    })

    socket.on('join', (data) => {
        var messageData = JSON.parse(data)
        var to = messageData.to
        var from = messageData.from
        var messageUserIndex = messageData.userIndex
        var content = `   ${messageData.content}님이 입장하셧습니다.   `
        var thumbnailImage = messageData.thumbnailImage
        var type = messageData.type
        messageData.content = content
        let today = new Date();

        let year = today.getFullYear(); // 년도
        let month = today.getMonth() + 1; // 월
        let date = today.getDate(); // 날짜
        let hours = today.getHours(); // 시
        let minutes = today.getMinutes(); // 분
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

        nowTime = year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds;

        var sql = "INSERT INTO group_message_tb(to_room_id, from_user_id,user_index,message_type, content, thumbnailImage,sendtime)VALUES(?,?,?,?,?,?,?)";

        connection.query(sql, [to, from, messageUserIndex, type, content, thumbnailImage, nowTime], function (error, result) {
            if (error) throw error
            console.log("입장처리 저장")
        })
        console.log(`
[type : ${messageData.type} 
Room Number ${messageData.to}] 
${messageData.from} : ${messageData.content} 
ImageUrl : ${messageData.thumbnailImage} 
Time : ${messageData.sendTime}
`)
        messageData.sendTime = nowTime

        io.sockets.to(`${messageData.to}`).emit('joinRoom', JSON.stringify(messageData))
    })

    socket.on('left', (data) => {
        const roomData = JSON.parse(data)
        const username = roomData.userName
        const roomNumber = roomData.roomId

        if (roomListUser.has(`${roomNumber}`)) {
            var ss = roomListUser.get(`${roomNumber}`)
            if (ss.has(roomData.userIndex)) {
                console.log(`지우기 ${ss.values().next().value}`)
                ss.delete(roomData.userIndex)
                console.log(`지우기 ${ss.size}`)
            }
        }
        socket.leave(`${roomNumber}`)
        console.log(`[Username : ${username}] left [room number : ${roomNumber}]`)
        if (roomListUser.has(`${roomNumber}`)) {
            ss.forEach(index => {
                console.log(`퇴장후 : ${index}`)
            })
        }


        const leftData = {
            type: "LEFT",
            content: `${username} left the room`
        }
        // socket.broadcast.to(`${roomNumber}`).emit('update', JSON.stringify(leftData))
    })
    socket.on('TIMELINE', (data) => {
        const messageDate = JSON.parse(data)
        let today = new Date();
        let year = today.getFullYear(); // 년도
        let month = today.getMonth() + 1; // 월
        let date = today.getDate(); // 날짜


        dateTime = year + "-" + month + "-" + date + " " + 00 + ":" + 00 + ":" + 00 + "." + 000
        var to = messageDate.to
        var from = messageDate.from
        var messageUserIndex = messageDate.userIndex
        var content = "      " + year + "년  " + month + "월  " + date + "일      "
        var thumbnailImage = messageDate.thumbnailImage
        var type = messageDate.type
        messageDate.content = content
        messageDate.sendTime = dateTime
        var sql = "INSERT INTO group_message_tb(to_room_id, from_user_id,user_index, message_type, content, thumbnailImage,sendtime)VALUES(?,?,?,?,?,?,?)";

        connection.query(sql, [to, from, messageUserIndex, type, content, thumbnailImage, dateTime], function (error, result) {
            if (error) throw error
            console.log("날짜구분선 저장")
        })
        console.log(`[type : ${messageDate.type} 
            Room Number ${messageDate.to}] 
            ${messageDate.from} : ${messageDate.content} 
            ImageUrl : ${messageDate.thumbnailImage} 
            Time : ${messageDate.sendTime}`)

        io.sockets.to(`${messageDate.to}`).emit('update', JSON.stringify(messageDate))
    })

    socket.on('kick', (data) => {
        const Kickdata = JSON.parse(data)


    })

    socket.on('outRoom', (data) => {
        var outRoomData = JSON.parse(data)
        var to = outRoomData.to
        var from = outRoomData.from
        var messageUserIndex = outRoomData.userIndex
        var content = `   ${outRoomData.content}님이 방을 나가셨습니다.   `
        var kickId = outRoomData.content
        if (from == "GETOUTROOM") {
            content = `   ${outRoomData.content}님을 내보냈습니다.   `
        }
        var thumbnailImage = outRoomData.thumbnailImage
        var type = outRoomData.type
        let today = new Date();
        outRoomData.content = content

        let year = today.getFullYear(); // 년도
        let month = today.getMonth() + 1; // 월
        let date = today.getDate(); // 날짜
        let hours = today.getHours(); // 시
        let minutes = today.getMinutes(); // 분
        let seconds = today.getSeconds();
        let miliseconds = today.getMilliseconds();
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

        nowTime = year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds + "." + month;

        var sql = "INSERT INTO group_message_tb(to_room_id, from_user_id,user_index,message_type, content, thumbnailImage,sendtime)VALUES(?,?,?,?,?,?,?)";

        connection.query(sql, [to, from, messageUserIndex, type, content, thumbnailImage, nowTime], function (error, result) {
            if (error) throw error
            console.log("퇴장처리 저장")
        })
        console.log(`
[type : ${outRoomData.type} 
Room Number ${outRoomData.to}] 
${outRoomData.from} : ${outRoomData.content} 
ImageUrl : ${outRoomData.thumbnailImage} 
Time : ${outRoomData.sendTime}
`)
        if (from == "EXITROOM") {
            io.sockets.to(`${outRoomData.to}`).emit('outRoom', JSON.stringify(outRoomData))

        } else {
            io.sockets.to(`${outRoomData.to}`).emit('outRoom', JSON.stringify(outRoomData))
            outRoomData.content = kickId
            io.sockets.to(`${outRoomData.to}`).emit('kick', JSON.stringify(outRoomData))

        }
    })

    socket.on('newMessage', (data) => {

        const messageData = JSON.parse(data)
        let today = new Date();
        const roomNumber = messageData.to
        const roomlist = io.sockets.adapter.rooms.get(`${roomNumber}`)
        let year = today.getFullYear(); // 년도
        let month = today.getMonth() + 1; // 월
        let date = today.getDate(); // 날짜
        let hours = today.getHours(); // 시
        let minutes = today.getMinutes(); // 분
        let seconds = today.getSeconds();
        let miliseconds = today.getMilliseconds();
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

        nowTime = year + "-" + month + "-" + date + " " + hours + ":" + minutes + ":" + seconds + "." + miliseconds;

        var to = messageData.to
        var from = messageData.from
        var messageUserIndex = messageData.userIndex
        var content = messageData.content
        var thumbnailImage = messageData.thumbnailImage
        var type = messageData.type
        messageData.sendTime = nowTime
        console.log(`메시지타입 : ${type}`)

        var memberArray2 = JSON.parse(`${messageData.members}`)
        var ss = roomListUser.get(`${roomNumber}`)
        ss.forEach(index => {
            memberArray2 = memberArray2.filter((element) => element != index)
            console.log(`인덱스2 : ${index} 배열${memberArray2}`)

        })
        memberArray2.forEach(index => {
                var fcmsql = `SELECT user_token From user_tb WHERE id =${index} `;
                connection.query(fcmsql, function (error, result) {
                    if (error) throw error
                    result.forEach(function (row) {
                        switch (type) {
                            case "IMAGE":
                                if (row.user_token != null ) {
                                    var registrationToken = row.user_token;
                                    var message = {
                                        token: registrationToken,
                                        android: {
                                            priority: 'high'
                                        },
                                        data: {
                                            title: '새로운 메시지가 도착했습니다.',
                                            body: `이미지`,
                                            roomId: `${to}`,
                                            hostName: messageData.hostName
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
                                break;
                            default:
                                if (row.user_token != null) {
                                    console.log("여기")
                                    var registrationToken = row.user_token;
                                    var message = {
                                        token: registrationToken,
                                        android: {
                                            priority: 'high'
                                        },
                                        data: {
                                            title: '새로운 메시지가 도착했습니다.',
                                            body: `${from} : ${content}`,
                                            roomId: `${to}`,
                                            hostName: messageData.hostName
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
                                break;
                        }

                        console.log(`토큰 : ${row.user_token}`)
                    })
                })
            }

        )
        var memberArray3 = JSON.stringify(memberArray2)
        messageData.members = memberArray3
        var sql = "INSERT INTO group_message_tb(to_room_id, from_user_id, user_index,message_type, content, thumbnailImage,sendtime,join_members)VALUES(?,?,?,?,?,?,?,?)";

        connection.query(sql, [to, from, messageUserIndex, type, content, thumbnailImage, nowTime, memberArray3], function (error, result) {
            if (error) throw error
            console.log("메시지 저장")
        })


        console.log(`
[type : ${messageData.type} 
Room Number : ${messageData.to}] 
${messageData.from} : ${messageData.content} 
ImageUrl : ${messageData.thumbnailImage} 
Time : ${messageData.sendTime}
roomlist : ${JSON.stringify(roomlist)}
members : ${messageData.members}

members2 : ${memberArray2}
members3 : ${memberArray3}`)
        io.sockets.to(`${messageData.to}`).emit('listRead', JSON.stringify(messageData))
        io.sockets.to(`${messageData.to}`).emit('update', JSON.stringify(messageData))
    })

    socket.on('newImage', (data) => {
        const messageData = JSON.parse(data)
        // 안드로이드 에뮬레이터 기준으로 url은 10.0.2.2, 스마트폰에서 실험해보고 싶으면 자신의 ip 주소로 해야 한다.
        messageData.content = 'http://10.0.2.2:80/' + messageData.content
        console.log(`[Room Number ${messageData.to}] ${messageData.from} : ${messageData.content}`)
        socket.broadcast.to(`${messageData.to}`).emit('update', JSON.stringify(messageData))
    })

    socket.on('disconnect', () => {
        if (roomListUser.has(`${socket.InRoomNum}`)) {
            var ss = roomListUser.get(`${socket.InRoomNum}`)
            if (ss.has(`${socket.userIndex}`)) {
                console.log(`지우기 ${ss.values().next().value}`)
                ss.delete(`${socket.userIndex}`)
                console.log(`지우기 ${ss.size}`)
            }
        }
        console.log(`[Username : ${socket.nickname}] left [room number : ${socket.InRoomNum}]`)
        if (roomListUser.has(`${socket.InRoomNum}`)) {
            ss.forEach(index => {
                console.log(`퇴장후 : ${index}`)
            })
        }
        console.log(`나간방+ ${socket.InRoomNum}`)
        console.log(`나간사람+ ${socket.userIndex}`)
        console.log(`Socket disconnected : ${socket.id}`)
    })
})

server.listen(9000, () => {
    console.log(`Server listening at http://localhost:9000`)
})