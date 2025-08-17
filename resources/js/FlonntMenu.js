//ç”¨iåˆ¤æ–­æ˜¯å¦è¦å‹¾é€‰
	var i = 0 ;
	//å½“é€‰ä¸­ä¸ªæ•°å¤§äºŽç­‰äºŽbtn_thresholdçš„å€¼çš„æ¯”è¾ƒåˆ—è¡¨æ˜¾ç¤º
	var btn_threshold = 1;
	//é‡æ–°åŠ è½½ä¹‹åŽè¦åˆå§‹åŒ–ä¹‹å‰é€‰çš„æ˜¯å¦ç›¸åŒ
	$(function(){
		var count = $(".is_check.background_red").length;
		$(".is_check.background_red").next().children().css('display','block');
		$(".is_check.background_red").css('display','block');
		i = count;
		if(i < btn_threshold){
			$(".btn_compare").css('display','none');
			//$(".btn_compare .btn_bc").css('display','none');
		}else{
			$(".btn_compare").css('display','block');
			//$(".btn_compare .btn_bc").css('display','block');
			$(".btn_compare .btn_show").text("Compare products("+i+")");
		}
		if(i>=4){
				//è¶…è¿‡2ä¸ªä¹‹åŽç¦æ­¢é€‰å–ï¼Œä¸€å®šè¦åœ¨æœ€åŽä¸€ä¸ªå‹¾é€‰ä¹‹åŽå°±è¦ç¦æ­¢å‹¾é€‰ï¼Œä¸ç„¶ä¼šå¤šå‡ºä¸€ä¸ª
				$(".is_check.background_white").css('cursor','not-allowed');
		}
				
	})
	

	//é¼ æ ‡ç§»å…¥å’Œç§»å‡ºæ•ˆæžœ
	$(".filter-type").mouseover(function(){
		//é€‰æ‹©ä¸ªæ•°å¤§äºŽ4ç§»å…¥é¼ æ ‡çš„æ—¶å€™æ˜¾ç¤ºæ–‡å­—
		if(i>=4){
			
			if($(this).children().eq(1).hasClass('background_white')){
				$(this).children().eq(1).css('display','block');
				$(this).children().eq(2).children().val("Max (4) products");				
				$(this).children().eq(2).children().css('display','block');
				
			}
		}else{
			//å°äºŽ4æ˜¾ç¤ºåŠ å…¥æ¯”è¾ƒ
			$(this).children().eq(1).css('display','block');
			if($(this).children().eq(2).children().hasClass('dis_check')){
				$(this).children().eq(2).children().val("Compare");
				$(this).children().eq(1).css('display','block');
			}
			$(this).children().eq(2).children().css('display','block');
		}
	}).mouseout(function(){
		//å¦‚æžœæ²¡æœ‰è¢«é€‰ä¸­åˆ™éšè—æ–‡å­—å’Œé€‰æ‹©æ¡†
		if($(this).children().eq(1).hasClass('background_white')){
			$(this).children().eq(2).children().css('display','none');
			$(this).children().eq(1).css('display','none');
		}
	});
	var ids = new Array();
	//é¡µé¢è·³è½¬	
	$(".btn_show").click(function(){
		if(ids.length >=2){						
			var sr=document.getElementById("fenlei").getAttribute("value");
			window.location.href="compare.php?ids="+ids+"&pid="+sr;
		}
	});
	//ç‚¹å‡»åŠ å…¥åˆ—è¡¨å›¾
	function showcomparelist(){		
		var imgid = ids.join(",");					
		$.ajax({
			url:"specparam.php",
			data:{"imgid":imgid},
			type:"GET",
			traditional:true,
			success:function(msg){
				$(".btn_imgs").empty();
				var str="";
				var specName = msg.trim().split(",");
				for(var i=0; i < specName.length-1; i++){
					var id_value = specName[i].trim().split(":");
					str += '<div class="btn_liebiao">'
				   	str += '<img src='+id_value[0]+' />';
					str += '<input type="hidden" value="'+id_value[1]+'" />'				
					str += '<div class="removeimg" onclick="canelimg('+id_value[1]+')">X</div>'
					str += '</div>'
				}
				var $li = $(str);
				$(".btn_imgs").append($li);
			},	
		});
	}
	//ç‚¹å‡»Xå–æ¶ˆäº§å“æ¯”è¾ƒ
	function canelimg(id){			
		$("input[name='fixed"+id+"']").parent().removeClass('background_red');
		$("input[name='fixed"+id+"']").parent().addClass('background_white');
		$("input[name='fixed"+id+"']").parent().css('display','none');
		$("input[name='fixed"+id+"']").parent().next().children().css('display','none');		
		var index = ids.indexOf(""+id);
		ids.splice(index,1);
		i--;
		$(".btn_compare .btn_show").text("Compare products("+i+")");
		if(i < btn_threshold){
			$(".btn_compare").css('display','none');
		}else{
			if(i == 1){
				$(".btn_show").css('background-color','#bebbba');
				$(".btn_show").css('cursor','not-allowed');
				$(".btn_show").hover(function(){
					$(".btn_show").css('box-shadow','');
				},
				function(){
					$(".btn_show").css('box-shadow','');
				});
			}else{
				$(".btn_show").css('background-color','#ff0013');
				$(".btn_show").css('cursor','pointer');
				$(".btn_show").hover(function(){
					$(".btn_show").css('box-shadow','0 5px 10px 0 rgba(255,0,0,0.24),0 2px 2px 0 rgba(255,0,0,0.3)');
				},
				function(){
					$(".btn_show").css('box-shadow','');
				});
			}
		}
		if(i >= 4){
			$(".is_check.background_white").css('cursor','not-allowed');
		}else{
			$(".is_check.background_white").css('cursor','pointer');
		}
		showcomparelist();
	}
	//æ˜¯å¦åŠ å…¥å¯¹æ¯”
	$(".is_check").click(function(e){
		if(i < 4){
			if($(this).hasClass('background_white')){
				$(this).removeClass('background_white');
				$(this).addClass('background_red');
				$(this).css('display','block');
				$(this).next().children().css('display','block');
			//å‹¾é€‰äº†å¯¹æ¯”ä¹‹åŽæ˜¾ç¤ºå‡ºæ¥
				i++;
				ids.push($(this).children().eq(0).val());
				showcomparelist();
			}else{
				$(this).removeClass('background_red');
				$(this).addClass('background_white');	
				$(this).css('display','none');
				$(this).next().children().css('display','none');
				i--;
				var index = ids.indexOf($(this).children().eq(0).val());
				ids.splice(index,1);
				showcomparelist();
			}
			e.stopPropagation();
			e.preventDefault();
		}else{
			if($(this).hasClass('background_red')){
				$(this).removeClass('background_red');
				$(this).addClass('background_white');	
				$(this).css('display','none');
				$(this).next().children().css('display','none');
				i--;
				var index = ids.indexOf($(this).children().eq(0).val());
				ids.splice(index,1);
				showcomparelist();
				e.stopPropagation();
				e.preventDefault();
			}			
		}
		if(i >= 4){
			$(".is_check.background_white").css('cursor','not-allowed');
		}else{
			$(".is_check.background_white").css('cursor','pointer');
		}
		if(i >= btn_threshold){
			$(".btn_compare").css('display','block');			
			$(".btn_compare .btn_show").text("Compare products("+i+")");
			if(i == 1){
				$(".btn_show").css('background-color','#bebbba');
				$(".btn_show").css('cursor','not-allowed');
				$(".btn_show").hover(function(){
					$(".btn_show").css('box-shadow','');
				},
				function(){
					$(".btn_show").css('box-shadow','');
				});
			}else{
				$(".btn_show").css('background-color','#ff0013');
				$(".btn_show").css('cursor','pointer');
				$(".btn_show").hover(function(){
					$(".btn_show").css('box-shadow','0 5px 10px 0 rgba(255,0,0,0.24),0 2px 2px 0 rgba(255,0,0,0.3)');
				},
				function(){
					$(".btn_show").css('box-shadow','');
				});
			}
		}else{
			$(".btn_compare").css('display','none');
		}		
	});
	$(".filter-type").click(function(event){
		if(i >= 4){
			event.preventDefault();
		}
	})