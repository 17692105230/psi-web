<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>进销存系统</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="apple-mobile-web-app-title" content="进销存系统"/>
    <meta name="robots" content="all" />
    <link href="/assets/web/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/web/css/login.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/assets/common/css/themes/default/easyui.css" />
    <link rel="stylesheet" type="text/css" href="/assets/common/css/themes/icon.css" />
    <link rel="icon" href="/assets/web/img/tianjinbitbug_favicon.ico" type="image/x-icon">
    <script type="text/javascript" src="/assets/common/js/jquery.min.1.9.4.js"></script>
    <script type="text/javascript" src="/assets/common/js/jquery.easyui.min.1.9.4.js"></script>
    <script type="text/javascript" src="/assets/web/js/src/easyui.base.js"></script>
    <script type="text/javascript" src="/assets/web/js/src/operation.login.js"></script>

    <script type="text/javascript">
        if (window.top !== window.self) {
            window.top.location = window.location
        };
    </script>
    <style type="text/css">
        .browser-happy {position:fixed;left:0;right:0;top:0;bottom:0;width:100%;height:100%;z-index:999;background:#24a6fa}
        .browser-happy .content {text-align:center;color:#fff;font-size:24px;padding-top:100px}
        .browser-happy .content a {display:inline-block;padding:10px 20px;border:2px solid #fff;text-decoration:none;color:#fff}
        #login_form {background: #FFFFFF;}
        #login_form h4{color: #333333;}
        #login_form input{background: #FFFFFF;border-color: #DCDFE6;color: #333333;}
        #login_form .btn {background-color:#337ab7;outline: none;border-color:#2e6da4}
        body {font-family:Microsoft YaHei;}
    </style>
</head>

<body onkeydown="if(window.event.keyCode == 13){
                    $.h.login.onLogin()
                }" class="signin tianjinsignin" style="background-image: url(/assets/web/img/morenloginbg.jpg);">
<div class="signinpanel tianjinsigninpanel1" style="position: relative;padding: 10%;padding-bottom: 0;">
    <img style="position: absolute;left: 0;top: 50px;left: 68px;" src="/assets/web/img/428b4683560d5a7f2e82eb2f1afbb51.png" alt="">

    <div class="rowBox" style="justify-content: center;margin: 0 auto;">

        <div>
            <form id="login_form" class="login_form tianjinlogin_form" method="post">
                <h4 class="no-margins">用户登录</h4>
                <p class="m-t-md"></p>
                <input type="text" class="form-control" name="username" maxlength="20" placeholder="用户名" />
                <input type="password" class="form-control m-b" name="password" maxlength="12" placeholder="密码" />
                <div>
                    <input type="text" class="form-control" name="captcha" maxlength="6" placeholder="验证码" autocomplete="off" />
                    <img id="captcha" src="/captcha" alt="验证码" onclick="this.src='/captcha?rand='+Math.random()" style="width:100%;cursor:pointer;margin-top:15px;">
                </div>
                <button type="button" class="btn btn-primary btn-block" onClick="$.h.login.onLogin();">登录</button>
            </form>
        </div>
    </div>

</div>
<div class="foot_text_login1" style="color: #FFFFFF;">&copy; 2020 进销存管理系统公司 All Rights Reserved</div>
</body>

</html>