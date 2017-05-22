/**
 * Created by lixiaoran on 2017/1/3.
 */

$(function(){

    var Register = {
        getCodeInfo : {
            regCodeCountDow : 5
        },
        dom : {
            getCode : $("#get-code")
        }
    };
    //获取验证码的效果
    Register.dom.getCode.click(function() {
        getCodeTime();
    });
    //倒计时函数
    function getCodeTime(){

        var _getCT = setInterval(function() {
            if(Register.getCodeInfo.regCodeCountDow == 0) {
                Register.dom.getCode.html("获取验证码");
                Register.dom.getCode.css("color","rgba(255,255,255,.5)");
                Register.getCodeInfo.regCodeCountDow = 5;
                clearInterval(_getCT);

            } else {
                Register.dom.getCode.html("重新发送(" + Register.getCodeInfo.regCodeCountDow + ")");
                Register.getCodeInfo.regCodeCountDow--;
                Register.dom.getCode.css("color","rgba(255,255,255,1)");
            }
        }, 1000);
    }
})