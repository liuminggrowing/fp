<!DOCTYPE >
<html>
	<?php
	header("Access-Control-Allow-Origin: http://fancyu.baidu.com");
                function ch2arr($str)
                {
                    $length = mb_strlen($str, 'utf-8');
                    $array = [];
                    for ($i=0; $i<$length; $i++)  
                        $array[] = mb_substr($str, $i, 1, 'utf-8');    
                    return $array;
                }
                $url = "https://api.fancyu.net/Video/UserVideo?video_id=".$_GET["video_id"];
		//$url = "http://test.vcast.baidu.com/Video/UserVideo?video_id=1006";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		echo '<script> var shareTitle = "'.$result["data"]["username"].'";</script>';
		echo '<script> var descContent = "'.$result["data"]["greetings"].'";</script>';
		echo '<script> var imageUrl = "'.$result["data"]["artworks"].'";</script>';
		//echo '<script> var username = "'.$result["data"]["username"].'";</script>';
	?>
<head>
	<meta charset="utf-8">
	<title>脸玩APP</title>
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no">
	<link rel="shortcut icon" href="http://v.fancyu.baidu.com/src/lianwan-icon.png?__inline" />
	<div id='wx_pic' style='margin:0 auto;display:none;'>
	<?php
		echo '<img src="'.$result['data']['artworks'].'" />';
	?>
	</div>
	<script>
        var _hmt = _hmt || [];
        (function() {
          var hm = document.createElement("script");
          hm.src = "https://hm.baidu.com/hm.js?204f833a78b7c016aafdd4ad74a2c0cf";
          var s = document.getElementsByTagName("script")[0]; 
          s.parentNode.insertBefore(hm, s);
        })();
        </script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','./js/analytics.js','ga');

  ga('create', 'UA-90673478-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
	
 <body>
	
<div id="cover" style="display: none; width: 100%; height: 669px;"></div>
<div id="guide" style="display: none; top: 5px;"><img src="./image/guide1.png"></div>
	<div class="fp-total">
		<div class="fp-head">
			<div class="fp-name fp-words"><?php
				if (empty($result['data']['username'])){
					echo "我给大家拜年啦！";
				}
				else{
					echo $result['data']['username'];
				}
			?></div>
		</div>
		<div class="fp-bgpaper">
			
			<?php echo '<div class="ve" id="ve"><video id="myVideo" loop="loop" class="fp-video1" src="'.$result['data']['video_url'].'"  type="video/mp4"  webkit-playsinline="true" x-webkit-airplay="true" playsinline="true" x5-video-player-type="h5" x5-video-player-fullscreen="true" poster="'.$result['data']['artworks'].'"></video><img id="video_poster" src="'.$result['data']['artworks'].'" style="display:none;margin-top:20px;"></img><img id="gplayer" src="./image/player.png" class="play"></img></div>';?>
                        <div class="fp-benediction">
				<div class="fp-benediction-words fp-words"><?php
					if (empty($result['data']['greetings'])){
						echo "祝大家新春快乐!鸡年大吉!";
					}
					else {
						echo $result['data']['greetings'];
					}
				?></div>
			</div>
			<div>
				<div class="fp-circles">
					<?php
						if (empty($result['data']['title'])){
							$result['data']['title'] = "掏心窝";
						}
						$arr = ch2arr($result['data']['title']);
						foreach ($arr as $k => $v){
							echo '<div class="fp-circle fp-words">'.$v.'</div>';

						} 
					?>
				</div>

				<div class="fp-description fp-words"><?php 

		      		     // 		$result["data"]["content"] = "请记住请记住请记请记住请记住请记住|一个红包|我们就能回去|我们曾经都是最好的";
						$desc = split("\|",$result["data"]["content"]);
						foreach ($desc as $k => $v){
							echo '<div >'.$v.'</div><br/>';
						}
					?></div>
							</div>

							<a id="fp_foot_share" href="https://itunes.apple.com/cn/app/faceu-ji-meng/id955253735"><img class="fp-foot" id="fp_foot" onclick="_system._guide(true)" src="http://fancyu-bj.bj.bcebos.com/src/foot_ios.png"></img></a>

		</div>
	</div>
</body>
<!--<link rel="stylesheet" href="http://v.fancyu.baidu.com/src/common3.css">-->
<link rel="stylesheet" href="./css/common.css">
<script src="./js/social-share.min.js"></script>
<script type="text/javascript" src="http://apps.bdimg.com/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="http://v.fancyu.baidu.com/src/rem.js"></script>
<script type="text/javascript" src="http://v.fancyu.baidu.com/src/iphone-inline-video.browser.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
<script type="text/javascript">
    var u = navigator.userAgent;
    var isAndroid = u.indexOf('Android') > -1 || u.indexOf('Adr') > -1; //android终端
    var isiOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端
    
    //var o = document.getElementById("myVideo");
    $("#video_poster").attr("width", document.getElementById("myVideo").offsetWidth)
    $("#video_poster").attr("height", document.getElementById("myVideo").offsetHeight)
    $("#video_poster").attr("margin-top", "20px");
    if (isAndroid) {
    	//$("#myVideo").attr("autoplay","autoplay");
  //  	$("#myVideo").removeAttr("webkit-playsinline");
  //  	$("#myVideo").removeAttr("x-webkit-airplay");
  //  	$("#myVideo").removeAttr("playsinline");
    	$("#myVideo").removeAttr("x5-video-player-type");
	$("#fp_foot").attr("src","http://fancyu-bj.bj.bcebos.com/src/foot_android.png");
	$("#fp_foot_share").removeAttr("href");

//    	$("#myVideo").removeAttr("x5-video-player-fullscreen");
//    	$("#myVideo").removeAttr("loop","loop");

        //获取video
        /*var oLiveVideo=document.getElementById("myVideo");
        //获取canvas画布
        var oLiveCanvas=document.getElementById("canvas");
        //设置画布
        var oLiveCanvas2D=oLiveCanvas.getContext('2d');
        //设置setinterval定时器
        var bLiveVideoTimer=null;
        //监听播放
        oLiveVideo.addEventListener('play',function() {
            bLiveVideoTimer=setInterval(function() {
                oLiveCanvas2D.drawImage(myPlayer,0,0,640,320);
            },20);
        },false);
        //监听暂停
        oLiveVideo.addEventListener('pause',function() {
            clearInterval(bLiveVideoTimer);
        },false);
        //监听结束
        oLiveVideo.addEventListener('ended',function() {
            clearInterval(bLiveVideoTimer);
        },false);
*/
    /*
    	$("#myVideo").attr("playsinline","true");
    	$("#myVideo").attr("x5-video-player-type","h5");
    	$("#myVideo").attr("x5-video-player-fullscreen","true");
    	$("#myVideo").attr("object-fit","fill");
    */
    }
    if (isiOS){
	$("#fp_foot").removeAttr("onclick");
    	//$("#myVideo").removeAttr("webkit-playsinline");
    	//$("#myVideo").removeAttr("x-webkit-airplay");
//    	$("#myVideo").removeAttr("playsinline");
//    	$("#myVideo").removeAttr("x5-video-player-type");
//    	$("#myVideo").removeAttr("x5-video-player-fullscreen");
//    	$("#myVideo").removeAttr("poster");
//    	$("#myVideo").removeAttr("autoplay");
//    	$("#myVideo").removeAttr("loop");
//    	$("#myVideo").attr("playsinline","true");
//    	$("#myVideo").attr("x5-video-player-type","h5");
//    	$("#myVideo").attr("x5-video-player-fullscreen","true");
    }
</script>

<script type="text/javascript">
$(function(){
 var video = $("#myVideo")[0];
 var video = $("#myVideo");
 if (video[0].played){
   	$(".play").css("opacity","0");
 }
 $(".ve").click(function(){
  if(video[0].paused){
   video[0].play();
   $(".play").css("opacity","0");
  }
  else{
   $(".play").css("opacity","1");
            video[0].pause();
        }
  return false;
    });
var num = $("div.fp-description").children("div").length;
if(num == 6){
    $(".fp-description").css("lineHeight","0.9rem");
    } else if(num == 5){
      $(".fp-description").css("lineHeight","1.1rem");
       }else if(num == 4){
        $(".fp-description").css("lineHeight","1.1rem");
         }else if(num ==3) {
          $(".fp-description").css("lineHeight","1.1rem");
           }
    $(".fp-description div").each(function(){
		    	var maxWidth=12;
			    	if($(this).text().length>maxWidth){
				    	var str = $(this).text().substring(0,maxWidth);
					$(this).html(str);
				}
									    });
 });
 
</script>

<script> 
     //一般情况下，这样就可以自动播放了，但是一些奇葩iPhone机不可以 
     document.getElementById('myVideo').play(); 
    //必须在微信Weixin JSAPI的WeixinJSBridgeReady才能生效 
    document.addEventListener("WeixinJSBridgeReady", function () { 
        document.getElementById('myVideo').play(); 
    }, false); 
</script> 
<script type="text/javascript">
    var _system={
        $:function(id){return document.getElementById(id);},
   _client:function(){
      return {w:document.documentElement.scrollWidth,h:document.documentElement.scrollHeight,bw:document.documentElement.clientWidth,bh:document.documentElement.clientHeight};
   },
   _scroll:function(){
      return {x:document.documentElement.scrollLeft?document.documentElement.scrollLeft:document.body.scrollLeft,y:document.documentElement.scrollTop?document.documentElement.scrollTop:document.body.scrollTop};
   },
   _cover:function(show){
      if(show){
	document.getElementById("myVideo").style.display="none";
	document.getElementById("video_poster").style.display="inline";
        //var vDiv = document.getElementById("ve");
        //var post = document.createElement("img");
        //post.setAttribute("src","imageUrl");
        //vDiv.appendChild(post);
        this.$("cover").style.display="block";
	this.$("cover").style.width=document.body.scrollWidth ;
	this.$("cover").style.height=document.body.scrollHeight ;
  }else{
     document.getElementById("video_poster").style.display="none";
     document.getElementById("myVideo").style.display="inline";
     this.$("cover").style.display="none";
     //var vDiv = document.getElementById("ve");
     //vDiv.removeChild(vDiv.childNodes[1]);
  }
   },
   _guide:function(click){
      this._cover(true);
      this.$("guide").style.display="block";
      this.$("guide").style.top=(_system._scroll().y+5)+"px";
      window.onresize=function(){_system._cover(true);_system.$("guide").style.top=(_system._scroll().y+5)+"px";};
  if(click){_system.$("cover").onclick=function(){
         _system._cover();
         _system.$("guide").style.display="none";
 _system.$("cover").onclick=null;
 window.onresize=null;
  };}
   },
   _zero:function(n){
      return n<0?0:n;
   }
}
</script>
<style type="text/css">
#mess_share{margin:15px 0;}
#share_1{float:left;width:49%;}
#share_2{float:right;width:49%;}
#mess_share img{width:22px;height:22px;}
#cover{display:none;position:absolute;left:0;top:0;z-index:18888;background-color:#000000;opacity:0.7;}
#guide{display:none;position:absolute;right:18px;top:5px;z-index:19999;}
#guide img{width:260px;height:180px;}
</style>

</html>


