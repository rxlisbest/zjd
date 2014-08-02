$(document).ready(function(){$("[id^='p_nav_']").click(function(){$("[id^='p_nav_']").removeClass("current"),$(this).addClass("current");});});	
$(document).ready(function(){$("[id^='s_nav_']").click(function(){$("[id^='s_nav_']").removeClass("current"),$(this).addClass("current");});});	
$(document).ready(function(){$("[id^='test']").click(function(){$([id^='p_nav_']).hide();});});	
function show_frame(){
	url = arguments[0];
	if(arguments[1]){
		path=arguments[1];
	}
	else{
		path = 0;
	}
	var xmlhttp;
	if(window.XMLHttpRequest){
		xmlhttp = new XMLHttpRequest();
	}
	else{
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function(){
		if(xmlhttp.readyState ==4 && xmlhttp.status == 200){
			document.getElementById("main-content-nofoot").innerHTML = xmlhttp.responseText;
			reload_js();
			reload_ajax_js(xmlhttp.responseText);
			if(path){
				p_path = path.substr(0,4);
				$("[id^='nav_ul_']").css("display","none");	
				$("#nav_ul_"+p_path).css("display","block");	
				$("[id^='p_nav_']").removeClass("current");	
				$("[id^='s_nav_']").removeClass("current");	
				$("#p_nav_"+p_path).addClass("current");	
				$("#s_nav_"+path).addClass("current");	
			}
			$("[id^='p_nav_']").click(function(){$("#main-info").html("")});	
			$("[id^='s_nav_']").click(function(){$("#main-info").html("")});	
		}
	}
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
}
function reload_ajax_js(ajaxLoadedData){
	var regDetectJs = /<script(.|\n)*?>(.|\n|\r\n)*?<\/script>/ig;
	var jsContained = ajaxLoadedData.match(regDetectJs);

	// 第二步：如果包含js，则一段一段的取出js再加载执行
	if(jsContained) {
		// 分段取出js正则
		var regGetJS = /<script(.|\n)*?>((.|\n|\r\n)*)?<\/script>/im;

		// 按顺序分段执行js
		var jsNums = jsContained.length;
		for (var i=0; i<jsNums; i++) {
			var jsSection = jsContained[i].match(regGetJS);

			if(jsSection[2]) {
				if(window.execScript) {
					// 给IE的特殊待遇
					window.execScript(jsSection[2]);
				} else {
					// 给其他大部分浏览器用的
					window.eval(jsSection[2]);
				}
			}
		}
	}
}
function form_post(form, url){
	var $form = $(form);
	var _submit = function(){$.ajax({
		type: 'POST',
		url: $form.attr("action"),
		data:$form.serialize(),
		success: form_bridge,
		dataType:"json",
		cache:false, 
	})};
	_submit();
	return false;
}
function url_post(url){
	var _submit = function(){$.ajax({
		type: 'POST',
		url: url, data:"",
		success: form_bridge,
		dataType:"json",
		cache:false, 
	})};
	_submit();
}
function form_bridge(json){
	var obj = eval(json);
	if(obj["info"]){
		$("#main-info").html(obj["info"]);	
	}
	if(obj["nav"]){
		$("#main-nav").html(obj["nav"]);
		//Sidebar Accordion Menu:

		$("#main-nav li ul").hide(); // Hide all sub menus
		$("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu

		$("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
				function () {
				$(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				return false;
				}
				);

		$("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
				function () {
				window.location.href=(this.href); // Just open the link instead of a sub menu
				return false;
				}
				); 

		// Sidebar Accordion Menu Hover Effect:

		$("#main-nav li .nav-top-item").hover(
				function () {
				$(this).stop().animate({ paddingRight: "25px" }, 200);
				}, 
				function () {
				$(this).stop().animate({ paddingRight: "15px" });
				}
				);
		$("[id^='p_nav_']").click(function(){$("[id^='p_nav_']").removeClass("current"),$(this).addClass("current");});
		$("[id^='s_nav_']").click(function(){$("[id^='s_nav_']").removeClass("current"),$(this).addClass("current");});
	}
	if(obj["url"]){
		show_frame(obj["url"]);
	}
}
function PreviewImage(obj, imgPreviewId, divPreviewId) {

	var allowExtention = ".jpg,.bmp,.gif,.png"; //,允许上传文件的后缀名
	var extention = obj.value.substring(obj.value.lastIndexOf(".") + 1).toLowerCase();
	var browserVersion = window.navigator.userAgent.toUpperCase();
	if (allowExtention.indexOf(extention) > -1) {
		if (browserVersion.indexOf("MSIE") > -1) {
			if (browserVersion.indexOf("MSIE 6.0") > -1) {//ie6
				document.getElementById(imgPreviewId).setAttribute("src", obj.value);
			} else {//ie[7-8]、ie9
				obj.select();
				var newPreview = document.getElementById(divPreviewId + "New");
				if (newPreview == null) {
					newPreview = document.createElement("div");
					newPreview.setAttribute("id", divPreviewId + "New");
					newPreview.style.width = 160;
					newPreview.style.height = 170;
					newPreview.style.border = "solid 1px #d2e2e2";
				}
				newPreview.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale',src='" + document.selection.createRange().text + "')";
				var tempDivPreview = document.getElementById(divPreviewId);
				tempDivPreview.parentNode.insertBefore(newPreview, tempDivPreview);
				tempDivPreview.style.display = "none";
			}
		} else if (browserVersion.indexOf("FIREFOX") > -1) {//firefox
			var firefoxVersion = parseFloat(browserVersion.toLowerCase().match(/firefox\/([\d.]+)/)[1]);
			if (firefoxVersion < 7) {//firefox7以下版本
				document.getElementById(imgPreviewId).setAttribute("src", obj.files[0].getAsDataURL());
			} else {//firefox7.0+                    
				document.getElementById(imgPreviewId).setAttribute("src", window.URL.createObjectURL(obj.files[0]));
			}
		} else if (obj.files) {
			//兼容chrome、火狐等，HTML5获取路径                   
			if (typeof FileReader !== "undefined") {
				var reader = new FileReader();
				reader.onload = function (e) {
					document.getElementById(imgPreviewId).setAttribute("src", e.target.result);
				}
				reader.readAsDataURL(obj.files[0]);
			} else if (browserVersion.indexOf("SAFARI") > -1) {
				alert("暂时不支持Safari浏览器!");
			}
		} else {
			document.getElementById(divPreviewId).setAttribute("src", obj.value);
		}
	} else {
		alert("仅支持" + allowSuffix + "为后缀名的文件!");
		obj.value = ""; //清空选中文件
		if (browserVersion.indexOf("MSIE") > -1) {
			obj.select();
			document.selection.clear();
		}
		obj.outerHTML = obj.outerHTML;
	}
}
//INPUT验证不能为空
function insertAfter( newElement, targetElement ){ // newElement是要追加的元素 targetElement 是指定元素的位置 
	var parent = targetElement.parentNode; // 找到指定元素的父节点 
	if( parent.lastChild == targetElement ){ // 判断指定元素的是否是节点中的最后一个位置 如果是的话就直接使用appendChild方法 
		parent.appendChild( newElement, targetElement ); 
	}else{ 
		parent.insertBefore( newElement, targetElement.nextSibling ); 
	}; 
}; 
function checkFormInput(form){
	var error = new Array();
	var input = form.getElementsByTagName("input");	
	for(var i=0;i<input.length;i++){
			
		if(/required/.test(input[i].className) && input[i].value.length==0){ 
			var spans = input[i].parentNode.getElementsByTagName("span");
			if(spans.length>0){
				for(var m=0;m<spans.length;m++){
					if(spans[m].className == "input-notification error png_bg"){
						spans[m].parentNode.removeChild(spans[m]);
					}
				}	
			}
			var span = document.createElement("span");
			span.className = "input-notification error png_bg";
			span.innerHTML = "请填写此字段!";
			insertAfter(span, input[i]);
			error.push(i);
		}
	}	
	if(error.length>0){
		return false;
	}
	else{
		return true;
	}
}
