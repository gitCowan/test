<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<h3 align="center">恭喜您授权成功</h3>
<table border="1" align="center">
    <tr>
        <td colspan="2"><img src="<?php echo ($resul["headimgurl"]); ?>"/></td>
    </tr>
    <tr>
        <td>姓名:</td>
        <td><?php echo ($resul["nickname"]); ?></td>
    </tr>
    <tr>
        <td>性别:</td>
        <td>
            <?php if($resul['sex'] == 1): ?>男
            <?php else: ?>
                女<?php endif; ?>
        </td>
    </tr>
    <tr>
        <td>住址:</td>
        <td><?php echo ($resul["country"]); echo ($resul["province"]); echo ($resul["city"]); ?></td>
    </tr>
</table>
</body>
</html>