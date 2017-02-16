/*
* 前端业务类
* */
var login ={
    check : function (){
        //获取登录页面中的用户名
        var nickname = $("#nickname").val();
        var password = $("#pwd").val();

        //执行异步请求
        var url = 'check';
        var data = {'nickname':nickname,'password':password};
        $.post(url,data,function(result){
            dialog.error(result.message);
        },'json');
    }
}