
require('../css/justwave.player.css');

(function($){$.justwave=function(options,classOnly)
{var html='\
<div class="justwave_wrapper">\
 <img class="justwave_wave" src="" alt="" ondragstart="return false"/>\
 <div class="justwave_playhead">\
  <img class="justwave_progress" src="" alt="" ondragstart="return false"/>\
  <span class="justwave_curpos">00:00</span>\
 </div>\
 <span class="justwave_duration">00:00</span>\
 <span class="justwave_curfocus">00:00</span>\
 <span class="justwave_songname"></span>\
 <button class="justwave_playpause">\
  <svg width="100%" height="100%" viewBox="0 0 44 44" xmlns="http://www.w3.org/2000/svg">\
  <circle r="21.5" cy="21.5" cx="21.5" stroke="#30C000" fill="#30C000"/>\
  <path d="M31,21.5L17,33l2.5-11.5L17,10L31,21.5z" fill="#FFF" class="justwave_play"/>\
  <g class="justwave_pause" fill="#FFF">\
   <rect height="19" width="5" y="12" x="15"/><rect height="19" width="5" y="12" x="23"/>\
  </g>\
  </svg>\
 </button>\
</div>';var els=$('audio');if(typeof options=='string')
classOnly=options;if(typeof classOnly=='string')
els=els.filter('.'+classOnly);els.each(function(){var p=this,$p=$(this);p.opts={ajax:'justwave.ajax.php',width:500,height:100,wave_color:'#909296',prog_color:'#FF530D',back_color:'',buttoncolor:'#A47655',buttonsize:0,showname:1,namesize:15,showtimes:1,nowaves:0};if($.isPlainObject(options))
$.extend(p.opts,options);if($p.attr('width'))
p.opts.width=$p.attr('width');if($p.attr('height'))
p.opts.height=$p.attr('height');if($p.attr('poster'))
p.opts.poster=$p.attr('poster');for(var attr,i=0,attrs=p.attributes,n=attrs.length;i<n;i++){attr=attrs[i];if(attr.nodeName.substring(0,5)=='data-')
p.opts[attr.nodeName.substring(5)]=attr.nodeValue;}
if(p.opts.chained)
$p.addClass('justwave_chained');$p.after(html).on('loadedmetadata',function(e){var p=e.target,song=$(p).next();clearWaves(p);normalizePlayPauseButton(p);p.xduration=p.duration;song.find('.justwave_duration').text(toMinSec(p.xduration));if(!!+p.opts.showtimes)
song.find('.justwave_duration, .justwave_curpos').show();else
song.find('.justwave_duration, .justwave_curpos').hide();if($(p).attr('src'))
p.opts.audio=$(p).attr('src');else
p.opts.audio=p.currentSrc;var songName=song.find('.justwave_songname').css('font-size',p.opts.namesize+'px').text(decodeURIComponent(p.opts.audio.replace(/.+[\\\/]/,'')));if(!!+p.opts.showname)
songnameShow(songName,true);if(!+p.opts.nowaves)
$.ajax(p.opts.ajax,{dataType:'json',type:'POST',data:p.opts}).done(function(data)
{var song=$(p).next(),waveImg=song.find('.justwave_wave'),progImg=song.find('.justwave_progress');if(data.status=='ok'){waveImg.width(p.opts.width);progImg.width(p.opts.width);song.css('background','');song.find('.justwave_playhead').css('background','');if(p.opts.poster)
song.css('background-image','url('+p.opts.poster+')');waveImg.attr('src',data.waveurl);if(p.opts.wave_color==p.opts.prog_color)
progImg.attr('src',data.waveurl);else
progImg.attr('src',data.progressurl);}
p.xduration=parseFloat(data.duration);if(!p.xduration)
p.xduration=p.duration;song.find('.justwave_duration').text(toMinSec(p.xduration));});}).on('playing',function(e){var p=e.target,song=$(p).next();song.find('.justwave_pause').show();song.find('.justwave_play').hide();if(p.opts.chained)
$('audio.justwave_chained').each(function(){if(this!=p)this.pause();});}).on('pause',function(e){var song=$(e.target).next();song.find('.justwave_pause').hide();song.find('.justwave_play').show();}).on('error',function(e){var p=e.target,song=$(p).next();clearWaves(p);song.find('.justwave_pause, .justwave_play').hide();song.find('.justwave_playpause').prop('disabled',true);song.find('.justwave_songname').text($(p).attr('src').replace(/.+[\\\/]/,''));song.find('.justwave_duration').text('00:00');p.xduration=0;song.find('.justwave_duration, .justwave_curpos, .justwave_curfocus').hide();}).on('timeupdate',function(e){updatePlayhead(this);}).on('ended',function(e){this.pause();});clearWaves(p);normalizePlayPauseButton(p);var song=$p.next();song.click(function(e){e.preventDefault();var p=$(this).prev()[0],scrl=findScroll(this),mouseX=e.pageX-scrl.scrLeft-leftPos(this);p.currentTime=mouseX*p.xduration/this.offsetWidth;updatePlayhead(p);}).mousemove(function(e){var scrl=findScroll(this),mouseX=e.pageX-scrl.scrLeft-leftPos(this),mouseY=e.pageY-scrl.scrTop-topPos(this),song=this,p=$(this).prev()[0];$(song).find('.justwave_curfocus').text(toMinSec(mouseX/this.offsetWidth*p.xduration)).css({top:mouseY-7+'px',left:mouseX+7+'px'});if(!!+p.opts.showname)
songnameShow($(song).find('.justwave_songname'));}).mouseleave(function(){$(this).find('.justwave_curfocus').hide();$(this).find('.justwave_playpause').fadeOut(1000);}).mouseenter(function(){var p=$(this).prev()[0]
$(this).find('.justwave_playpause').stop(false,true).fadeIn(500);if(!!+p.opts.showtimes)
$(this).find('.justwave_curfocus').show();});song.find('.justwave_playpause').click(function(e){e.stopPropagation();e.preventDefault();var song=$(this).parent(),p=song.prev()[0];if(p.ended)
p.currentTime=0;if(p.paused)
p.play();else
p.pause();}).mouseenter(function(e){$(this).siblings('.justwave_curfocus').hide();}).mouseleave(function(e){var p=$(this).parent().prev()[0];if(!!+p.opts.showtimes)
$(this).siblings('.justwave_curfocus').show();});});};var updatePlayhead=function(p)
{var newWidth=p.currentTime/p.xduration*100,song=$(p).next();if(newWidth<=100.10)
song.find('.justwave_playhead').width(newWidth+'%');song.find('.justwave_curpos').text(toMinSec(p.currentTime));},toMinSec=function(time)
{var min=Math.floor(time/60),sec=Math.floor(time%60);if(isNaN(time))
return'00:00';return(min<10?'0':'')+min+':'+(sec<10?'0':'')+sec;},songnameShow=function(el,force)
{if(force)el.stop(true,true).hide();if(!el.is(':visible'))
el.fadeIn(300,function(){el.fadeOut(7000,'swing');});},leftPos=function(elem)
{var curleft=0;if(elem.offsetParent){do{curleft+=elem.offsetLeft;}while(elem=elem.offsetParent);}
return curleft;},topPos=function(elem)
{var curtop=0;if(elem.offsetParent){do{curtop+=elem.offsetTop;}while(elem=elem.offsetParent);}
return curtop;},findScroll=function(elem)
{var scrLeft=0,scrTop=0,offEl=$(elem).offsetParent().css('position').toLowerCase();if(offEl=='fixed'){scrLeft=$(window).scrollLeft();scrTop=$(window).scrollTop();}
return{scrLeft:scrLeft,scrTop:scrTop};},clearWaves=function(p)
{var song=$(p).next();song.find('.justwave_wave').width(0).attr('src','');song.find('.justwave_progress').width(0).attr('src','');song.width(p.opts.width).height(p.opts.height).css('background',p.opts.wave_color);if(p.opts.poster)
song.css('background-image','url('+p.opts.poster+')');song.find('.justwave_playhead').css('background',p.opts.prog_color);},normalizePlayPauseButton=function(p)
{var song=$(p).next(),ppSize;if(p.opts.buttonsize)
ppSize=p.opts.buttonsize;else{ppSize=parseInt(p.opts.height*0.50);if(ppSize>88)
ppSize=88;if(ppSize<33)
ppSize=p.opts.height-1;}
song.find('.justwave_playpause').width(ppSize).height(ppSize).prop('disabled',false).find('circle').attr({stroke:p.opts.buttoncolor,fill:p.opts.buttoncolor});song.find('.justwave_pause').hide();song.find('.justwave_play').show();};})(jQuery);