$(function() {
    $(".info-title").hide();
    $(".misc-texts").hide();
    $(".chat-tail").hide();
    $(".channel-info > .game").hide();

    $.get(DOZZK_API+"channel/info/"+dozzk_channel_id, function( data ) {
        if (!data.live) {
            return; // 방송중이지 않음
        }
        
        $(".info-title").show();
        $(".misc-texts").show();
        $(".chat-tail").show();
        $(".channel-info > .game").show();

        // 정보 반영
        $(".misc-people").text("현재 "+numberWithCommas(data.clients)+" 시청중");
        setInterval(() => {
            const time = new Date() - new Date(data.date);
            const hours = Math.floor(time / (1000 * 60 * 60));
            const minutes = Math.floor((time % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((time % (1000 * 60)) / 1000);

            $(".misc-time").text(`${hours.toString().padStart(2,"0")}:${minutes.toString().padStart(2,"0")}:${seconds.toString().padStart(2,"0")} 스트리밍 중`);
        }, 500);

        // 비디오 재생
        const videoUrl = DOZZK_LIVE_URL+dozzk_channel_id+"/index.m3u8";
        const videoTarget = $(".live-video")[0];
        if (Hls.isSupported()) {
            const hls = new Hls({
                autoStartLoad: false,
            });
            hls.on(Hls.Events.MEDIA_ATTACHED, function () {
                videoTarget.muted = true;
                videoTarget.play();
            });

            hls.loadSource(videoUrl);
            hls.attachMedia(videoTarget);
            hls.startLoad();
        } else {
            videoTarget.src = videoUrl;
        }

        // 채팅
        connectChat();

        registerPing();
    }).fail(function() {
        console.error("err!");
    });
});

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function escapeHtml( text ) {
    var map = {
      '&': '&amp;',
      '<': '&lt;',
      '>': '&gt;',
      '"': '&quot;',
      "'": '&#039;'
    };
    
    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function registerPing() {
    setInterval(() => {
        $.get(DOZZK_API+"channel/info/"+dozzk_channel_id, function( data ) {
            if (!data.live) {
                location.reload();
                return;
            }
    
            $(".misc-people").text("현재 "+numberWithCommas(data.clients)+" 시청중");
        }).fail(function() {
            console.error("ping err!");
        });
    }, 1000 * 10);
}

const wsCallback = {};
let scrollLock = true;
async function connectChat() {
    // $("#chat-input").attr("placeholder", "접속중...");

    let protocol;
    
    if (dozzk_is_logined) {
        protocol = await new Promise((reslove,reject) => $.get("./chatlogin.api.php", function(data) {
            reslove(data);
        }).fail(reject));
    }

    const ws = new WebSocket(DOZZK_API.replace("https", "wss").replace("http", "ws")+"chat?channel="+dozzk_channel_id, protocol);
    ws.onopen = function() {
        if (dozzk_is_logined) {
            $("#chat-input").attr("placeholder", "채팅을 입력하세요");

            $("#chat-input").focus(function() {
                $(this).parent().addClass('active');
            }).focusout(function() {
                if ($(this).val() === "")
                    $(this).parent().removeClass('active');
            }).keydown(function(event) {
                if (event.keyCode === 13) {
                    if ($(this).val().length === 0) {
                        return $(this).blur();
                    }
                    $(".chat-send").trigger('click');
                }
            }).keyup(function() {
                const value = $(this).val();
                if (value.length > 0) {
                    $(".chat-send").addClass("active").attr("disabled", false);
                } else {
                    $(".chat-send").removeClass("active").attr("disabled", true);
                }
            });

            // 전송 버튼
            $(".chat-send").click(function() {
                const value = $("#chat-input").val();
                if (value.length === 0) return;
                
                $("#chat-input").val("");
                $(".chat-send").removeClass("active").attr("disabled", true);
                ws.send(JSON.stringify({
                    type: "chat",
                    content: value
                }));
            });
        }
    }
    ws.onmessage = function(result) {
        const message = JSON.parse(result.data);
        const cb = wsCallback[message.type];
        if (typeof cb !== "function") {
            return console.error(message.type+" not register callback websocket!");
        }

        cb(message.data);
    }
    ws.onclose = function() {

    }

    $(".chat-content").scroll(function(event){
        var scrollTop = $(this).scrollTop();
        var innerHeight = $(this).innerHeight();
        var scrollHeight = $(this).prop('scrollHeight');
        
        scrollLock = scrollTop + innerHeight >= scrollHeight;
    });
}

// websocket callback
wsCallback["chat.result"] = function(data) {
    $(".chat-content").append(`<div><span style="color: rgb(${(data.color).join(",")});">${data.name}</span>${escapeHtml(data.content)}</div>`);

    if (scrollLock) {
        $('.chat-content').scrollTop($('.chat-content')[0].scrollHeight);
    }
}