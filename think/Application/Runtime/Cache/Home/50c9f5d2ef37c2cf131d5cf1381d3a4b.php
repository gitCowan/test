<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
    <link rel="stylesheet" type="text/css" href="/think/Public/dist/css/bootstrap.min.css"/>
</head>
<body style="background-image: '/Public'">
<div style="border:1px solid;text-align:center;">
    <h3>这是画圆</h3>
<img src="..." alt="..." class="img-rounded">
<img src="..." alt="..." class="img-circle">
<img src="..." alt="..." class="img-thumbnail">
</div>
<div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown">
        选择你喜欢的水果
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">苹果</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">香蕉</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">梨</a></li>
        <li role="presentation"><a role="menuitem" tabindex="-1" href="#">桃</a></li>
    </ul>
</div>
<form>
<table border="1" align="center">
    <tr>
        <td>用户名</td>
        <td><input type="text"  class="form-control" id="nickname" name="nickname"/></td>
    </tr>
    <tr>
        <td>密码</td>
        <td><input type="password"  class="form-control" id="pwd" name="password"/></td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <input type="button" onclick="login.check()" class="btn btn-success" value="提交">
            <input type="reset" class="btn btn-inverse" value="重置"/>
        </td>
    </tr>
</table>
</form>
</body>
<script src="/think/Public/js/jquery-1.11.3.js"></script>
<script src="/think/Public/js/dialog/layer.js"></script>
<script src="/think/Public/js/login.js"></script>
<script src="/think/Public/js/dialog.js"></script>


<script src="/think/Public/dist/js/bootstrap.min.js"></script>
</html>