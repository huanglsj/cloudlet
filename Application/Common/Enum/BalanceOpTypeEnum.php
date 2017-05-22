<?php

abstract class BalanceOpTypeEnum {
	//充值
	const CZ = 1;
	
	//提现
	const TXSQ = 2;
	
	//建仓
	const JC = 3;
	
	//平仓
	const PC = 4;
	
	//提现驳回
	const TXBH = 5;
	
	//提现失败
	const TXSB = 6;

    //充值赠送
    const CZZS = 7;

    //清空赠送
    const QKZS = 8;
	
	//手动修改
	const SDXG = 100;
}
