/**
 * 共通方法类脚本
 */

function GetRTime(shijian,t_h,t_m,t_s) {
	
	var EndTime = new Date(shijian);
	var NowTime = new Date();
	var t = EndTime.getTime() - NowTime.getTime();
	var d = 0;
	var h = 0;
	var m = 0;
	var s = 0;
	if (t >= 0) {
		d = Math.floor(t / 1000 / 60 / 60 / 24);
		h = Math.floor(t / 1000 / 60 / 60 % 24);
		m = Math.floor(t / 1000 / 60 % 60);
		s = Math.floor(t / 1000 % 60);
	}
	if ($("#" + t_h)) {
		$("#" + t_h).html(h + "时");
	}
	if ($("#" + t_m)) {
		$("#" + t_m).html(m + "分");
	}
	if ($("#" + t_s)) {
		$("#" + t_s).html(s + "秒");
	}
	
}