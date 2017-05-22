
/**
 * @param value
 * @param m 最大整数位数（null表示不限制）
 * @param n 最大小数位数（null表示不限制，0表示没有小数位）
 */
function isNumeric(value, m, n) {
	if (isNaN(value)){
		return false;
	}
	
	var regex;
	if (m==null && n==null) {
		//整数位和小数位都不限制
		regex = new RegExp("^[+-]?([0-9]*\\.?[0-9]*)$");
	}
	else if (m==null && n!=null) {
		//整数位不限制，小数位限制
		regex = new RegExp("^[+-]?([0-9]*\\.?[0-9]{0,"+n+"})$");
	}
	else if (m!=null && n==null) {
		//整数位限制，小数位不限制
		regex = new RegExp("^[+-]?([0-9]{0,"+m+"}(\\.[0-9]*)?)$");
	}
	else {
		//整数位和小数位都限制
		regex = new RegExp("^[+-]?(([0-9]{0,"+m+"}\\.?)|(\\.[0-9]{0,"+n+"})|([0-9]{0,"+m+"}\\.[0-9]{0,"+n+"}))$");
	}
	
	return regex.test(value);
}

function isInteger(value, m){
	var regex;
	if (m == null) {
		regex = /^[0-9]*$/;
	}
	else {
		regex = new RegExp("^([0-9]{0,"+m+"})$");
	}
	return regex.test(value);
}
