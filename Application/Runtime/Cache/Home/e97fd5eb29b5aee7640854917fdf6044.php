<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<title>微云交易</title>
<meta name="keywords" content="微云交易，轻松获得高收益---全国领先的交易平台" />
<meta name="description" content="微云交易，轻松获得高收益---全国领先的交易平台">

<link rel="stylesheet" type="text/css" href="/Public/Home/css/cd.css" />
<link rel="stylesheet" type="text/css" href="/Public/Home/css/icons.css" />
<script language="javascript" type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
</head>
<body>
<div class="main"> 	
       
<link rel="stylesheet" href="/Public/Home/css/common.css" />
<link rel="stylesheet" href="/Public/Home/css/base.css">
<link rel="stylesheet" href="/Public/Home/css/login.css?20170501">
<link href="/Public/Home/css/riskInfo.css" rel="stylesheet" />
<script src="/Public/Home/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Home/js/script.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Home/js/sea.js" async></script>
<!-- <script src="/Public/Home/js/register.js"></script> -->
	<link type="text/css" href="//g.alicdn.com/sd/ncpc/nc.css?t=<?php echo strtotime(date('Y-m-d H:i')) ?>" rel="stylesheet"/>
	<script type="text/javascript" src="//g.alicdn.com/sd/ncpc/nc.js?t=<?php echo strtotime(date('Y-m-d H:i')) ?>"></script>
  <style>
    #dom_id .nc_iconfont.btn_slide{
      width: 2em;
    }
    #dom_id .nc_iconfont.btn_ok{
      width: 2em;
    }
    body .wrap .main-con .input-con span{
      width: 4em;
    }
  </style>
<body>
<div class="wrap">
		<form id="reviseForm" class="i-form" method="post" onsubmit="return checkread()" action="<?php echo U('User/register');?>" >
        <div class=" main-con">
            <a class="logo" href="javascript:void(0)">
                <img src="/Public/Home/images/login-logo.png">
            </a>
			<input id="openid"  type="hidden"  name="openid" value="<?php echo ($openid); ?>">
            <div class="input-con">
                <span><strong>手机号:</strong></span>
                <input type="tel" placeholder="请输入手机号" id="tel" class="f-input text" maxlength="11" name="utel">
            </div>
		<div class="input-con">
						<div id="dom_id"></div>
		</div>
            <div class="code input-con">
                <span><strong>验证码:</strong></span>
                <input type=text placeholder="短信验证码" maxlength="6" name="code">
                <!--<a id="get-code" href="javascript:void(0)">获取验证码</a>-->
            </div>
            <?php if(empty($openid)): ?><div class="input-con">
	                <span><strong>用户名:</strong></span>
	                <input id="username" class="f-input text" type="text"  maxlength="16" placeholder="请输入用户名" name="username">
	            </div><?php endif; ?>
            <?php if(!empty($openid)): ?><input id="username" type="hidden"  value="<?php echo ($user["username"]); ?>" name="username"><?php endif; ?>
            <div class="input-con">
                <span><strong>密码:</strong></span>
                <input id="n-pwd" class="f-input text" type="password" maxlength="15" placeholder="请输入六位密码" name="upwd">
            </div>
<!--            <div class="input-con">
                <span><strong>确认:</strong></span>
                <input id="r-pwd" class="f-input text" type="password" maxlength="15" placeholder="请再次输入六位密码" name="repassword">
            </div>-->
			<div class="input-con" <?php if(!empty($invitecode)): ?>style="display:none"<?php endif; ?>>
                <span><strong>邀请码:</strong></span>
                <input id="r-pwd" class="f-input text" value="<?php echo ($invitecode); ?>" type="text" placeholder="请输入邀请码" name="yaoqingCode">
            </div>
            <div class="read">
                <img id="readed" class="ok" src="/Public/Home/images/Ok.png">
                <span class="fir">我已阅读并同意</span>
                <span id='btnBook'>《服务协议及隐私条款》</span>
            </div>
            <!-- <a class="register-btn" href="javascript:void(0)">注册</a> -->
			<input type="submit" value="完成注册" class="register-btn" id='send2' style="font-weight:800;">
            <a class="login" href="<?php echo U('User/login');?>" style="color:#FFF;">登录</a>

            <div class="bot-font">
                <img src="/Public/Home/images/font.png">
            </div>
        </div>
        </form>
 <script type="text/javascript">
        $(function () {
            // bg switcher
            var $btns = $(".bg-switch .bg");
            $btns.click(function (e) {
                e.preventDefault();
                $btns.removeClass("active");
                $(this).addClass("active");
                var bg = $(this).data("img");

                $("html").css("background-image", "url('/Public/Home/images/bgs/" + bg + "')");
            });
        });
        <?php if(!empty($pwderror)): ?>alert("<?php echo ($pwderror); ?>");<?php endif; ?>
    </script>
	<!-- 此段必须要引入 -->
	<script>
                    var tel = $("#tel");
		var nc = new noCaptcha();
		var nc_appkey = '<?php echo (C("ALIYUN_ACCESS_KEY.SECURITY_APP")); ?>';  // 应用标识,不可更改
	    var nc_scene = 'login';  //场景,不可更改
		var nc_token = [nc_appkey, (new Date()).getTime(), Math.random()].join(':');
		var nc_option = {
			renderTo: '#dom_id',//渲染到该DOM ID指定的Div位置
			appkey: nc_appkey,
	        scene: nc_scene,
			token: nc_token,
	        //trans: '{"name1":"code100"}',//测试用，特殊nc_appkey时才生效，正式上线时请务必要删除；code0:通过;code100:点击验证码;code200:图形验证码;code300:恶意请求拦截处理
			callback: function (data){
				console.log(data.csessionid);
				console.log(data.sig);
				console.log(nc_token);
$.ajax({
                   type : 'POST',
                   url : "<?php echo U('User/smsverify11');?>",
                   data : {'tel' : tel.val()},
                   success:function(data){
                       //alert(data);
                       if(data != ''){
                    	   alert(data);
			   //存在相同的电话
                    	   //alert('您可能通过其他途径已经注册，请输入该手机验证码和用户密码以完成注册！')
                    	   $('.telrefuse').css('display','none');
                       }
                   },
                   error:function(data){
                       alert(data);
                   }
               });
				document.getElementById('csessionid').value = data.csessionid;
				document.getElementById('sig').value = data.sig;
				document.getElementById('token').value = nc_token;
	            document.getElementById('scene').value = nc_scene;
			}
		};
		nc.init(nc_option);

	</script>

    </div>
	<div class="box">
			<div id="dialogBg"></div>
			<div id="dialog" class="main-con">
				<div class="content">
					<h4>微云交易服务协议</h4>
					<!-- <a href="javascript:;" class="claseDialogBtn"
									style="position: absolute; right: 20px; top: 10px; mz-index: 111; font-size: 1.5rem; width: 40px; height: 20px; text-align: center;">关闭</a> -->
					<p>尊敬的投资者：</p>
					<p>感谢您选择微云交易服务。微云交易服务协议（以下简称本协议）由微云交易大宗商品交易中心有限公司 （以下简称微云交易）和您签订。</p>
					<p>一、 微云交易服务协议的确认</p>
					<p>本协议有助于您了解微云交易为您提供的服务内容及您使用服务的权利和义务，请您仔细阅读（特别是以粗体标注的内容）。如果您对本协议的条款有疑问，您可通过微云交易客服电话进行咨询。</p>
					<p>二、微云交易为您提供的服务内容</p>
					<p>1、入金：您可以将您银行卡内的资金充值到您的微云交易账户。</p>
					<p>2、交易</p>
					<p>（1）交易对象：微云交易在交易平台上提供的所有交易品种。</p>
					<p>（2）交易时间：具体以微云交易所规定的时间为准（特殊时间、国家法定节假日及国际市场休市除外），特殊情况下，开市、休市时间如果有变动以微云交易的通知为准。</p>
					<p>（1）交易方式：通过网络系统在微云交易平台上进行交易。</p>
					<p>3、出金：您可以委托华微云交易将您微云交易账户内，除持仓占用以外的资金转到您的银行卡。</p>
					<p>三、微云交易账户的开户、使用和销户</p>
					<p>1、开户</p>
					<p>（1）微云交易账户由微云交易统一进行监管，实行一户一码制且必须通过微云交易的审核，进行激活后方可进行交易。</p>
					<p>（2）当您在开户时，须提供有效的本人身份证（自然人交易商）或机构代码证、营业执照（机构交易商）等有效证件，并保证所提供开户资料的真实性、完整性、准确性、有效性，同时需备有有效的银行账户（银行开户证件需同交易账户开户证件一致），用以实现与微云交易资金账户之间的划转。</p>
					<p>（3）如您提供的身份材料或开户资料有虚假，微云交易有权停止您的交易权限、冻结您的资金账户，由此而引起的一切损失将由您本人承担。</p>
					<p>（4）当您注册时的信息、资料等有发生变化时，您应当及时以书面方式通知微云交易。如因资料发生变化，您未及时通知微云交易而造成的损失由您本人承担。</p>
					<p>2、使用</p>
					<p>（1）您应妥善保管好您的交易账户和所有密码，如若密码丢失，您须提供其本人手机号码、验证码及开户时预留的电子签名，方可将密码重置。</p>
					<p>（2）您的交易账户只限您本人（或本机构）使用，不得转借他人，如转借他人所引起的一切纠纷和损失均由您本人承担。</p>
					<p>（3）微云交易仅能通过您的账号和密码核实您的身份，您应妥善保管账号和密码信息。若因您的账号、密码信息等被盗而导致的一切不利于您本人的操作，均与微云交易无关。</p>
					<p>（4）您若对当日的交易结算结果有异议时，须在一个工作日即24 小时内向微云交易提出。如果您未提出异议，视为认可您对交易结算单所记载事项。</p>
					<p>3、销户</p>
					<p>在需要终止使用微云交易服务时，符合以下条件的，您可以申请注销您的微云交易账户：</p>
					<p>（2）您可以通过人工或者书面申请的方式申请注销账户。</p>
					<p>（3）为了维护您的合法利益，您申请注销的账户应当不存在未了结的权利义务或其他因为注销该账户会产生纠纷的情况，不存在任何未完结交易，没有余额等资产。</p>
					<p>四、使用微云交易注意事项</p>
					<p>1、为了您的资金安全，您的资金都是由与微云交易合作的商业银行进行托管，不直接与微云交易挂钩，且您缴存在微云交易账户的交易资金不会产生任何利息。</p>
					<p>2、微云交易交易结算的具体办法及细则参照《微云交易结算管理办法》执行。</p>
					<p>3、微云交易到期采用现金交割，不涉及现货交割。</p>
					<p>4、所有的交易指令和成交记录，均以微云交易电脑记录数据为准。</p>
					<p>5、报价系统可能因交易系统故障、互联网传输故障等原因而导致出现错误的报价，因错误报价引起的所有成交单，微云交易均予以撤销。</p>
					<p>6、您必须遵守和接受微云交易规定有关微云交易事项的一切规则。</p>
					<p>7、微云交易不接受您的任何典押、质押或抵押，您也不得将本协议书中的任何权益全部或部分转让。</p>
					<p>8、您不得私下与微云交易的工作人员达成以利益共享、风险共担的承诺作为交易，委托其替代您进行微云交易具体操作。</p>
					<p>五、服务费用</p>
					<p>在您参与微云交易过程中，需缴付每笔交易作为手续费，无其他费用支出。</p>
					<p>六、违约条款</p>
					<p>任何一方违反本协议书给对方造成损失的，违约方应承担相应的经济赔偿责任。</p>
					<p>七、免责条款</p>
					<p>1、微云交易及其工作人员对市场的判断和操作建议仅供您的参考，微云交易的任何工作人员向您作出获利以及免受损失的担保或者承诺都是违反微云交易规定的，且这些担保或承诺对微云交易不具有任何法律约束力，希望您能清楚的认识并理性的对待，以防止您的利益受损。否则，您将自负盈亏，与微云交易无关。</p>
					<p>2、基于微云交易不能控制电讯信号的中断和连接以及互联网的畅通，也不能保证您自身网络设备及电讯设备的稳定性，由此原因而使您遭受的损失，由您本人自行承担，微云交易不负有任何法律责任。</p>
					<p>3、由于系统性不可抗拒的风险以致于您的亏损超过其资金净值，微云交易将保留其向您追索的权利，您应及时履行补足资金的义务。</p>
					<p>4、由于地震、水灾、火灾、暴动、罢工、战争、政府管制、国际或国内的禁止或限制以及技术故障、电子故障等不可抗力因素导致的交易中断、延误等风险，微云交易不承担责任，但应在条件允许的范围内采取必要的补救措施以减少因不可抗力造成的损失。</p>
					<p>5、由于发生不可抗力及国家有关法律、政策的改变、紧急措施的出台等导致交易系统临时或永久性关闭及其他风险，微云交易不承担责任。</p>
					<p>八、完整协议</p>
					<p>本协议由《微云交易服务协议》与《微云交易规则》、《微云交易风险控制管理办法》、《微云交易结算管理办法》等各项规则组成，各项规则有约定的，而本协议条款没有约定的，以各项规则约定为准。本协议部分内容被有管辖权的法院认定为违法或无效的，不因此影响其他内容的效力。</p>
					<p>九、法律适用与管辖</p>
					<p>本协议之效力、解释、变更、执行与争议解决均适用中华人民共和国法律。因本协议产生之争议，均应依照中华人民共和国法律予以处理，并由被告住所地人民法院管辖。</p>
					
					<h4>风险告知书</h4>
					<p>尊敬的投资者：</p>
					<p>欢迎您使用微云交易进行交易活动，为了更好的防范风险，在您进行交易之前请您务必认真阅读以下内容：</p>
					<p>一、重要提示</p>
					<p>1、微云交易与现有的国内其他交易市场相比，在交易模式、交易规则等方面存在着一定的差别，请您在参与投资之前务必详尽的了解本市场投资的基本知识和相关风险以及微云交易有关的业务规则等，依法合规地从事该项交易业务；</p>
					<p>2、微云交易为了确保市场“公开、公平、公正”和健康稳定地发展，将采取更加严格的措施，强化市场的监管。请您务必密切地关注微云交易的公告、风险提醒等信息，及时了解市场风险状况，做到理性投资，切忌盲目跟风；</p>
					<p>3、微云交易的交易业务不适合利用养老基金、债务资金(如银行贷款) 等进行投资的投资者。只适合于满足以下全部条件的投资者：</p>
					<p>(A) 年满18至60周岁并具有完全民事行为能力的中国公民或依法在中华人民共和国境内注册成立的企业法人或其他经济组织；</p>
					<p>(B) 能够充分理解有关于此交易的一切风险，并且具有风险承受能力；</p>
					<p>(C) 因投资失误而造成账户资金部分或全部损失、仍不会改变其正常的生活方式或影响其正常生产经营状况的；</p>
					<p>(D) 不具有微云交易开户管制相关制度规定的禁止开户情形。</p>
					<p>4、您在开通交易之前，请配合会员开展的适当性管理工作，完整、如实地提供开户所需要的信息，不得采取弄虚作假等手段规避有关的要求，否则，微云交易有权拒绝为您开通交易服务；</p>
					<p>5、本风险揭示书风险揭示事项仅为列举性质，未能详尽的列明有关该市场的所有风险因素，您在参与该市场投资之前，还应认真的对其他可能存在的风险因素有所了解和掌握。</p>
					<p>二、相关的风险揭示</p>
					<p>(一) 政策风险</p>
					<p>国家法律、法规、政策以及规章的变化，紧急措施的出台，相关监管部门监管措施的实施，微云交易交易规则的修改等原因，均可能会对您的投资产生影响，您必须承担由此导致的损失。</p>
					<p>(二) 价格波动风险</p>
					<p>本市场的交易品种的价格受多种因素的影响(并且影响机制非常复杂，您在实际操作中存在出现投资失误的可能性，如果不能有效控制风险，则可能遭受较大的损失，您必须独自承担由此导致的一切损失。</p>
					<p>(三) 技术风险</p>
					<p>1、此业务通过电子通讯技术和互联网技术来实现。由通讯或网络故障导致的某些服务中断或延时可能会对您的投资产生影响。</p>
					<p>2、您的电脑、操作系统等软硬件环境有可能由于自身原因或被病毒、网络黑客攻击等原因，从而导致您的交易系统连接受到影响，使您的投资决策无法正确或及时执行。</p>
					<p>对于上述不确定因素带来的风险，有可能会对您的投资产生影响，您应该充分了解并承担由此造成的全部损失。</p>
					<p>(四) 交易风险</p>
					<p>1、您需要了解微云交易的交易业务具有低保证金的投资特点，可能导致快速的盈利或亏损。若建仓的方向与行情的波动相反，会造成较大的亏损，根据亏损的程度，您必须有条件满足随时追加保证金的要求，否则其持仓将会被强行平仓，您必须承担由此造成的全部损失。</p>
					<p>2、本交易市场的价格可能会与其他途径的报价存在微弱的差距，并不能保证上述交易价格与其他市场保持完全的一致性。</p>
					<p>3、您在交易系统内，通过网上终端(含手机移动终端) 所提交的市价单一经成交，即不可撤销，您必须接受这种方式可能带来的风险。</p>
					<p>4、微云交易提供的交易系统中的指价建仓和指价平仓功能可能由于各种原因导致交易无法在达到触发条件时成交，如果您无法理解或承受上述风险，我们建议您不要使用该功能进行交易；如果您坚持使用该功能，我们将认为您已经完全理解并愿意承担使用该功能的全部风险，并愿意承担使用该功能所带来的一切后果。</p>
					<p>5、微云交易禁止所有会员(及其分支机构) 、会员居间人及前述主体的工作人员以任何方式与您分享收益或共担风险，或利用您的资金从事任何代客理财业务。</p>
					<p>6、您的成交单据必须建立在自主决定的基础之上。微云交易、会员(及其分支机构) 、会员居间人及前述主体的工作人员提供的任何关于市场的分析和信息，仅供投资者参考，不构成任何要约或承诺。由此而造成的交易风险由您自行承担。</p>
					<p>(五) 不可抗力风险</p>
					<p>任何因微云交易不能够控制的原因，包括但不限于地震、水灾、火灾、暴动、罢工、战争、政府管制、国际或国内的禁止或限制以及停电、技术故障、电子故障等其他无法预测或防范的不可抗力事件，都有可能会对您的交易产生影响，您应该充分了解并承担由此造成的全部损失。</p>
				</div>
				<div class="read-con">
					<a class="read-btn" href="javascript:void(0)">我已阅读以上协议并同意遵守</a>
				</div>
			</div>
		</div>
</body>
<script type="text/javascript">
	var readedsrc = "/Public/Home/images/pick.png";
	var unreadsrc = "/Public/Home/images/Ok.png";
    var w,h,className;
    function getSrceenWH(){
      w = $(window).width();
      h = $(window).height();
      $('#dialogBg').width(w).height(h);
      $('#dialog').height(h);
      $('.box').height(h);
      $('.wrap .index').css("min-height",h);
    }
    window.onresize = function(){  
      getSrceenWH();
    } 
    $(window).resize();  
        $(document).ready(function(){
        	getSrceenWH();
          //关闭弹窗
            $('.claseDialogBtn,#claseDialogBtn').click(function(){
              $('#dialogBg').fadeOut(200,function(){
                $('#dialog').addClass('bounceOutUp').fadeOut(200);
              });
            });
          
          	$('#readed').click(function(){
          		if($('#readed').hasClass('readed')){
          			$('#readed').attr('src',unreadsrc);
          			$('#readed').removeClass('readed');
          		}else{
          			$('#readed').attr('src',readedsrc);
          			$('#readed').addClass('readed');
          		}
          	});
        });
        
        $(function(){
            //如果是必填的，则加红星标识.
            //文本框失去焦点后
            $('form :input').blur(function(){
                var $parent = $(this).parent();
                $parent.find(".formtips").remove();
                //验证用户名
                if( $(this).is('input[name="username"]') ){
                    if( this.value=="" || this.value.length < 6 ){
                        var errorMsg = '请输入至少6位的用户名.';
                        $parent.append('<span class="formtips" style="color:red;width:5px">*</span>');
                    }else{
                        $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');
                    }
                }
                //手机号码验证
                if( $(this).is('input[name="utel"]') ){
                    if( this.value=="" || ( this.value!="" && !/^1[3|4|5|7|8][0-9]\d{4,8}$/.test(this.value) ) ){
                        var errorMsg = '请输入正确的手机号码.';
                        $parent.append('<span class="formtips" style="color:red;width:5px">*</span> ');
                    }else{
                        $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');
                    }
                }
                
                //手机验证码验证
/*                 if( $(this).is('input[name="code"]') ){
                	if( this.value=="" ){
                        var errorMsg = '请输入手机验证码.';
                        $parent.append('<input  class="f-input formtips onError" type="text" value="'+errorMsg+'" > ');
                    }else{
                        $parent.append('<input class="f-input formtips onSuccess" style="display:none" type="text"  > ');
                    }
                } */

                //密码验证
                if( $(this).is('input[name="upwd"]') ){
                    if( this.value=="" ){
                        var errorMsg = '请输入正确的密码.';
                        $parent.append('<span class="formtips" style="color:red;width:5px">*</span>');
                    }else{
                        $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');
                    }
                }
                //确认密码验证
/*                if( $(this).is('input[name="repassword"]') ){
                    if( this.value!=$('#n-pwd').val()){
                        var errorMsg = '两次密码不一样.';
                        $parent.append('<span class="formtips" style="color:red;width:5px">*</span>');
                    }else{
                        $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');
                    }
                }*/
              //邀请码验证
              /*  if( $(this).is('input[name="yaoqingCode"]') ){
                	  if( this.value=="" ){
                          var errorMsg = '输入6位邀请码！';
                          $parent.append('<span class="formtips" style="color:red;width:5px">*</span>');
                      }else{
                          $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');
                      }
                }*/

            }).keyup(function(){
                $(this).triggerHandler("blur");
            }).focus(function(){
                $(this).triggerHandler("blur");
            });//end blur
            
          //提交，最终验证。
            $('#send2').click(function(){
                $("form :input.text").trigger('blur');
                var numError = $('form .onError').length;
                if(numError){
                    return false;
                } else {
                    //验证码验证
                    var codeFlg = false;
                    var tel = $('input[name="utel"]').val();
                    var code = $('input[name="code"]').val();
                    $.ajax({
                       type : 'POST',
                       url : "<?php echo U('User/checkVerificationCode');?>",
                       data : {'tel' : tel,'code' : code},
                       async : false ,
                       success:function(data){
                    	   codeFlg = data;
                       }
                    });
                    if(!codeFlg){
                    	alert("手机验证码错误！");
                    	return false;
                    }
                }
            });
            //服务条款效果
            $("#btnBook").click(function(){
                //$("#panel").slideToggle("slow");
                $(".box").show();
                $("#dialogBg").show();
                $("#dialog").show();
            });

			$(".read-btn").click(function(){
                //$("#panel").slideToggle("hide");
                $(".box").hide();
                $("#dialogBg").hide();
                $("#dialog").hide();
            });
			$(".claseDialogBtn").click(function(){
                //$("#panel").slideToggle("hide");
                $(".box").hide();
                $("#dialogBg").hide();
                $("#dialog").hide();
            });
        })


       var countdown = 60;
       $(document).ready(function() {
           $("#get-code").click(function() {
        	   if (countdown != 60){ return false};
               var tel = $('input[name="utel"]').val();
               var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
               if (tel == '') {
                   alert("请输入手机号！");
                   return false;
               } else if (!myreg.test(tel)) {
                   alert("手机号码格式不正确！");
                   return false;
               }
               settime(this);
               $.ajax({
                   type : 'POST',
                   url : "<?php echo U('User/smsverify11');?>",
                   data : {'tel' : tel},
                   success:function(data){
                       //alert(data);
                       if(data != ''){
                    	   alert(data);
			   //存在相同的电话
                    	   //alert('您可能通过其他途径已经注册，请输入该手机验证码和用户密码以完成注册！')
                    	   $('.telrefuse').css('display','none');
                       }
                   },
                   error:function(data){
                       alert(data);
                   }
               });
           });
       });
       function settime(obj) {
           if (countdown == 0) {
               $(obj).text("获取验证码");
               countdown = 60;
               return;
           } else {
               $(obj).text("重新发送(" + countdown + ")");
               countdown--;
           }
           setTimeout(function() {
               settime(obj)
           }, 1000)
       }
       
       function checkread(){
    	   if($('#readed').hasClass('readed') == false){
    		   alert('如需完成注册，请勾选已阅读！');
    		   return false;
    	   }
    	   return true;
       }

    //禁止滚动条
    $(document.body).css({
        "overflow-x":"hidden",
        "overflow-y":"hidden"
    });
    document.body.addEventListener('touchmove', function (event) {
        event.preventDefault();
    }, false);
    </script>

</div>
</body>
</html>