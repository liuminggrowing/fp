! function($) {
    /*--ga:begin--*/
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-53479660-16', 'auto');
    var vid = getQuery('video_id'),
        page = 'videoapp_share/detail?vid=' + vid;
    window.ga('send', 'pageview', {
        page: page
    });
    /*--ga:end--*/

    var RELATED_VIDEO_PLAY_TIME = 60;

    function getQuery(k) {
        var obj={};
        location.href.replace(/([^;?&]+)=([^;?&]+)/g, function($, $1, $2) {obj[decodeURIComponent($1)]=decodeURIComponent($2);});
        return obj[k];
    }

    function appendVideo(src, vid, related, thumb) {
        var $videoBox = $('#video-box'), timer, player,
            $mask = $('<div class="mask">60 Seconds Preview</div>'),
            $overlay = $('<div class="overlay hide"><div class="popup">'
                       + 'Download Short Tube<br/>to watch more</div></div>');

        function autoPause() {
            timer && clearTimeout(timer);
            var currentTime = (5 == src) ? player.currentTime : player.getCurrentTime(),
                time = RELATED_VIDEO_PLAY_TIME - currentTime;
            if (time > 0) {
                timer = setTimeout(autoPause, 1000*time);
                return;
            }
            if (5 == src) {
                player.pause();
            }
            if (1 == src) {
                player.stopVideo();
            }
            $videoBox.prepend($overlay).find('.overlay').show();
        }
        // youtube
        if (1 == src) {
            var tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            var firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
            window.onYouTubeIframeAPIReady = function() {
                player = new YT.Player('player', {
                    videoId: vid,
                    events: {
                        'onReady': function(event) {
                            window.ga('send', 'event', page, 'play', vid);
                            if (!related) {
                                event.target.playVideo();
                            } else {
                                $mask.prependTo($videoBox);
                            }
                        },
                        'onStateChange': function(event) {
                            if ($('.mask')) {$('.mask').remove();$mask=null;}
                            if (related && event.data == YT.PlayerState.PLAYING) {
                                autoPause();
                            }
                        }
                    }
                });
            }
        }
        // googledrive
        else if (4 == src) {
            // 两种格式的播放url，分开处理
            if (vid.indexOf('view') === -1) {
                var serial = vid.split('=')[1];
                vid = 'https://drive.google.com/file/d/' + serial + '/preview';
                $('#player').replaceWith('<iframe src="' + vid + '"></iframe>');
            } else {
                $('#player').replaceWith('<iframe src="' + vid.replace('view', 'preview') + '"></iframe>');
            }
            window.ga('send', 'event', page, 'play', vid);
            if (related) {
                timer = setTimeout(function () {
                    $videoBox.prepend($overlay).find('.overlay').show();
                }, 1000*RELATED_VIDEO_PLAY_TIME*2); //googledrive的相关视频播放2min
            }
        }
        // other
        else if (5 == src) {
            $('#player').replaceWith('<video id="player" src="'+vid+'" controls poster="'+thumb+'">your browser does not support HTML5 video.</video>');
            player = document.getElementById('player');
            if (related) {
                $mask.prependTo($videoBox);
                /*$mask.prependTo($videoBox);*/
                player.onplay = function() {
                    window.ga('send', 'event', page, 'play', vid);
                    if ($('.mask')) {$('.mask').remove();$mask=null;}
                    autoPause();
                }
            } else {
                player.play();
            }
        }
    }

    $('.related-container').on('click', 'a', function() {
        var $this = $(this)
        location.href = location.origin + location.pathname + '?related=1&video_id=' + $this.attr('id')
    })

    $('.install-btn').on('click', function() {
        var pos = $(this).data('pos');
        window.ga('send', 'event', page, 'install', pos);
    })

    // 拉取数据
    $.ajax({
        type: "POST",
        url: "/VideoAppShare/GetVideoInfo",
        data:{
            video_id: vid,
            related: getQuery('related')
        },
        timeout: 10000,
        dataType: "json",
        success: function(result){
            if (result && result.retCode == 0) {
                var related = result.data.recommend,
                    comment = result.data.comments_hot,
                    src = result.data.src,
                    vid = result.data.youtube_id ? result.data.youtube_id : result.data.video_url;

                $('.video-container').html(template('video-tpl', {data: result.data}));

                appendVideo(result.data.src, vid, result.data.related, result.data.thumb);

                if (related.length) {
                    $('.related-container').html(template('related-tpl', {data: related}));
                    $('.related').show();
                }

                if (comment.length) {
                    $('.comment-container').html(template('comment-tpl', {data: comment}));
                    $('.comment').show();
                }
            }
        },
        error: function(xhr, errorType, error){
            var $overlay = $('#overlay');
            if (errorType === "timeout"){
                $('#popup_timeout').show().siblings().hide();
            }
            else{
                $('#popup_internet_error').show().siblings().hide();
            }
            $overlay.show();
        }
    });
}(Zepto)

