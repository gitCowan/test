<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Insert title here</title>


<style>

 #preview, .img, img  
 {  
 width:150px;  
 height:150px; 
 float:left; 
 }  
 #preview  
 {  
border:1px solid #000;  
} 


a{display:inline-block; width:100px; height:40px; background:red; position:relative; overflow:hidden;}
a:hover{background:green;}
/* input{position:absolute; right:0; top:0; font-size:100px; opacity:0; filter:alpha(opacity=0);} */
input{
	float:left; 
	width:71px; 
	border:1px solid #006666; 
	padding:0px; 
	margin:0px;
}
</style>

</head>
<body>
<a href="/think/index.php/Home/Index/upload">点击</a>
<form action="/think/index.php/Home/Index/upload">
	<!-- <input type="file"/> -->
	<!-- <input type="submit" value="提交"/> -->
	
     <div id="preview">
       
        

    <input type="file"onchange="preview(this)" />


</div> 
		 <?php $__FOR_START_27318__=3;$__FOR_END_27318__=i+1;for($i=$__FOR_START_27318__;$i < $__FOR_END_27318__;$i+=1){ echo ($i); } ?>

    <script type="text/javascript">    
 function preview(file)  
 {  

 		var prevDiv = document.getElementById('preview'); 

 if (file.files && file.files[0])  
 {  
 var reader = new FileReader();  
 reader.onload = function(evt){  
 prevDiv.innerHTML = '<img src="' + evt.target.result + '" onchange="'+preview(this)+'"/>';  
}    
 reader.readAsDataURL(file.files[0]);  
}  
 else    
 {  
 prevDiv.innerHTML = '<div class="img" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'"></div>';  
 }  
 }  
 </script> 

</form>
</body>
</html>