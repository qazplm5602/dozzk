const express = require("express");
const app = express();

global.CONFIG = require("./config.json");

app.use(express.urlencoded({extended: true}));
app.use(express.json());

const server = app.listen(3000, () => console.log("API 3000 열림"));

const live = require("./streaming");
const chat = require("./chat")
chat.init(server, app);

app.get("/channel/info/:channel", function(req, res) {
    const channel = req.params.channel;
    if (typeof channel !== "string" || channel.length === 0) {
        return res.sendStatus(400);
    }

    const stream = live.getStreamInfo(channel);
    if (!stream) return res.json({live: false});

    res.json({
        live: true,
        date: stream.date,
        clients: chat.getChannelClients(channel)
    });
});