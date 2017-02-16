<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>登录</title>
</head>
<body>
<form>
<table border="1">
    <tr>
        <td>用户名</td>
        <td><input type="text" id="nickname" name="nickname"/></td>
    </tr>
    <tr>
        <td>密码</td>
        <td><input type="password" id="pwd" name="password"/></td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="button" onclick="login.check()" value="提交">
            <input type="reset" value="重置"/>
        </td>
    </tr>
</table>
</form>
</body>
<script src="/think/Public/js/jquery-1.11.3.js"></script>
<script src="/think/Public/js/dialog/layer.js"></script>
<script src="/think/Public/js/login.js"></script>
<script src="/think/Public/js/dialog.js"></script>
</html>