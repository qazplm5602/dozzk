const express = require("express");
const cors = require('cors');
const app = express();

global.CONFIG = require("./config.json");

app.use(express.urlencoded({extended: true}));
app.use(express.json());
app.use(cors({
    origin: "http://localhost",
    credentials: true,
}));

const server = app.listen(3000, () => console.log("API 3000 열림"));

const live = require("./streaming");
require("./thumbnail").init(app);
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

app.get("/channel/lives", function(req, res) {
    res.json(live.getLiveStreams());
});