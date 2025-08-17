// JavaScript Document
// JavaScript Document
//æµ‹è¯•è½½å…¥iconå›¾ç‰‡
(function(){var img=new Image();img.src='/favicon.ico';}());

function getDomain(url){url=url.toLowerCase().replace('http://','').replace('https://','');if(url.indexOf('/')>4)url=url.substring(0,url.indexOf('/'));var us=url.split('.');var l=us.length;if(l<3)return url;else return us[l-2]+'.'+us[l-1];};/*èŽ·å–åŸŸå*/
if(this!=top)if(getDomain(top.location.href)!=getDomain(this.location.href))top.location.href=this.location.href;/*ç¦æ­¢è¢«å¤–éƒ¨ç½‘ç«™frame*/
function $(o){if(typeof(o)=='string')return document.getElementById(o);return o;};/*æ ¹æ®IDèŽ·å–å…ƒç´ */
function $$(o,tag){if(!$(o))return null;return $(o).getElementsByTagName(tag);}/*æ ¹æ®IDèŽ·å–å…ƒç´ å†…éƒ¨æŸç§æ ‡ç­¾ï¼Œè¿”å›žobject array*/
function $S(o,tag,className){var ds=$$($(o),tag);var cs=new Array();for(var i=0;i<ds.length;i++){var cn=' '+ds[i].className+' ';if(cn.indexOf(' '+className+' ')>-1)cs[cs.length]=ds[i];};return cs;};/*æ ¹æ®æ ·å¼è¿”å›žå…ƒç´ æ•°ç»„*/
function $A(o,tag,attribute,attributeValue){var ds=$$($(o),tag);var cs=new Array();for(var i=0;i<ds.length;i++){if(ds[i].getAttribute(attribute)==attributeValue)cs[cs.length]=ds[i];};return cs;};/*æ ¹æ®ç”¨æˆ·è‡ªå®šä¹‰attributeè¿”å›žå…ƒç´ æ•°ç»„*/
function $CE(parent,tag,beforeObj,ot){var no=document.createElement(tag);if(ot)no.type=ot;if(beforeObj)parent.insertBefore(no,beforeObj);else parent.appendChild(no);return no;};/*åˆ›å»ºä¸€ä¸ªæ–°çš„å…ƒç´ $CreateElement*/

/*åˆ¤æ–­æ˜¯å¦æ”¯æŒhtmlæ ‡ç­¾*/
var HTML5=(function(){if(!!document.createElement('video').canPlayType){var vidTest=document.createElement('video');var oggTest=vidTest.canPlayType('video/ogg; codecs="theora, vorbis"');if (!oggTest){return vidTest.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');}else{return true;};}else{return false;};}());
/*å¦‚æžœä¸æ”¯æŒhtml5ï¼Œåˆ™éœ€åˆå§‹åŒ–ä¸€æ¬¡ï¼Œæ­¤åˆå§‹åŒ–æš‚æ—¶åªé’ˆå¯¹IEï¼Œå…¶å®ƒæµè§ˆå™¨å°šæœªæµ‹è¯•ã€‚Erræ ‡ç­¾ä¸ºæˆ‘è‡ªå·±è®¾å®šçš„ï¼Œç”¨äºŽæ˜¾ç¤ºé”™è¯¯çš„*/
if(!HTML5){var e=('abbr,article,aside,audio,canvas,datalist,details,err,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video').split(',');for(var i=0;i<e.length;i++)document.createElement(e[i]);};
/*ç»‘å®šäº‹ä»¶åˆ°å¯¹åƒ*/
function bindEvent(elm, evType, fn, useCapture){if (elm.addEventListener){elm.addEventListener(evType, fn, useCapture);return true;}else if(elm.attachEvent){var r = elm.attachEvent('on' + evType, fn);return r;}else{elm['on' + evType] = fn;};};
/*èŽ·å–ç½‘å€ä¸­çš„æ–‡ä»¶å*/
function getFileName(url){var e=url.length;if(url.indexOf('?')>3)e=url.indexOf('?');url=url.substring(0,e);var b=0;if(url.indexOf('/')>-1)b=url.lastIndexOf('/')+1;return url.substring(b,url.length).toLowerCase();};
/*èŽ·å–urlä¼ é€’çš„æŸä¸ªå€¼*/
function request(s){var u=window.location.href;if(u.indexOf('?')==-1)return '';while(u.indexOf(' =')>-1)u=u.replace(' =','=');if(u.toLowerCase().indexOf(s.toLowerCase()+'=')==-1)return '';u=decodeURI(u);u=u.substring(u.toLowerCase().indexOf(s.toLowerCase()+'=')+s.length+1,u.length);if(u.indexOf('&')==-1)return u;else return u.substring(0,u.indexOf('&'));};
/*èŽ·å–æŸä¸ªcookieçš„å€¼*/
function getCookie(c){var dc=document.cookie;if(dc.length>0){var cs=dc.indexOf(c + '=');if (cs>-1){cs=cs + c.length+1;var ce=dc.indexOf(';',cs);if (ce==-1) ce=dc.length;return unescape(dc.substring(cs,ce));};};return '';}
/*è®¾ç½®ä¸‹æ‹‰æ¡†é€‰ä¸­çš„å€¼*/
function setSelectValue(o,v){o=$(o);if(!o)return;if(o.length==0)return;for(var i=0;i<o.length;i++){if(o.options[i].value==v)o.options[i].selected=true;return;};o.options[0].selected=true;};
/*å›¾ç‰‡ç¼©æ”¾å‡½æ•° æŒ‰å›ºå®šæ¯”ä¾‹ç¼©æ”¾ï¼Œä¸å¤Ÿçš„æ•°å€¼ç”¨paddingè¡¥ä¸Šï¼Œæœ€ç»ˆå ä½ä¸Žç›®æ ‡å¤§å°ä¸€è‡´ã€‚å‚æ•°fä¸ºå¸ƒå°”å€¼ï¼Œå¦‚ä¸ºçœŸï¼Œå½“å›¾ç‰‡çš„çœŸå®žå¤§å°å°äºŽç›®æ ‡å¤§å°æ—¶ä¹Ÿè¿›è¡Œç¼©æ”¾ï¼›å¦‚æžœä¸ºå‡åˆ™ä¸è¿›è¡Œ*/
function zoomPic(o,w,h,f){if(!o)return;o.style.padding='0';if(!f||o.width>w||o.height>h){if(o.width/w>o.height/h){o.height=w/o.width*o.height;o.width=w;}else{o.width=h/o.height*o.width;o.height=h;};};if(o.offsetWidth<=w||o.offsetHeight<=h)o.style.padding=(h-o.height)/2+'px '+(w-o.width)/2+'px';}
/*å›¾ç‰‡ç¼©æ”¾å‡½æ•°ï¼Œåªæ˜¯å°†å›¾ç‰‡ç¼©æ”¾åˆ°ç›®æ ‡å°ºå¯¸çš„å¤§å°ä»¥å†…*/
function zoomPicNoPad(o,w,h){if(!o)return;if(o.width>w||o.height>h){if(o.width/w>o.height/h){o.height=w/o.width*o.height;o.width=w;}else{o.width=h/o.height*o.width;o.height=h;};};}
/*å›¾ç‰‡è½½å…¥å‡½æ•° å››ä¸ªå‚æ•°åˆ†åˆ«ä¸ºï¼šç›®æ ‡å›¾ç‰‡ å›¾ç‰‡çš„ç½‘å€ å›¾ç‰‡çš„ç›®æ ‡å®½åº¦ ç›®æ ‡é«˜åº¦*/
function loadImage(image,url,w,h)
{
	var obj=image;if(typeof(obj)=='string')obj=document.getElementById(image);if(!obj){return;};
	obj.src='images/blank.gif?'+Math.random();obj.style.background='url("images/loadMovie.gif") center no-repeat';obj.style.padding='0';
	var img= new Image();img.src=url+'?'+Math.random();if (navigator.appName.toLowerCase().indexOf("netscape") == -1){img.onreadystatechange = function (){if (img.readyState == "complete"){loaded();};};}else{img.onload = function (){if (img.complete == true){loaded();};};};
	function loaded(){obj.style.background='none';obj.src=img.src;obj.width=img.width;obj.height=img.height;img=null;zoomPic(obj,w,h,true);}
};
/*å›¾ç‰‡ä¸Šä¼ é¢„è§ˆ å‚æ•°ï¼šupload ä¸Šä¼ æ–‡ä»¶çš„inputåç§°æˆ–æ˜¯å¯¹åƒï¼› viewer é¢„è§ˆå›¾çŽ°å‡ºçŽ°çš„çª—å£ï¼›w å›¾ç‰‡æ˜¾ç¤ºçš„å®½åº¦ï¼›h å›¾ç‰‡æ˜¾ç¤ºæ—¶çš„é«˜åº¦*/
function viewImg(upload,viewer,w,h)
{
	if(typeof(viewer)=='string')viewer=document.getElementById(viewer);
	var src=upload.value;if(src==''){viewer.style.filter='';viewer.innerHTML='è¯·é€‰æ‹©æ‚¨è¦ä¸Šä¼ çš„å›¾ç‰‡ï¼';return;};/*åˆ¤æ–­æ˜¯å¦æœ‰æ–‡ä»¶ä¸Šä¸“*/
	var imgExt = '---.jpg|.jpeg|.gif|.bmp|.png---';var ext=src.substring(src.lastIndexOf('.')).toLowerCase();if(imgExt.indexOf(ext)==-1){viewer.innerHTML='æ–‡ä»¶æ ¼å¼('+ext+')é”™è¯¯ï¼Œè¯·é€‰æ‹©æ‚¨è¦ä¸Šä¼ çš„å›¾ç‰‡ï¼<br/>å¯æŽ¥å—çš„æ–‡ä»¶ç±»åž‹ï¼š.jpgã€.jpegã€.gifã€.bmpã€.png';return;};
	function setSize(ow,oh){var p={rw:ow,rh:oh};if(ow<w&&oh<h){p.rw=ow;p.rh=oh;return p;};if(ow/oh>w/h){p.rh=oh*w/ow;p.rw=w;}else{p.rw=ow*h/oh;p.rh=h;};return p;};		
	function displayImg(imgSrc){viewer.innerHTML='<img src="'+imgSrc+'" alt="" />';viewer.getElementsByTagName('img')[0].onload=function(){var wh=setSize(this.width,this.height);this.width=wh.rw;this.height=wh.rh;};};
	var WNUA=window.navigator.userAgent;
	if(WNUA.indexOf("MSIE 9")>=1){src=src.replace('\\','/');while(src.indexOf('//')>-1)src=src.replace('//','/');if(src.toLowerCase().indexOf('c:/fakepath/')>-1){viewer.innerHTML='æ‚¨æ‰€ä½¿ç”¨çš„æµè§ˆå™¨ä¸º MSIE 9 ï¼Œæ­¤ç‰ˆæœ¬æµè§ˆå™¨éœ€è¦è¿›è¡Œç®€å•è®¾ç½®ï¼Œä¾æ¬¡æ‰“å¼€ï¼šèœå•æ -å·¥å…·-interneté€‰é¡¹-å®‰å…¨-è‡ªå®šä¹‰çº§åˆ«ï¼Œåœ¨æ‰“å¼€çš„å¯¹è¯çª—å£ä¸­ï¼Œæ‰¾åˆ°â€œå°†æ–‡ä»¶ä¸Šè½½åˆ°æœåŠ¡å™¨æ—¶åŒ…å«æœ¬åœ°ç›®å½•è·¯å¾„â€å¹¶å°†å…¶è®¾ç½®ä¸ºâ€œå¯ç”¨â€ã€‚';}else{displayImg(upload.value);};return;};
	if(WNUA.indexOf("MSIE 6")>=1){displayImg(upload.value);return;};
	if (WNUA.indexOf("MSIE")>=1){upload.select();src=document.selection.createRange().text;viewer.innerHTML='<div></div><img src="'+src+'" alt="" />';var pic=viewer.getElementsByTagName('img')[0];pic.style.cssText='width:auto;height:auto;z-index:-1;margin:0;';pic.style.filter='progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image)';pic.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src =src;var wh=setSize(pic.offsetWidth,pic.offsetHeight);pic.style.display='none';var div=viewer.getElementsByTagName('div')[0];div.style.cssText='width:'+wh.rw+'px;height:'+wh.rh+'px;';div.style.filter='progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)';div.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src =src;upload.blur();return;};
	if(HTML5){var files=upload.files;for(var i=0;i<files.length;i++){var file = files[i];var imageType = /image.*/;if (!file.type.match(imageType)){viewer.innerHTML='è¯·é€‰æ‹©æ‚¨è¦ä¸Šä¼ çš„å›¾ç‰‡ï¼';continue;};var reader = new FileReader();reader.onload = function(e){displayImg(e.target.result);};reader.readAsDataURL(file);};return;};
	if(upload.files){try{displayImg(upload.files.item(0).getAsDataURL());return;}catch(e){};}
	viewer.innerHTML='æ‚¨æ‰€ä½¿ç”¨çš„æµè§ˆå™¨ä¸æ”¯æŒå›¾ç‰‡é¢„è§ˆåŠŸèƒ½ï¼Œå»ºè®®æ‚¨ä½¿ç”¨ IEã€Firefoxï¼ˆç«ç‹ï¼‰æˆ–æ˜¯ Chrome(è°·æ­Œ) æµè§ˆå™¨ï¼';
	return;
};
/*Ajaxæ¡†æž¶ï¼Œæœ€ç»ˆè¿”å›žçš„æ˜¯Ajaxå¯¹åƒ [ æ³¨æ„ï¼šå¹¶éžAjaxçš„ç»“æžœ ]*/
var ajaxReq=function(url,method,send){var xmlhttp;if(!method||method.toUpperCase()!='POST')method='GET';if(url.indexOf('?')>-1)url+='&'+Math.random();else url+='?'+Math.random();if(window.ActiveXObject){try{xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');}catch(e){try{xmlhttp= new ActiveXObject('Microsoft.XMLHTTP');}catch(e){}}}else if(window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();if(xmlhttp.overrideMimeType) {xmlhttp.overrideMimeType('text/xml');};};xmlhttp.open(method,url,true);if(method=='POST'){xmlhttp.setRequestHeader('Content-length',send.length);xmlhttp.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}else xmlhttp.setRequestHeader('Content-Type','text/xml;charset=utf-8');xmlhttp.send(send);return xmlhttp;};
/*å…¼å®¹æµè§ˆå™¨çš„èŽ·å–XMLèŠ‚ç‚¹å†…å®¹çš„æ–¹æ³•*/
function xmlTxt(obj){if(obj.text&&obj.text.toLowerCase()!='undefined')return obj.text;else return obj.textContent;};
/*è¾“å‡ºflasn mode åˆå§‹ä¸ºç©ºï¼Œå¸¸ç”¨çš„æœ‰ transparent å’Œopaque*/
function flash(src,width,height,mode){if(!HTML5){if(!mode)mode='null';mode=mode.toLowerCase();document.write('<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=9,0,28,0" width="'+width+'" height="'+height+'">');document.write('<param name="movie" value="'+src+'" />');document.write('<param name="quality" value="high" />');if(mode=='transparent'||mode=='opaque')document.write('<param name="wmode" value="'+mode+'" />');document.write('<embed src="'+src+'" quality="high" '+((mode=='transparent'||mode=='opaque')?'wmode="'+mode+'" ':'')+' pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="'+width+'" height="'+height+'"></embed>');document.write('</object>');}else{document.write('<embed src="'+src+'" width="'+width+'" height="'+height+'" type="application/x-shockwave-flash"></embed>');};};
/*ç®€æ˜“æ­£åˆ™åˆ¤æ–­*/
function simReg(ov,type,cto)/*cto:compare to object*/
{
	if(type=='')return true;/*å¦‚æžœéœ€è¦éªŒè¯çš„æ ¼å¼ä¸ºç©ºï¼Œè¯´æ˜Žä¸éœ€è¦éªŒè¯*/if(typeof(ov)=='object')ov=ov.value;var re,mi,ma;
	if(type.indexOf(',')>0){var ar=type.split(',');type=ar[0];mi=ar[1];if(ar[2])ma=ar[2];};//å¦‚æžœå­—ç¬¦ä¸²ä¸­å¸¦æœ‰é€—å·ï¼Œè¯´æ˜Žé™„å¸¦æœ‰å…¶ä»–æ¡ä»¶
	function reNum(){if(!re.test(ov))return false;var isl=true;if(mi&&!isNaN(mi))isl=isl&&(mi-ov<=0);if(ma&&!isNaN(ma))isl=isl&&(ma-ov>=0);return isl;};/*å¯¹æ•°å­—çš„å¤§å°è¿›è¡Œåˆ¤æ–­*/
	function ia(){if(!mi&&!ma)return '+';if(!mi)mi='NaN';if(!ma)ma='NaN';if(!isNaN(mi)){if(!isNaN(ma))return '{'+mi+','+ma+'}';else return '{'+mi+',}';}else{if(!isNaN(ma))return '{1,'+ma+'}';else return '+';};};/*å¯¹æœ‰é•¿åº¦è¦æ±‚çš„æ­£åˆ™åŠ ä»¥ä¿®é¥°*/
	switch(type.toLowerCase().replace(/ /,''))
	{
		case 'email':re=/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;break;/*é‚®ç®±éªŒè¯*/
		case 'tel':case 'fax':case 'telephone':re=/^0(([12]\d)|([3-9]\d{2}))\-?[1-9]\d{6,7}(\-\d{1,6})?$/;break;/*åº§æœºç”µè¯ éœ€è¦å¸¦åŒºå·*/
		case 'mobile':re=/^1[358]\d{9}$/;break;/*æ‰‹æœºå·ç */
		case 'url':re=/^(http(s)?:\/\/)?([\w-]+\.)+[\w-]+(\/[\w- \.\/\?\%\&\=]*)?$/;break;
		case 'date':re=/^\d{1,4}\-\d{1,2}\-\d{1,2}$/;if(re.test(ov)){var v=ov.split('-');var d=new Date(v[0],v[1]-1,v[2]);return ((v[0]-0)+'-'+(v[1]-0)+'-'+(v[2]-0)==d.getFullYear()+'-'+(d.getMonth()+1)+'-'+d.getDate());}else{return false;};break;/*éªŒè¯æ—¥æœŸï¼Œæ ¼å¼ï¼šyyyy-mm-dd*/
		case 'zip':re=/^\d{6}$/;break;/*é‚®ç¼–*/
		case 'cntxt':re=eval('/^[\u4e00-\u9fa5]'+ia()+'$/');break;/*éªŒè¯ä¸­æ–‡*/
		case 'entxt':re=eval('/^[a-zA-Z]'+ia()+'$/');break;/*éªŒè¯è‹±æ–‡å­—ç¬¦ä¸²*/
		case 'en-num':re=eval('/^[a-zA-Z0-9]'+ia()+'$/');break;/*è‹±æ–‡æ•°å­—æ··åˆ*/
		case 'num': re = /^\d+$/; return reNum(); break; /*éªŒè¯æ•°å­—*/
		default:return false;break;
	};
	return re.test(ov);
};
/*
å•ä¸ªå…ƒç´ éªŒè¯
ç¤ºä¾‹ï¼š<input type="text" id="test-1" checktype="cntxt,1,5|num,100,2000" required="1" noteTxt="éªŒè¯å‡ºé”™æ—¶æ˜¾ç¤ºçš„æ–‡å­—ï¼" noteId="æ˜¾ç¤ºéªŒè¯å‡ºé”™å…ƒç´ çš„IDå·ï¼ˆéœ€è¦æ”¯æŒinnerHTMLï¼‰" noteColor="æ–‡å­—çš„é¢œè‰²" />
checktype:éªŒè¯çš„ç±»åž‹,å¤šç§ç±»åž‹æˆ–è¿ç®—ç”¨|åˆ†éš”ï¼Œå¤šç§ç±»åž‹ç”¨å’Œè¿ç®—ç”¨&åˆ†éš”ï¼Œç¤ºä¾‹ä¸­ä¸ºä¸¤ç§æ¡ä»¶çš„æˆ–è¿ç®—ï¼›cntxt,1,5 åˆ†åˆ«ä¸º éªŒè¯ç±»åž‹ æœ€å°é•¿åº¦ æœ€å¤§é•¿åº¦ï¼›è‹¥éªŒè¯çš„ä¸ºæ•°å­—ï¼šåˆ™åŽé¢çš„1 5åˆ†åˆ«ä¸ºæœ€å°å€¼ä¸Žæœ€å¤§å€¼
requiredï¼šå€¼ä¸ºï¼š1æˆ–æ˜¯0ï¼ˆä¸ºå…¼å®¹HTML5ï¼Œå¦‚æžœä¸æ˜¯å¿…å¡«é¡¹ï¼Œå»ºè®®ä¸è®¾ç½®æ­¤é¡¹ï¼‰;æ˜¯å¦å¿…å¡«ï¼Œå¦‚æžœä¸æ˜¯å¿…å¡«ï¼Œåˆ™ä¸è¾“å…¥å¯¹åº”æ¡ä»¶æ—¶å°±ä¹Ÿè¿”å›žçœŸï¼Œä½†æ˜¯å¦‚æžœè¾“å…¥äº†ï¼Œå°±ä¼šæŒ‰ç…§checktypeçš„æ¡ä»¶è¿›è¡ŒéªŒè¯ï¼›
noteTxt :éªŒè¯å‡ºé”™æ—¶æ˜¾ç¤ºçš„æ–‡å­—
noteId	:éªŒè¯å‡ºé”™æ—¶æ˜¾ç¤ºæ–‡å­—çš„å…ƒç´ ï¼Œå¦‚æžœä¸è®¾ç½®åˆ™ä»¥alertæç¤ºæ¡†æ–¹å¼å¼¹å‡º
noteColor:éªŒè¯å‡ºé”™æ—¶æ˜¾ç¤ºæ–‡å­—çš„é¢œè‰²
*/
function alanCheckFormItem(obj) { obj = $(obj); var is = false; var NTID = obj.getAttribute('noteId'); if (NTID) NTID = $(NTID); /*èŽ·å–æ˜¾ç¤ºé”™è¯¯æç¤ºçš„å…ƒç´ */var NT = obj.getAttribute('noteTxt'); /*éªŒè¯å¤±è´¥çš„æç¤º*/function showErr() { if (NTID) { var NTC = obj.getAttribute('noteColor'); if (!NTC) NTC = 'red'; /*æç¤ºçš„é¢œè‰²ï¼Œå¦‚æžœæ²¡æœ‰è®¾ç½®ï¼Œåˆ™æ˜¾ç¤ºä¸ºçº¢è‰²*/NTID.innerHTML = '<err style="color:' + NTC + ';">' + NT + '</err>'; /*è¿™é‡Œçš„Erræ ‡ç­¾åœ¨å‰é¢å·²ç»å®šä¹‰è¿‡äº†ï¼Œæ²¡æœ‰å®šä¹‰çš„è¦å…ˆå®šä¹‰ä¸€ä¸‹*/ } else { alert(NT); }; return false; }; function clearErr() { if (NTID) NTID.innerHTML = ''; return true; }; if (obj.value == '') { if (!(obj.getAttribute('required') - 0)) { return clearErr(); } else { return showErr(); }; }; /*å¦‚æžœå¯ä»¥ä¸ºç©ºï¼Œåˆ™å¦‚æžœå€¼ä¸ºç©ºæ—¶ä¹Ÿè¿”å›žæ­£ç¡®*/var checkType = obj.getAttribute('checkType'); /*éœ€è¦éªŒè¯çš„ç±»åž‹*/if (!checkType) return true; /*ä¸éœ€è¦éªŒè¯çš„å°±ä¸éªŒè¯*/if (checkType == 'same') { var cto = $(obj.getAttribute('compare'));if (!cto)return true;if (obj.value == cto.value)return clearErr(); else return showErr();}; /*å¦‚æžœæ˜¯å¯¹æ¯”æ“ä½œ*/if (checkType.indexOf('&') > 0) { while (checkType.indexOf('&&') > 0) checkType = checkType.replace('&&', '&'); var tps = checkType.split('&'); is = true; for (var i = 0; i < tps.length; i++) { is = is && simReg(obj, tps[i]); if (!is) break; }; } else if (checkType.indexOf('|') > 0) { while (checkType.indexOf('||') > 0) checkType = checkType.replace('||', '|'); var tps = checkType.split('|'); is = false; for (var i = 0; i < tps.length; i++) { is = is || simReg(obj, tps[i]); if (is) break; }; } else { is = simReg(obj, checkType); }; if (is) return clearErr(); /*å¦‚æžœæ­£ç¡®ï¼Œåˆ™æ¸…ç©ºé”™è¯¯æç¤º*/return showErr(); };
/*è¡¨å•éªŒè¯*/
function alanCheckForm(formObj){var obj=$(formObj);if(!obj)return false;for(var i=0;i<obj.length;i++)if(!alanCheckFormItem(obj[i]))return false;return true;};


/*ä»¥ä¸‹å†…å®¹ä¸“ä¸ºæœ¬ç½‘ç«™æœåŠ¡*/
function pushSelect(obj,xml,v,t){obj=$(obj);obj.length=0;for(var i=0;i<xml.length;i++){obj.options[i]=new Option(xml[i].getAttribute(t),xml[i].getAttribute(v));if(xml[i].getAttribute('selected')=='1'||xml[i].getAttribute('selected')=='true')obj.options[i].selected=true;};};

/*æ ¹æ®XMLèŽ·å–options*/
function getOptByXML(obj,url,ft,fv)
{
	if(typeof(obj)=='string')obj=obj.split(',');else obj=new Array(obj);
	if(!ft)ft='txt';
	if(!fv)fv='value';
	for(var i=0;i<obj.length;i++)$(obj[i]).disabled=true;
	var thisAjax=new ajaxReq(url);
	thisAjax.onreadystatechange=function()
	{
		if(thisAjax.readyState==4)
		{
			for(var i=0;i<obj.length;i++)$(obj[i]).disabled=false;
			if(thisAjax.status==200)
			{
				var xml=$$(thisAjax.responseXML,'option');
				for(var j=0;j<obj.length;j++)
				{
					obj[j]=$(obj[j]);
					if(obj[j].tagName.toLowerCase()!='select')continue;
					pushSelect(obj[j],xml,fv,ft);
				};
			};
		};
	};
};

//ç¦æ­¢åœ¨äº§å“å›¾ç‰‡ä¸Šå³é”®
function setForbidden(obj){if(!obj)return;var imgs=$$(obj,'img');for(var i=0;i<imgs.length;i++)imgs[i].oncontextmenu=function(){return false;};};

/*è®¾ç½®å¤šçº§è”åŠ¨çš„select*/
function setSelects(o)
{
	var ss=$$(o,'select');
	var tx=$$(o,'input');
	if(ss.length==0||tx.length==0)return;
	tx=tx[0];o=$(o);
	
	function setAjax(obj)
	{
		var n=Math.round(obj.getAttribute('level')/1+1);
		while(ss[n])o.removeChild(ss[n]);
		tx.value=obj.value;
		if(obj.selectedIndex==0)return;
		var url='/xml/getFAVLevel.ashx?id='+obj.value;
		var ajax=new ajaxReq(url);
		ajax.onreadystatechange=function()
		{
			if(ajax.readyState==4)
			{
				if(ajax.status==200)
				{
					var xml=ajax.responseXML;xml=$$(xml,'option');if(xml.length==0)return;
					var ns=document.createElement('select');o.appendChild(ns);pushSelect(ns,xml,'value','txt');ns.setAttribute('level',n);ns.onchange=function(){setAjax(this);};
				}
				else
				{
					alert('ç½‘ç»œæˆ–æ˜¯ç¨‹åºé”™è¯¯ï¼ˆé”™è¯¯ç ï¼š'+ajax.status+'ï¼‰ï¼');
				};
			};
		};
	};
	
	for(var i=0;i<ss.length;i++){ss[i].setAttribute('level',i);ss[i].onchange=function(){setAjax(this);};};
};

var pubAjax;

function setEffect(o,h)
{
	var interval,addflag=1,mover;
	o=$(o);
	if(!h)h=o.offsetHeight-2;
	var divs=$$(o,'div');if(!divs)return;var tem;for(var i=0;i<divs.length;i++)if(divs[i].className.indexOf('effect')>-1){tem=divs[i];break;};if(!tem)return;
	o.onmouseover=function(){clearInterval(interval);mover=tem;addflag=3;mover.style.display='block';interval=setInterval(move,5);};
	o.onmouseout=function(){if(!mover)return;clearInterval(interval);addflag=-3;mover.style.display='block';interval=setInterval(move,5);};
	function move(){if(addflag>0){if(mover.offsetHeight<mover.scrollHeight-addflag){mover.style.height=mover.offsetHeight+addflag+'px';mover.style.marginTop=h-mover.offsetHeight+'px';} else {mover.style.height=mover.scrollHeight+'px';mover.style.marginTop=h-mover.offsetHeight+'px';clearInterval(interval);};} else {if(mover.offsetHeight>0-addflag){mover.style.height=mover.offsetHeight+addflag+'px';mover.style.marginTop=h-mover.offsetHeight+'px';} else {mover.style.height='0px';mover.style.marginTop=h+'px';mover.style.display='none';clearInterval(interval);};};};
}

function setListEffect(o)
{
	o=$(o);
	if(!o)return;
	var lis=$$(o,'li');
	for(var i=0;i<lis.length;i++)setEffect(lis[i]);
};

//æ¨¡æ‹Ÿselectï¼ŒåŽŸç›®æ ‡éœ€è¦ä¸ºæ–‡æœ¬æ¡†
var selectObj = function (ot, vt, styleName, canRead)
{
	ot = $(ot);
	if (!ot) return;
	vt = $(vt);
	if (!vt) vt = ot; else vt.style.display = 'none';
	ot.className = styleName + 'Text';
	ot.style.cursor = 'default';

	var bv = ot.value;
	if (!canRead)
	{
		ot.readOnly = true;
	}
	else
	{
		ot.onblur = function () { if (ot.value == bv) return; vt.value = this.value; };
	};

	var pn = ot;
	while (pn.tagName.toLowerCase() != 'div') pn = pn.parentNode;
	var ul = $CE(document.body, 'div');
	ul.className = styleName;
	ul.style.cssText = 'position:absolute;z-index:10000;width:' + (ot.offsetWidth - 2) + 'px;height:150px;top:0px;left:0px;overflow:auto;display:none;';

	var ci = null;
	function closeMenu() { ot.className = styleName + 'Text'; if (ci) clearTimeout(ci); ci = setTimeout(function () { ul.style.display = 'none'; if (ci) clearTimeout(ci); }, 100); };
	function appMenu() { ot.className = styleName + 'TextHover'; if (ci) clearTimeout(ci); ul.style.display = 'block'; var x = 0, y = 0; (function () { var _o = ot; y += _o.offsetHeight - 1; while (_o) { x += _o.offsetLeft; y += _o.offsetTop; _o = _o.offsetParent; }; } ()); ul.style.top = y + 'px'; ul.style.left = x + 'px'; if ($$(ul, 'ul')[0].scrollHeight < ul.offsetHeight) ul.style.height = $$(ul, 'ul')[0].scrollHeight + 'px'; };
	ul.onmouseover = appMenu;
	ul.onmouseout = closeMenu;

	bindEvent(ot, 'mouseover', function () { ot.className = styleName + 'TextHover'; if (ul.style.display == 'block') appMenu(); }, true);
	bindEvent(ot, 'mouseout', function () { closeMenu(); }, true);
	bindEvent(ot, 'click', function () { if (!canRead) ot.blur(); appMenu(); }, true);

	this.bindValues = function bindValues(vs, bindFunc)
	{
		if (vs == '') return;
		var lis = $CE(ul, 'ul');
		v = vs.split('\n');
		for (var i = 0; i < v.length; i++)
		{
			if (!v[i]) continue;
			var li = $CE(lis, 'li');
			var a = $CE(li, 'a');
			var av = v[i].split('\t');
			a.innerHTML = av[0];
			//a.setAttribute('num',i);
			if (av.length > 1)
			{
				a.setAttribute('value', av[1]);
				if (av.length > 2) a.setAttribute('fun', av[2]);
			}
			else
			{
				a.setAttribute('value', av[0]);
			};
			a.href = '#' + (i + 1);
			a.onclick = function ()
			{
				ot.value = this.innerHTML;
				vt.value = this.getAttribute('value');
				if (this.getAttribute('fun')) eval(this.getAttribute('fun'));
				if (bindFunc) eval(bindFunc);
				closeMenu();
				return false;
			};
		};
		if (v.length > 0)
		{
			var av = v[0].split('\t');
			ot.value = av[0];
			vt.value = av[1];
		};
	};
};
function setOrderMail(v)
{
	v=v.replace(/ /,'');
	if(v=='')
	{
		alert('è¯·è¾“å…¥æ‚¨çš„é‚®ä»¶åœ°å€ï¼');
		return;
	}
	var re=/^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
	if(!re.test(v))
	{
		alert('æ‚¨è¾“å…¥çš„é‚®ä»¶åœ°å€é”™è¯¯ï¼Œè¯·é‡æ–°è¾“å…¥ï¼');
		return;
	}
	
	var a=new ajaxReq('xml/setOrderMail.ashx?v='+escape(v));
	a.onreadystatechange=function()
	{
		if(a.readyState==4){
			if(a.status==200){
				var xml=a.responseText;
				alert(xml);
			};
		};
	};
}