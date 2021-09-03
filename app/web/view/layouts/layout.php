<!DOCTYPE html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <title>永保电销系统</title>
    <link rel="stylesheet" type="text/css" href="/assets/common/css/themes/gray/easyui.css" />
    <link rel="stylesheet" type="text/css" href="/assets/common/css/themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="/assets/{{$assets}}/css/jitnry.css" />
	<link rel="stylesheet" type="text/css" href="/assets/{{$assets}}/css/easyui.upload.css" />
    <script type="text/javascript" src="/assets/common/js/jquery.min.1.9.4.js"></script>
	<script type="text/javascript" src="/assets/common/js/jquery.easyui.min.1.9.4.js"></script>
    <script type="text/javascript" src="/assets/common/js/jquery.cookie.1.4.1.js"></script>
    
	<!-- 插件调用 --->
	<script type="text/javascript" src="/assets/common/js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="/assets/common/locale/easyui-lang-zh_CN.js"></script>
	<!-- 基础 JS 调用 -->
	<script type="text/javascript" src="/assets/{{$assets}}/js/src/easyui.base.js"></script>
	<script type="text/javascript" src="/assets/{{$assets}}/js/src/common.menu.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/common.fun.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/common.window.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/common.search.js"></script>
    <!-- 业务js 调用 -->
    <!-- 采购单 -->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.purchase.order.js"></script>
    <!-- 采购订单 -->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.bookingOrders.js"></script>
    <!--    销售订单调用-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.marketSo.js"></script>

    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.purchase.plan.js?u=2"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.purchase.reject.js?u=2"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.supplier.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.sale.reject.orders.js"></script>
    <!-- 销售菜单中客户管理 js调用-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.client.js"></script>
    <!-- 销售订单 -->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.sale.plan.js"></script>
    <!-- 销售 -->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.sale.orders.js"></script>
    <!-- 销售退货申请单-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.sale.reject.apply.js"></script>
    <!--仓库-库存查询-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.store.report.js"></script>
    <!--仓库-库存流水-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.store.report.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.store.inventory.js"></script>
    <!--仓库-库存调拨单-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.store.transfer.js"></script>
    <!--商品-商品列表-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.goods.js"></script>
    <!--上传-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/easyui.upload.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/webuploader.min.js"></script>
    <!-- 会员管理 JS 调用 -->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.member.js?u=1"></script>
    <!--财务--->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.finance.account.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.finance.client.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.finance.supplier.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.settlement.js"></script>
    <!--    报表-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/report.purchase.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/report.sale.js"></script>
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/report.store.js"></script>
     <!-- 角色管理 -->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.role.js"></script>
    <!--员工管理-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.user.js"></script>
    <!--系统参数-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.system.parameter.js"></script>
    <script>

        $(function () {
			// 资源目录
			ASSETS = '{{$assets}}';
			$("<div class=\"datagrid-mask\"></div>").css({
                display: "none",
                position: "absolute",
                width: "100%",
                height: "100%"
            }).appendTo("#Loading");
			$("<div class=\"loading\"></div>").html("<div><img src='/assets/{{$assets}}/img/loading.gif' style='width:80px;height:80px;'></div><div style='font-size:12px;color:#666;'>加载中请稍后……</div>").css({
                position: "relative",
                left: $(window).width() / 2 - $(window).width() * 0.1,
				top: $(window).height() / 2 - 100,
				width: $(window).width() * 0.2
            }).appendTo("#Loading").css({display: "block"});
			setTimeout(function () {
                $("#Loading").fadeOut("normal", function () {
                    $(this).remove();
                });
            }, 300);

			$(window)._unbind(".operation_im")._bind("keydown.operation_im", function(e) {
				// e.stopPropagation();
				//e.preventDefault();
				if (e.keyCode == 112) {
					parent.imTarget.window(parent.imTarget.window('options').closed ? 'open' : 'close');
				}
			});
			$(window)._unbind(".operation_log")._bind("keydown.operation_log", function(e) {
				// e.stopPropagation();
				//e.preventDefault();
				if (e.keyCode == 113) {
					parent.logTarget.window(parent.logTarget.window('options').closed ? 'open' : 'close');
				}
			});
			$(window)._unbind(".operation_all")._bind("keydown.operation_all", function(e) {
				if (e.keyCode == 27) {
					if (!parent.imTarget.window('options').closed) {
						parent.imTarget.window('close');
						return;
					}
					if (!parent.logTarget.window('options').closed) {
						parent.logTarget.window('close');
						return;
					}
				}
			});
        });
	</script>
</head>
<body>
	<div id='Loading' style="position:absolute;z-index:1000;top:0px;left:0px;width:100%;height:100%;background-color:#fff;text-align:center;"></div>
	<!-- 内容区域 start -->
	{__CONTENT__}
	<!-- 内容区域 end -->
</body>
</html>