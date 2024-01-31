const webSocket = require("ws");

const rooms = {};
const userAccess = {};
const rgbList = [
    [186, 130, 190],
    [233, 127, 88],
    [148, 201, 148]
];

const generateRandomString = (num) => {
    const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let result = '';
    const charactersLength = characters.length;
    for (let i = 0; i < num; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
  
    return result;
}

function getRandom(min, max) {
    return Math.floor((Math.random() * (max - min + 1)) + min);
}

exports.init = function(server, express) {
    const ws = new webSocket.Server({ server, path: "/chat" });
    ws.on("connection", (connection, req) => {
        const param = new URLSearchParams(req.url.substring(req.url.indexOf('?') + 1));
        
        if (!param.has("channel")) {
            return connection.close();
        }

        const channelID = param.get("channel");
        if (channelID.length === 0) return connection.close();

        // 로그인 시도
        let my_id;
        let my_name;
        if (connection.protocol.length > 0) {
            const user = userAccess[connection.protocol];
            if (user === undefined) return connection.close();

            // 시간 만료?
            if ((new Date() - user.time) > 1000 * 60 /* 1분 */) {
                delete userAccess[connection.protocol];
                return connection.close();
            }

            my_id = user.id;
            my_name = user.name;

            connection.on("message", function(message) {
                try {
                    message = JSON.parse(message);
                } catch {return};

                if (message.type === "chat") {
                    broadcastChat(channelID, my_id, message);
                }
            });
        }

        if (rooms[channelID] === undefined) rooms[channelID] = {};

        if (my_id === undefined) my_id = "guest-"+generateRandomString(10);

        connection.old_send = connection.send;
        connection.send = function(type, data) {
            connection.old_send(JSON.stringify({
                type,
                data
            }));
        }

        rooms[channelID][my_id] = {
            name: my_name || "GUEST",
            color: rgbList[getRandom(0, rgbList.length - 1)],
            socket: connection
        }

        connection.once("close", function() {
            if (rooms[channelID] === undefined) return;
            delete rooms[channelID][my_id];
            
            if (Object.keys(rooms[channelID]).length === 0)
                delete rooms[channelID];
        });
    });

    express.post("/chat/register", function(req, res) {
        if (req.body.secret !== CONFIG.secret) {
            return res.sendStatus(401);
        }

        if (typeof req.body.id !== "string" || typeof req.body.name !== "string") {
            return res.sendStatus(400);
        }
        
        const token = generateRandomString(20);
        userAccess[token] = {
            id: req.body.id,
            name: req.body.name,
            time: new Date()
        };

        res.send(token);
    });
}

exports.getChannelClients = function(channel) {
    if (rooms[channel] === undefined) return 0;
    return Object.keys(rooms[channel]).length;
}

function broadcastChat(channel, id, data) {
    if (typeof(data.content) !== "string" || data.content.length === 0) return;

    const room = rooms[channel];
    if (room === undefined) return;
    const user = room[id];
    if (user === undefined) return;

    Object.values(room).forEach(n_user => {
        n_user.socket.send("chat.result", { id, name: user.name, color: user.color, content: data.content });
    });
}