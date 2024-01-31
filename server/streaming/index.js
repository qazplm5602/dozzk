const NodeMediaServer = require('node-media-server');
const mysql = require("../lib/mysql.js");

const config = {
  logType: 1,

  logging: {},

  rtmp: {
    port: 3500,
    chunk_size: 60000,
    gop_cache: true,
    ping: 30,
    ping_timeout: 60
  },
  http: {
    port: 8000,
    mediaroot: './media',// 방송 송출할 파일의 위치
    allow_origin: '*'
  },
  trans: {
    ffmpeg: './streaming/ffmpeg/bin/ffmpeg.exe', //ffmpeg가 설치된 경로
    tasks: [
      {
        app: 'live', // 포트번호 뒤에 입력할 url(?)
        hls: true,
        hlsFlags: '[hls_time=2:hls_list_size=3:hls_flags=delete_segments]',
        // hls_time: 하나의 ts파일 당 길이. hls_list_size: 유지 할 hls파일 갯수.
        hlsKeep: true, // to prevent hls file delete after end the stream
        dash: true,
        dashFlags: '[f=dash:window_size=3:extra_window_size=5]',
        dashKeep: true // to prevent dash file delete after end the stream
      }
    ]
  }
};

const live_players = {};
const nms = new NodeMediaServer(config);

nms.check('prePublish', async (args) => {
    const channelID = args.stream.key;
    const secret = args.data.secret;
    if (channelID.length === 0 || secret === undefined || live_players[channelID] !== undefined /* 이미 라이브중 */) {
        return false;
    }

    // 검증
    const connection = await mysql.getConnection().catch(() => {});
    if (connection === undefined) {
        connection.release();
        return false;
    }

    const rows = await connection.Aquery("SELECT token FROM dozzk_channel WHERE id = ?", [ channelID ]).catch(() => {});
    connection.release();

    if (rows === undefined || rows.length === 0 || rows[0].token !== secret) {
        return false;
    }

    args.startDate = new Date();
    live_players[channelID] = args;
    return true;
});

nms.on('donePublish', (id, StreamPath, args) => {
    const channelID = StreamPath.split("/")[2];
    const streamer = live_players[channelID];
    if (streamer === undefined || streamer.id !== id) return;

    delete live_players[channelID];
});

nms.run();

exports.getStreamInfo = function(streamer) {
  const data = live_players[streamer];
  if (data === undefined) return false;

  return {
    date: data.startDate
  }
}