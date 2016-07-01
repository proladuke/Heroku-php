var http = require('http');
var WebSocketServer = require('websocket').server;
var fs = require('fs');

var server = http.createServer(function(req, res) {
  fs.readFile(__dirname + '/client.html', function(err, data) {
    res.writeHead(200);
    res.end(data);
  });
});
server.listen(8080);

// WebSocketサーバの作成
var wsServer = new WebSocketServer({
    httpServer: server,
    autoAcceptConnections: true
});

// クライアントからのWebSocket接続時の処理
wsServer.on('connect', function(connection) {
  console.log('Connection accepted, protocol version ' + connection.webSocketVersion);
  connection.send('Hello, world');

  // クライアントからのメッセージ受信処理
  connection.on('message', function(message) {
    console.log('Received Message: ' + message.utf8Data);
  });
});
