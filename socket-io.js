var http = require('http').Server();
var io = require('socket.io')(http); // 使用 socket.io
var Redis = require('ioredis'); // 启动 Redis
var redisChat = new Redis(); // 获得 redis 实例
var redisUserSignIn = new Redis(); // 获得 redis 实例

redisChat.subscribe('public-channel'); // 获取 redis 数据库 public-channel 消息队列中的数据
// channel 就是上面的 public-channel, message 是获得的数据
redisChat.on('message',function (channel,message) {
    message = JSON.parse(message); // 解析 json
    // 用 emit() 方法进行数据分发
    io.emit(channel+':'+message.event,message.data); // public-channel:publicChat
});

redisUserSignIn.subscribe('public-channel-user'); // 获取 redis 数据库 public-channel-user 消息队列中的数据
redisUserSignIn.on('message',function (channel,message) {
    message = JSON.parse(message);
    io.emit(channel+':'+message.event,message.data); // public-channel-user:publicChatUserSignIn
});

// 启动服务器
http.listen(3000,function () {
    console.log('server start');
});