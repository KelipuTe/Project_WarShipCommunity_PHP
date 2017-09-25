var http = require('http').Server();
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('public-channel');
/*channel就是上面的public-channel*/
redis.on('message',function (channel,message) {
    //console.log(message);
    message = JSON.parse(message);//解析json
    /*用emit方法进行数据分发*/
    io.emit(channel+':'+message.event,message.data);//public-channel:publicChat
});

http.listen(3000,function () {
    console.log('server start')
});