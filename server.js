const fs = require("fs");

// Produccion
const server = require('https').createServer({
  key: fs.readFileSync("/etc/letsencrypt/live/capresi.net/privkey.pem"),
  cert: fs.readFileSync("/etc/letsencrypt/live/capresi.net/fullchain.pem")
});

// Local
// const server = require('http').createServer();

const io = require('socket.io')(server, {
    cors: { origin: "*"}
});


// io.on('connection', (socket) => {
//     socket.on('chat message', (msg) => {
//       console.log('message: ' + msg);
//     });
//   });

io.on('connection', (socket) => {
    console.log('connection');

    socket.on('reload score', data => {
        io.emit(`change score`, data);
        // console.log('message: ' + data.id);
    });

    // socket.on('chat message', (msg) => {
    //     console.log('message: ' + msg);
    // });


    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});

server.listen(3000, () => {
    console.log('Server Socket.io is running');
});