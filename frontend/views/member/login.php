<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>登录商城</title>
	<link rel="stylesheet" href="/style/base.css" type="text/css">
	<link rel="stylesheet" href="/style/global.css" type="text/css">
	<link rel="stylesheet" href="/style/header.css" type="text/css">
	<link rel="stylesheet" href="/style/login.css" type="text/css">
	<link rel="stylesheet" href="/style/footer.css" type="text/css">
    <style>
        .error{color: red}
        .c{color: red}
    </style>
</head>
<body>
	<!-- 顶部导航 start -->
	<div class="topnav">
		<div class="topnav_bd w990 bc">
			<div class="topnav_left">
				
			</div>
			<div class="topnav_right fr">
				<ul>
					<li>您好，欢迎来到京西！[<a href="login.html">登录</a>] [<a href="register.html">免费注册</a>] </li>
					<li class="line">|</li>
					<li>我的订单</li>
					<li class="line">|</li>
					<li>客户服务</li>

				</ul>
			</div>
		</div>
	</div>
	<!-- 顶部导航 end -->
	
	<div style="clear:both;"></div>

	<!-- 页面头部 start -->
	<div class="header w990 bc mt15">
		<div class="logo w990">
			<h2 class="fl"><a href="index.html"><img src="/images/logo.png" alt="京西商城"></a></h2>
		</div>
	</div>
	<!-- 页面头部 end -->
	
	<!-- 登录主体部分start -->
	<div class="login w990 bc mt10">
		<div class="login_hd">
			<h2>用户登录</h2>
			<b></b>
		</div>
		<div class="login_bd">
			<div class="login_form fl">
				<form action="<?=\yii\helpers\Url::to(['member/login'])?>" method="post" id="checklogin">
					<ul>
						<li>
							<label for="">用户名：</label>
							<input type="text" class="txt" name="username" />
						</li>
						<li>
							<label for="">密码：</label>
							<input type="password" class="txt" name="password" />
							<a href="">忘记密码?</a>
						</li>
						<li class="checkcode">
							<label for="">验证码：</label>
							<input type="text"  name="checkcode" />
							<img id="captcha_img" alt="" />
                            <span class="c"><?=isset($model->getErrors()["checkcode"][0])?$model->getErrors()["checkcode"][0]:''?></span>
							<span>看不清？<a href="javascript:;" id="change_captcha">换一张</a></span>
						</li>
						<li>
							<label for="">&nbsp;</label>
							<input type="checkbox" class="chb" name="remember" /> 保存登录信息
						</li>
						<li>
							<label for="">&nbsp;</label>
                            <input type="hidden" name="_csrf-frontend" value="<?=Yii::$app->request->csrfToken?>"/>
							<input type="submit" value="" class="login_btn" />
						</li>
					</ul>
				</form>

				<div class="coagent mt15">
					<dl>
						<dt>使用合作网站登录商城：</dt>
						<dd class="qq"><a href=""><span></span>QQ</a></dd>
						<dd class="weibo"><a href=""><span></span>新浪微博</a></dd>
						<dd class="yi"><a href=""><span></span>网易</a></dd>
						<dd class="renren"><a href=""><span></span>人人</a></dd>
						<dd class="qihu"><a href=""><span></span>奇虎360</a></dd>
						<dd class=""><a href=""><span></span>百度</a></dd>
						<dd class="douban"><a href=""><span></span>豆瓣</a></dd>
					</dl>
				</div>
			</div>
			
			<div class="guide fl">
				<h3>还不是商城用户</h3>
				<p>现在免费注册成为商城用户，便能立刻享受便宜又放心的购物乐趣，心动不如行动，赶紧加入吧!</p>

				<a href="regist.html" class="reg_btn">免费注册 >></a>
			</div>

		</div>
	</div>
	<!-- 登录主体部分end -->

	<div style="clear:both;"></div>
	<!-- 底部版权 start -->
	<div class="footer w1210 bc mt15">
		<p class="links">
			<a href="">关于我们</a> |
			<a href="">联系我们</a> |
			<a href="">人才招聘</a> |
			<a href="">商家入驻</a> |
			<a href="">千寻网</a> |
			<a href="">奢侈品网</a> |
			<a href="">广告服务</a> |
			<a href="">移动终端</a> |
			<a href="">友情链接</a> |
			<a href="">销售联盟</a> |
			<a href="">京西论坛</a>
		</p>
		<p class="copyright">
			 © 2005-2013 京东网上商城 版权所有，并保留所有权利。  ICP备案证书号:京ICP证070359号 
		</p>
		<p class="auth">
			<a href=""><img src="/images/xin.png" alt="" /></a>
			<a href=""><img src="/images/kexin.jpg" alt="" /></a>
			<a href=""><img src="/images/police.jpg" alt="" /></a>
			<a href=""><img src="/images/beian.gif" alt="" /></a>
		</p>
	</div>
	<!-- 底部版权 end -->
    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <!--引入js库-->
<!--    <script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/jquery.validate.min.js"></script>-->
    <!--将该本地化信息文件 dist/localization/messages_zh.js 引入到页面-->
<!--    <script src="http://static.runoob.com/assets/jquery-validation-1.14.0/dist/localization/messages_zh.js"></script>-->
    <script type="text/javascript">
        function bindPhoneNum(){
            //启用输入框
            $('#captcha').prop('disabled',false);

            var time=30;
            var interval = setInterval(function(){
                time--;
                if(time<=0){
                    clearInterval(interval);
                    var html = '获取验证码';
                    $('#get_captcha').prop('disabled',false);
                } else{
                    var html = time + ' 秒后再次获取';
                    $('#get_captcha').prop('disabled',true);
                }

                $('#get_captcha').val(html);
            },1000);
        }
        // 在键盘按下并释放及提交后验证提交表单
        $().ready(function() {
// 在键盘按下并释放及提交后验证提交表单
            $("#checklogin").validate({
                rules: {
                    //json格式
                    username: {
                        required: true//用户名 必填
                        //验证用户名唯一,使用了get传参
                    },
                    password: {
                        required: true
                    },
                    checkcode:{
                        required: "验证码不能为空",
                        validateCaptcha:"验证码错误"
                    }//使用自定义验证规则验证验证码
                },
                messages: {
                    username: {
                        required: "请输入用户名"
                    },
                    password: {
                        required: "请输入密码"
                    },
                    checkcode:{
                        required: "验证码不能为空",
                        validateCaptcha:"验证码错误"
                    }
                },
                errorElement:'span'//错误信息的标签
            });
        });
        var hash;//为了获取图片的hash值，定义变量
        var captcha_url='<?=\yii\helpers\Url::to(['site/captcha'])?>';
        var change_captcha=function () {//封装后不会执行，需初始化
            $.getJSON(captcha_url,{refresh:1},function (json) {
                $("#captcha_img").attr('src',json.url);
                //获取hash
                hash =json.hash1;
//                console.log(hash);
            });
        };
        change_captcha();
        //点击字切换验证码
        $('#change_captcha').click(function () {
            change_captcha();
        });
        //点击图片切换验证码
        $('#captcha_img').click(function () {
            change_captcha();
        });
        //添加自定义规则
        jQuery.validator.addMethod("validateCaptcha", function(value, element) {
            var h ;
            for (var i = value.length - 1, h = 0; i >= 0; --i) {
                h += value.charCodeAt(i);
            }
            return this.optional(element) || (h == hash);//测试成功返回测试结果
        }, "验证码不正确");
    </script>
</body>
</html>