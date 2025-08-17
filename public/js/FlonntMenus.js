var AID="";var SID="";var DID="";var FID="";var GID="";
var aidC="";var sidC="";var didC="";var fidC="";var gidC="";
function Tikspan(event){
	 $("#"+event.getAttribute("value")).slideToggle("slow");
	 $("#"+event.getAttribute("value")).toggleClass("active");
	if(event.getAttribute("name")=='hide'){
		event.setAttribute("name",'show');
	    $(event).attr("style","float:right;width:20px;-moz-transform:rotate(0deg);-webkit-transform: rotate(0deg);-o-transform:rotate(0deg);-ms-transform: rotate(0deg);transform: rotate(0deg); ");
	}else{
	    event.setAttribute("name",'hide');
	    $(event).attr("style","float:right;width:20px;-moz-transform:rotate(45deg);-webkit-transform: rotate(45deg);-o-transform:rotate(45deg);-ms-transform: rotate(45deg);transform: rotate(45deg); ");
	}
} 
window.onscroll = function (e) {
	var divs=document.getElementById("menseR");
	if(window.scrollY>=160){
		divs.style.position="fixed";divs.style.setProperty('margin-top','-160px');
	}else{divs.style.position="";divs.style.setProperty('margin-top','0px');}
};
function colorlistimg(event){
    $("."+event.getAttribute("id")).attr("src",event.getAttribute("value"));
}
function MenuSOnclick(event){
	var sr=document.getElementById("fenlei").getAttribute("value");
	if(event.getAttribute("id")=="aid"){
		if(aidC==""||aidC==null){
			event.style.color="RGB(252,3,3)";aidC=event.getAttribute("id")+event.getAttribute("value"); AID=event.getAttribute("value");
		}else{
			if(aidC==event.getAttribute("id")+event.getAttribute("value")){
				event.style.color="RGB(255,255,255)";AID="";aidC="";
			}else{
				document.getElementsByClassName(aidC)[0].style.color="RGB(255,255,255)";event.style.color="RGB(252,3,3)";
				AID=event.getAttribute("value");aidC=event.getAttribute("id")+event.getAttribute("value");
			}
		} 
		if(sidC!=""){document.getElementsByClassName(sidC)[0].style.color="RGB(255,255,255)";}
		if(didC!=""){document.getElementsByClassName(didC)[0].style.color="RGB(255,255,255)";} 
		if(fidC!=""){document.getElementsByClassName(fidC)[0].style.color="RGB(255,255,255)";}
		if(gidC!=""){document.getElementsByClassName(gidC)[0].style.color="RGB(255,255,255)";}
		ShowMenu(sr,AID,"","","","");
	}
	if(event.getAttribute("id")=="sid"){
		if(sidC==""||sidC==null){
			event.style.color="RGB(252,3,3)";sidC=event.getAttribute("id")+event.getAttribute("value");SID=event.getAttribute("value");
		}else{
			if(sidC==event.getAttribute("id")+event.getAttribute("value")){
				event.style.color="RGB(255,255,255)";SID="";sidC="";
			}else{
				document.getElementsByClassName(sidC)[0].style.color="RGB(255,255,255)";event.style.color="RGB(252,3,3)";
				SID=event.getAttribute("value");sidC=event.getAttribute("id")+event.getAttribute("value");
			}
		}
		if(aidC!=""){document.getElementsByClassName(aidC)[0].style.color="RGB(255,255,255)";}
		if(didC!=""){document.getElementsByClassName(didC)[0].style.color="RGB(255,255,255)";}
		if(fidC!=""){document.getElementsByClassName(fidC)[0].style.color="RGB(255,255,255)";}
		if(gidC!=""){document.getElementsByClassName(gidC)[0].style.color="RGB(255,255,255)";}
		ShowMenu(sr,"",SID,"","","");
	}
	if(event.getAttribute("id")=="did"){
		if(didC==""||didC==null){
			event.style.color="RGB(252,3,3)";didC=event.getAttribute("id")+event.getAttribute("value");DID=event.getAttribute("value");
		}else{
			if(didC==event.getAttribute("id")+event.getAttribute("value")){
				event.style.color="RGB(255,255,255)";DID="";didC="";
			}else{
				document.getElementsByClassName(didC)[0].style.color="RGB(255,255,255)";event.style.color="RGB(252,3,3)";
				DID=event.getAttribute("value");didC=event.getAttribute("id")+event.getAttribute("value");
			}
		}
		if(sidC!=""){document.getElementsByClassName(sidC)[0].style.color="RGB(255,255,255)";}
		if(aidC!=""){document.getElementsByClassName(aidC)[0].style.color="RGB(255,255,255)";}                                       
		if(fidC!=""){document.getElementsByClassName(fidC)[0].style.color="RGB(255,255,255)"}
		if(gidC!=""){document.getElementsByClassName(gidC)[0].style.color="RGB(255,255,255)";}
		ShowMenu(sr,"","",DID,"","");
	}
	if(event.getAttribute("id")=="fid"){
		if(fidC==""||fidC==null){
			event.style.color="RGB(252,3,3)";fidC=event.getAttribute("id")+event.getAttribute("value");FID=event.getAttribute("value");
		}else{
			if(fidC==event.getAttribute("id")+event.getAttribute("value")){
				event.style.color="RGB(255,255,255)";FID="";fidC="";
			}else{
				document.getElementsByClassName(fidC)[0].style.color="RGB(255,255,255)";event.style.color="RGB(252,3,3)";
				FID=event.getAttribute("value");fidC=event.getAttribute("id")+event.getAttribute("value");
			}
		}
		if(sidC!=""){document.getElementsByClassName(sidC)[0].style.color="RGB(255,255,255)";}
		if(didC!=""){document.getElementsByClassName(didC)[0].style.color="RGB(255,255,255)";}
		if(aidC!=""){document.getElementsByClassName(aidC)[0].style.color="RGB(255,255,255)";}
		if(gidC!=""){document.getElementsByClassName(gidC)[0].style.color="RGB(255,255,255)";}
		ShowMenu(sr,"","","",FID,"");
	}
	if(event.getAttribute("id")=="gid"){
		if(gidC==""||gidC==null){
			event.style.color="RGB(252,3,3)";gidC=event.getAttribute("id")+event.getAttribute("value");GID=event.getAttribute("value");
		}else{
			if(gidC==event.getAttribute("id")+event.getAttribute("value")){
				event.style.color="RGB(255,255,255)";GID="";gidC="";
			}else{
				document.getElementsByClassName(gidC)[0].style.color="RGB(255,255,255)";event.style.color="RGB(252,3,3)";
				GID=event.getAttribute("value");gidC=event.getAttribute("id")+event.getAttribute("value");
			}
		}
		if(sidC!=""){document.getElementsByClassName(sidC)[0].style.color="RGB(255,255,255)";}
		if(didC!=""){document.getElementsByClassName(didC)[0].style.color="RGB(255,255,255)";}
		if(aidC!=""){document.getElementsByClassName(aidC)[0].style.color="RGB(255,255,255)";}
		if(fidC!=""){document.getElementsByClassName(fidC)[0].style.color="RGB(255,255,255)";}
		ShowMenu(sr,"","","","",GID);
	}
}

function showSite(str){
	if (window.XMLHttpRequest){xmlhttp=new XMLHttpRequest();}else{ xmlhttp=new ActiveXObject("Microsoft.XMLHTTP"); }
    xmlhttp.onreadystatechange=function(){if (xmlhttp.readyState==4 && xmlhttp.status==200){document.getElementById("menseR").innerHTML=xmlhttp.responseText; }}
    xmlhttp.open("GET","TextAjaxA.php?pid="+str,true); xmlhttp.send();
}
function ShowMenu(str,aid,sid,did,fid,gid){
	 if (window.XMLHttpRequest){ xmlhttpS=new XMLHttpRequest(); }else{ xmlhttpS=new ActiveXObject("Microsoft.XMLHTTP");}
	 xmlhttpS.onreadystatechange=function() {
	        if (xmlhttpS.readyState==4 && xmlhttpS.status==200){
		        if(xmlhttpS.responseText==null||xmlhttpS.responseText==""){
		        	 document.getElementById("itmesvalue").innerHTML="<img src='conn/4041.jpg' style='margin:0px auto;'>";
			     }else{
			  	   refreshJS(); 
			       document.getElementById("itmesvalue").innerHTML=xmlhttpS.responseText;
				 } 
	        }                                                                     
	    }
	    if(aid!=0){aid="&aid="+aid;}else{aid="";}
	    if(sid!=0){sid="&sid="+sid;}else{sid="";} 
	    if(did!=0){did="&did="+did;}else{did="";}
	    if(fid!=0){fid="&fid="+fid;}else{fid="";}
	    if(gid!=0){gid="&gid="+gid;}else{gid="";}
	    xmlhttpS.open("GET","TextAjaxB.php?pid="+str+aid+sid+did+fid+gid,true);
	    xmlhttpS.send();
	 //   refreshJS();
}
var sr=document.getElementById("fenlei").getAttribute("value");
showSite(sr);
var kc=0;
function refreshJS() {
	 var script = document.getElementById('jQuery');
	 var newScript = document.createElement('script');
        if(script.src!=null||script.src!=""){
        	 kc=kc+1; newScript.src =script.src+'?'+kc;newScript.id="jQuery";document.body.removeChild(script);document.body.appendChild(newScript);
        }
}