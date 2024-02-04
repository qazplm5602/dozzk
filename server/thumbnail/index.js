const fs = require("fs");
const ffmpeg = require('fluent-ffmpeg');
ffmpeg.setFfmpegPath("./streaming/ffmpeg/bin/ffmpeg.exe");

const cacheImageBuffers = {};
const waitUsers = {};

exports.init = function(express) {
    express.get("/channel/thumbnail/:channel", function(req, res) {
        const channel = req.params.channel;

        const cacheImg = cacheImageBuffers[channel];

        if (cacheImg === false) {
            if (waitUsers[channel] === undefined) waitUsers[channel] = [];
            waitUsers[channel].push(function() {
                // 다시 불러오기
                const reCacheImg = cacheImageBuffers[channel];
                if (reCacheImg === undefined) {
                    res.sendStatus(500);
                } else {
                    res.set("Content-type", "image/jpeg").send(reCacheImg[0]);
                }
            });
            return;
        }

        if (cacheImg === undefined || (new Date() - cacheImg[1]) > 1000 * 60) {
            cacheImageBuffers[channel] = false;

            let m3u8Contents;
            try {
                m3u8Contents = fs.readFileSync(`./media/live/${channel}/index.m3u8`);
            } catch(err) {
                cacheImageBuffers[channel] = undefined;
                CallbackResultImage(channel);
                return res.sendStatus(404);
            }
            
            const Line = m3u8Contents.toString().split("\n");
            let findFile;
            Line.forEach(data => {
                if (data.indexOf(".ts") !== -1) findFile = data;
            });

            const outputFilename = generateRandomString(5)+".jpg";
            
            ffmpeg(`./media/live/${channel}/${findFile}`)
            .frames(1) // 첫 번째 프레임만 추출
            .size('1280x?').aspect('16:9') // 해상도 강제 변경 (사이즈 줄이기)
            .output("./thumbnail/cache/"+outputFilename)
            .on("error", () => {
                cacheImageBuffers[channel] = undefined;
                CallbackResultImage(channel);
                res.sendStatus(500);
            })
            .on('end', () => {
                let jpgContents;
                try {
                    jpgContents = fs.readFileSync("./thumbnail/cache/"+outputFilename);
                } catch {
                    cacheImageBuffers[channel] = undefined;
                    CallbackResultImage(channel);
                    res.sendStatus(500);
                    return;
                }

                fs.rm("./thumbnail/cache/"+outputFilename, { force: true }, () => {});

                cacheImageBuffers[channel] = [jpgContents, new Date()];
                res.set("Content-type", "image/jpeg").send(jpgContents);
                CallbackResultImage(channel);
            })
            .run();

            return;
        }

        res.set("Content-type", "image/jpeg").send(cacheImg[0]);
    });
}

function CallbackResultImage(channel) {
    if (waitUsers[channel] === undefined) return;
    
    waitUsers[channel].forEach(cb => cb());
    waitUsers[channel] = undefined;
}

const generateRandomString = (num) => {
    const characters ='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    let result = '';
    const charactersLength = characters.length;
    for (let i = 0; i < num; i++) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
  
    return result;
}