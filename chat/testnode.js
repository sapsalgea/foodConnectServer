var HashMap = require('hashmap');

var node = new HashMap(String, new Set())
const member  = Array("a","b")
node.set("하하",new Set())
if (node.has("히히")){
    console.log("있음")
}else{
    console.log("없음")

}

console.log(node.get("하하"))
var l = node.get("하하")
l.add("b")
console.log(node.get("하하"))
l.add("a")
console.log(node.get("하하"))
l.add("a")
console.log(node.get("하하"))
l.add("a")
console.log(node.get("하하"))
l.add("a")
console.log(node.get("하하"))
l.add("a")
console.log(node.get("하하"))
l.add("a")
console.log(node.get("하하"))

console.log(member.filter((element)=> element != "a"))



