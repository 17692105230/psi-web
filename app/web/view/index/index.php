<!DOCTYPE html public "-//w3c//dtd xhtml 1.0 transitional//en" "http://www.w3.org/tr/xhtml1/dtd/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <title>进销存系统</title>
    <link rel="stylesheet" type="text/css" href="/assets/common/css/themes/gray/easyui.css" />
	<link rel="stylesheet" type="text/css" href="/assets/common/css/themes/gray/menu.css" />
    <link rel="stylesheet" type="text/css" href="/assets/common/css/themes/icon.css" />
	<link rel="stylesheet" type="text/css" href="/assets/{{$assets}}/css/jitnry.css" />
    <script type="text/javascript" src="/assets/common/js/jquery.min.1.9.4.js"></script>
	<script type="text/javascript" src="/assets/common/js/jquery.easyui.min.1.9.4.js"></script>
    <script type="text/javascript" src="/assets/common/js/jquery.cookie.1.4.1.js"></script>
	<!-- 插件调用 --->
	<script type="text/javascript" src="/assets/common/js/datagrid-detailview.js"></script>
	<script type="text/javascript" src="/assets/common/locale/easyui-lang-zh_CN.js"></script>
	<!-- 基础 JS 调用 -->
	<script type="text/javascript" src="/assets/{{$assets}}/js/src/easyui.base.js"></script>
	<script type="text/javascript" src="/assets/{{$assets}}/js/src/common.menu.js"></script>
	<script type="text/javascript" src="/assets/{{$assets}}/js/src/common.window.js"></script>
    <!--系统参数-->
    <script type="text/javascript" src="/assets/{{$assets}}/js/src/operation.system.parameter.js"></script>
	<script type="text/javascript">
        var oltTarget, logTarget, logGridTarget, imTarget;
        var num = 0;
        var iCount;
		// 资源目录
		ASSETS = '{{$assets}}';
        $(function () {
            oltTarget = $("#operation_log_txt");
            logTarget = $("#operation_log");
            logGridTarget = $("#operation_log_grid");

			imTarget = $("#operation_im");

            /* 获得窗口大小 */
            function onResize() {
                logTarget.window({
                    left:$(window).width() - 702,
                    top:$(window).height() - 425,
                    closed:true
                });
				imTarget.window({
					left:$(window).width() - 335,
                    top:$(window).height() - 612,
                    closed:true
				});
				$(window)._unbind(".operation_im")._bind("keydown.operation_im", function(e) {
					// e.stopPropagation();
					//e.preventDefault();
					if (e.keyCode == 112) {
						imTarget.window(imTarget.window('options').closed ? 'open' : 'close');
					}
				});
				$(window)._unbind(".operation_log")._bind("keydown.operation_log", function(e) {
					// e.stopPropagation();
					//e.preventDefault();
					if (e.keyCode == 113) {
						logTarget.window(logTarget.window('options').closed ? 'open' : 'close');
					}
				});
				$(window)._unbind(".operation_all")._bind("keydown.operation_all", function(e) {
					// console.log(e.keyCode);
					if (e.keyCode == 27) {
						console.log(imTarget.window('options').closed);
						console.log(logTarget.window('options').closed);
						if (!imTarget.window('options').closed) {
							imTarget.window('close');
							return;
						}
						if (!logTarget.window('options').closed) {
							logTarget.window('close');
							return;
						}
					}
				});
				
            };
            window.onresize = onResize;
            onResize();
            
            /*
            $(document).unbind('.commoditygrid').bind('mousedown.commoditygrid', function(e){
                var p = $(e.target).closest('table.datagrid-btable,div.combo-panel');
                if (p.length) { return; }
            });
            */

			$('#main_tabs').tabs('update', {
				tab: $('#main_tabs').tabs('getTab', 0),
				options: {
					id: 'SysMain',
					title: '新手导航'
				}
			});

			$('#main_iframe').attr('src', $.toUrl('index', 'main'));

            //$.h.index.setOperateInfo(null, false);
			
			$("<div class=\"datagrid-mask\"></div>").css({
                display: "none",
                position: "absolute",
                width: "100%",
                height: "100%"
            }).appendTo("#Loading");
			$("<div class=\"loading\"></div>").html("<div><img src='/assets/{{$assets}}/img/loading.gif' style='width:80px;height:80px;'></div><div style='font-size:12px;color:#666;'>正在加载主框架数据……</div>").css({
                position: "relative",
                left: $(window).width() / 2 - $(window).width() * 0.1,
				top: $(window).height() / 2 - 100,
				width: $(window).width() * 0.2
            }).appendTo("#Loading").css({display: "block"});
			setTimeout(function () {
                $("#Loading").fadeOut("normal", function () {
                    $(this).remove();
                });
            }, 800);
        });
        
        $.h.index = {
            refresh : function(e) {
                var objMainTabs = $('#main_tabs');
                var objMainTabsOptions = objMainTabs.tabs('getSelected').panel('options');
                if (objMainTabs.tabs('getTabIndex',objMainTabs.tabs('getSelected')) == 0) {
                    $('#main_iframe').attr('src',$('#main_iframe').attr('src'));
                    return;
                }
                objMainTabs.tabs('update', {
                    tab: objMainTabs.tabs('getSelected'),
                    options: {
                        title: objMainTabsOptions.title,
                        href: objMainTabsOptions.href
                    }
                });
                objMainTabs.tabs('getSelected').panel('refresh');
            },
            onCloseAll : function() {
                var objTabs = $('#main_tabs');
                var mTabs = objTabs.tabs('tabs');
                for (var i = 0; i < mTabs.length; i++) {
                    if (i == 0) continue;
                    objTabs.tabs("close", i);
                }
            },
            sendMainMsg : function(msg) {
                //if ($("#main_msg").is(":hidden")) {
				if (msg) {
                    $('#main_msg').html(msg);
					//$('#main_msg').offset({top:0,left:((document.documentElement.clientWidth || document.body.clientWidth) / 2 - ($('#main_msg').width() / 2))});
                    $("#main_msg").fadeIn(500);
                } else {
					$("#main_msg").fadeOut(500, function() {
						$('#main_msg').html('');
						//$('#main_msg').offset({top:0,left:0});
					});
                }
            },
            /**
             * 如果 info 为 null 并且 b 为 false，只读取数组中第一条记录并显示
             * 如果 info 不是 null 并且 b 为 false，只显示信息，不写入数组中
             * 如果 info 不是 null 并且 b 为 true，显示信息，并写入数组中
             */
            setOperateInfo : function(info, b) {
                if (info == null || b) {
                    var mCookieData = $.cookie('SysOperateInfo');
                    if (!mCookieData) {
                        mCookieData = '[]';
                    }
                    mCookieData = JSON.parse(mCookieData);
                    
                    if (mCookieData.length >= 10) {
                        mCookieData.splice(9, 1);
                    }
                    /* 为 true 时，写入 Cookie */
                    if (b) {
                        info.id = mCookieData.length;
                        info.create_time = (new Date()).Format("yyyy-MM-dd hh:mm:ss");
                        mCookieData.unshift(info);
                        $.cookie('SysOperateInfo',JSON.stringify(mCookieData));
                    }
                    if (info == null) {
                        if (mCookieData.length == 0) return;
                        info = mCookieData[0];
                    }
                    
                    if (!logTarget.window('options').closed) {
                        logGridTarget.datagrid('loadData', mCookieData);
                    }
                }
                
                var strHtml = '<strong>【{R_Module}】【{R_Operate}】：</strong>{R_Content}';
                strHtml = strHtml.replace('{R_Module}',info.module).replace('{R_Operate}',info.operate).replace('{R_Content}',info.content);
                $(oltTarget.children()[0]).html(strHtml);
                
                clearInterval(iCount);
				
                if (info.icon == 'hr-warn' || info.icon == 'hr-error' || info.icon == 'hr-ok' || info.icon == 'icon-ok') {
                    this.sendMainMsg(strHtml);
                    iCount = window.setInterval(txtTwinkle, 1300);
                }

                if (!$(oltTarget.children()[1]).hasClass(info.icon)) {
                    strHtml = "l-btn-icon " + info.icon;
                    $(oltTarget.children()[1]).attr("class","l-btn-icon " + info.icon);
                }
            },
            abc:function(){
                console.log('abcdefghijklmn');
            },
        };
        function txtTwinkle() {
            num++;
            if (num == 6) {
                num = 0;
                $.h.index.sendMainMsg(null);
                clearInterval(iCount);
            } else {
                setTimeout("oltTarget.css('color','blue')",500);
                setTimeout("oltTarget.css('color','#000')",800);
            }
        }
        // 退出登录
        function logout(){
            $.ajax({
                url: '/web/passport/logout',
                type:'get',
                success: function(data) {
                    if (data.errcode == 0) {
                        $(location).attr('href', data.data.url);
                    } else {
                        $.messager.alert('提示', data.errmsg);
                    }
                }
            });
        }
	</script>
</head>
<body>

<div id='Loading' style="position:absolute;z-index:1000;top:0px;left:0px;width:100%;height:100%;background-color:#fff;text-align:center;"></div>
<div id="main_msg" style="display:none;position:absolute;z-index:1000;top:0;left:50%;transform:translate(-50%,0);height:40px;line-height:30px;font-size:14px;color:#fff;padding:5px 20px;border-radius:0 0 .5em .5em;background-color:rgba(0,0,0,0.5)"></div>
<div class="easyui-layout" data-options="fit:true">
	<div data-options="region:'north',border:false" style="display:flex;justify-content:space-between;height:60px;line-height:60px;overflow:hidden;background-image:linear-gradient(90deg, #dee, #fff);">
		<div style="display:flex;align-items:center;">
			<div slot="header-logo" style="float:left;width:180px;">
				<img src="/assets/{{$assets}}/img/logo.png" style="margin-left: 20px;height: 30px;">
			</div>
			<div class="index-menu-top-hover">
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuSysMain();" data-options="iconCls:'hr-database',iconAlign:'top',width:60,plain:false">首页</a>
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuClientListMain();" data-options="iconCls:'hr-cart',iconAlign:'top',width:75,plain:true">客户管理</a>
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuGooldsListMain();" data-options="iconCls:'hr-user',iconAlign:'top',width:75,plain:true">商品管理</a>
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuStoreListMain();" data-options="iconCls:'hr-law',iconAlign:'top',width:75,plain:true">库存管理</a>
				<span style="color:#E7E7E7;height:50px;margin: 0 5px;border-left: 1px solid #ddd;border-right: 1px solid #fff;"></span>
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuNodeListMain();" data-options="iconCls:'hr-admin',iconAlign:'top',width:75,plain:true">权限管理</a>
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuRoleListMain();" data-options="iconCls:'hr-076',iconAlign:'top',width:75,plain:true">角色管理</a>
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuModuleListMain();" data-options="iconCls:'hr-user4add',iconAlign:'top',width:75,plain:true">权限页面</a>
				<a href="#" class="easyui-linkbutton" onclick="javascript:$.h.menu.onMenuCellEditingMain();" data-options="iconCls:'hr-imageadd',iconAlign:'top',width:85,plain:true">单元格编辑</a>
				<span style="color:#E7E7E7;height:55px;margin: 0 5px;border-left: 1px solid #ddd;border-right: 1px solid #fff;"></span>
<!--				<a href="#" class="easyui-linkbutton" data-options="iconCls:'hr-flag2add',iconAlign:'top',width:75,plain:true">咨询坐席</a>-->
<!--				<a href="#" class="easyui-linkbutton" data-options="iconCls:'hr-turn-on',iconAlign:'top',width:75,plain:true">咨询外线</a>-->
<!--				<a href="#" class="easyui-linkbutton" data-options="iconCls:'hr-creditcards',iconAlign:'top',width:75,plain:true">转接</a>-->
<!--				<a href="#" class="easyui-linkbutton" data-options="iconCls:'hr-report_user',iconAlign:'top',width:75,plain:true">三方</a>-->
<!--				<a href="#" class="easyui-linkbutton" data-options="iconCls:'hr-report_link',iconAlign:'top',width:75,plain:true">三方接回</a>-->
			</div>
		</div>
		<div style="display:flex;align-items:center;margin-right:5px;">
			<a href="javascript:void(0)" class="easyui-menubutton" data-options="menu:'#sysButton',height:50,hasDownArrow:false">系统设置</a>
			<div id="sysButton" style="width:150px;">
				<div data-options="iconCls:'hr-loading'">组织机构</div>
				<div class="menu-sep"></div>
				<div data-options="iconCls:'icon-organisation'">权限管理</div>
				<div data-options="iconCls:'icon-bolt'">版本管理</div>
			</div>
			<span style="color:#E7E7E7;height:20px;margin: 0 5px;border-left: 1px solid #ccc;border-right: 1px solid #fff;"></span>
			<a href="javascript:void(0)" class="easyui-menubutton" data-options="menu:'#meButton',iconCls:'hr-settings',height:50,menuAlign:'right'">王二小</a>
			<div id="meButton" style="width:150px;">
				<div data-options="iconCls:'hr-view'">张三丰</div>
				<div class="menu-sep"></div>
				<div onclick="logout()" data-options="iconCls:'hr-warn'">退出</div>
			</div>
		</div>
	</div>
	<div data-options="region:'west',split:true,hideCollapsedContent:false,collapsed:false,title:'菜单',dataType:'json',width:160,minWidth:160,maxWidth:220">
		<div class="easyui-menu" data-options="inline:true,fit:true,itemHeight:40" style="border:0px;">
            <div id="caigou" data-options="iconCls:'icon-goods-purchase'">
                <span>采购</span>
                <div style="width:180px;">
                    <div onclick="$.h.menu.onMenuPurchaseOrder('#caigou')" data-options="iconCls:'icon-purchase-order'">采购单</div>
                    <div onclick="javascript:$.h.menu.onMenuSupplierListMain('#caigou');" data-options="iconCls:'icon-supplier-manage'">供应商管理</div>
                    <div onclick="javascript:$.h.menu.onMenuBookingOrderListMain('#caigou');;" data-options="iconCls:'icon-purchase-plan'" >采购订单</div>
                    <div onclick="javascript:$.h.menu.onMenuPurchaseReject('#caigou');" data-options="iconCls:'icon-purchase-return'">采购退货单</div>
                </div>
            </div>
            <div id="xiaoshou" data-options="iconCls:'icon-sale-manage'">
                <span>销售</span>
                <div style="width:180px;">
                    <div onclick="javascript:$.h.menu.onMenuMarketSoMain('#xiaoshou');" data-options="iconCls:'icon-sale-plan'">销售订单</div>
                    <div onclick="javascript:$.h.menu.onMenuSalesTicketMain('#xiaoshou');"data-options="iconCls:'icon-sale-order'">销售单</div>
                    <div onclick="javascript:$.h.menu.onMenuSaleRejectApply('#xiaoshou');" data-options="iconCls:'icon-sale-reject'">销售退货申请</div>
                    <div onclick="javascript:$.h.menu.onMenuSaleRejectOrders('#xiaoshou');"  data-options="iconCls:'icon-sale-return'">销售退货单</div>
                </div>
            </div>
            <div id="kehu" data-options="iconCls:'icon-client-manage'">
                <span>客户</span>
                <div style="width:180px;">
                    <div onclick="$.h.menu.onMenuClientListMain('#kehu')" data-options="iconCls:'icon-client-list'"><b>客户管理</b></div>
                    <div onclick="$.h.window.coustomerClassifyWin.onOpen()" data-options="iconCls:'icon-client-category'">客户类别</div>
                </div>
            </div>
            <div id="cangku" data-options="iconCls:'icon-store-manage'">
                <span>仓库</span>
                <div style="width: 180px;">
                    <div onclick="javascript:$.h.menu.onMenuDepotQuery('#cangku');" data-options="iconCls:'icon-store-list'">库存查询</div>
                    <div onclick="javascript:$.h.menu.onMenuDepotAccount('#cangku');" data-options="iconCls:'icon-store-inventory'">库存流水</div>
                    <div class="menu-sep"></div>
                    <div onclick="javascript:$.h.menu.onMenuDepotAllotted('#cangku');" data-options="iconCls:'icon-store-transfer'">库存调拨单</div>
                    <div onclick="javascript:$.h.menu.onMenuDepotInventory('#cangku')" data-options="iconCls:'icon-store-record'">库存盘点单</div>
                </div>
            </div>
            <div id="baobiao" data-options="iconCls:'icon-report-manage'">
                <span>报表</span>
                <div style="width: 180px;">
                    <div onclick="javascript:$.h.menu.onMenuReportPurchase('#baobiao');" data-options="iconCls:'icon-report-purchase'">采购报表</div>
                    <div onclick="javascript:$.h.menu.onMenuReportSale('#baobiao');" data-options="iconCls:'icon-report-sale'">销售报表</div>
                    <div class="menu-sep"></div>
                    <div onclick="javascript:$.h.menu.onMenuReportStore('#baobiao');" data-options="iconCls:'icon-report-store'">盘点报表</div>
                    <div onclick="javascript:$.h.menu.onMenuReportTransfer('#baobiao');" data-options="iconCls:'icon-report-transfer'">调拨报表</div>
                </div>
            </div>
            <div id="caiwu" data-options="iconCls:'icon-finance-manage'">
                <span>财务</span>
                <div style="width: 180px;">
                    <div  onclick="javascript:$.h.menu.onMenuFinanceAccount('#caiwu');" data-options="iconCls:'icon-finance-account'">账户流水</div>
                    <div onclick="javascript:$.h.menu.onMenuFinanceClient('#caiwu');" data-options="iconCls:'icon-finance-client'">客户对账</div>
                    <div onclick="javascript:$.h.menu.onMenuFinanceSupplier('#caiwu');" data-options="iconCls:'icon-finance-supplier'">供应商对账</div>
                    <div class="menu-sep"></div>
                    <div onclick="javascript:$.h.menu.onMenuSettlement('#caiwu');" data-options="iconCls:'icon-settlement'">结算账户</div>
                    <div onclick="javascript:$.h.window.winBaseAccountType.onOpen();" data-options="iconCls:'icon-account-type'">账目类型</div>
                </div>
            </div>
            <div id='hysz' onclick="javascript:$.h.menu.onMenuMember('#hysz');" data-options="iconCls:'icon-vip'">
                <span><font color="#32cd32" >会员设置</font></span>
            </div>
            <div id='splb' onclick="javascript:$.h.menu.onMenuGooldsListMain('#splb');" data-options="iconCls:'icon-goods-list'">
                <span>商品列表</span>
            </div>
            <div id="spgl" data-options="iconCls:'icon-goods-manager'">
                <span>商品管理</span>
                <div style="width:180px;">
                    <div onclick="javascript:$.h.window.winCategoryList.onOpen();" data-options="iconCls:'icon-goods-classify'">商品分类</div>
                    <div onclick="javascript:$.h.window.winBranchList.onOpen();" data-options="iconCls:'icon-goods-grand'">品牌管理</div>
                    <div onclick="javascript:$.h.window.winColorList.onOpen();" data-options="iconCls:'icon-goods-color'">颜色管理</div>
                    <div class="menu-sep"></div>
                    <div onclick="javascript:$.h.window.winMaterialList.onOpen();"  data-options="iconCls:'icon-goods-material'">材质管理</div>
                    <div onclick="javascript:$.h.window.winUnitList.onOpen();" data-options="iconCls:'icon-goods-company'">单位管理</div>
                    <div onclick="javascript:$.h.window.winBaseSize.onOpen()" data-options="iconCls:'icon-goods-size'">尺寸管理</div>
                    <div class="menu-sep"></div>
                    <div onclick="javascript:$.h.window.winBaseStyle.onOpen()" data-options="iconCls:'icon-goods-style'">款式管理</div>
                    <div onclick="javascript:$.h.window.winBaseSeason.onOpen()" data-options="iconCls:'icon-goods-season'">季节管理</div>
                    <div onclick="javascript:$.h.window.winBaseBarcode.onOpen()" data-options="iconCls:'icon-goods-barcode'">条码管理</div>
                </div>
            </div>
            <div id="rygl" data-options="iconCls:'icon-person-manage'">
                <span>人员管理</span>
                <div style="width:180px;">
                    <div onclick="javascript:$.h.menu.onMenuUser('#rygl');" data-options="iconCls:'icon-user-manage'">员工管理</div>
                    <div onclick="javascript:$.h.window.winBaseOrg.onOpen();" data-options="iconCls:'icon-organization'">组织机构</div>
                    <div onclick="javascript:$.h.menu.onMenuRole('#rygl');" data-options="iconCls:'icon-user-role'">角色管理</div>
                </div>
            </div>
            <div id="wlsz" onclick="javascript:$.h.menu.onMenuLogistics('#wlsz');" data-options="iconCls:'icon-logistics'">
                <span onclick="javascript:;">物流设置</span>
            </div>
            <div id="xtsz" data-options="iconCls:'icon-system-settings'">
                <span>系统设置</span>
                <div style="width:180px;">
                    <div onclick="javascript:$.h.menu.onMenuCompanyReport('#xtsz');" data-options="iconCls:'icon-send-report'"><b>发送报告</b></div>
                    <div class="menu-sep"></div>
                    <div onclick="javascript:$.h.window.winBaseSystemParameter.onOpen();" data-options="iconCls:'icon-system-parameter'">系统参数</div>
                    <div onclick="javascript:$.h.menu.onMenuSystemLog('#xtsz');" data-options="iconCls:'icon-system-log'">系统日志</div>
                    <div onclick="javascript:$.h.menu.onMenuTheNew('#xtsz');" data-options="iconCls:'icon-new-introduction'">快速入门</div>
                    <div onclick="javascript:$.h.window.SystemAlert('#xtsz')" data-options="iconCls:'icon-system-reset'">系统重置</div>
                </div>
            </div>
            <div data-options="iconCls:'icon-print-template'">
                <span onclick="javascript:;">打印设置</span>
            </div>
            <div id='grsz' onclick="javascript:$.h.window.winBaseUserInfo.onOpen();" data-options="iconCls:'icon-user-info'">
                <span onclick="javascript:;">个人信息</span>
            </div>
        </div>
	</div>
	<div data-options="region:'center',border:false" style="overflow:hidden;">
		<div id="main_tabs" class="easyui-tabs" data-options="fit:true,border:true,tools:'#tab-tools',tabWidth:120">
			<div style="overflow:hidden;padding:4px;">
				<iframe id="main_iframe" style="width:100%;height:100%;border:0px;overflow:hidden;"></iframe>
			</div>
			<!--div title="客户管理" style="overflow:hidden;padding:4px;" data-options="id:'ClientListMain1',closable:true,dataType:'json',href:'/{{$assets}}/client/client_list_main'"></div>
			<div title="添加客户" style="overflow:hidden;padding:4px;" data-options="id:'ClientEditMain2',closable:true,dataType:'json',href:'/{{$assets}}/client/client_edit_main'"></div>
			<div title="业务受理" style="overflow:hidden;padding:4px;" data-options="id:'ClientEditMain3',closable:true,selected:true,dataType:'json',href:'/{{$assets}}/work/work_accept_main'"></div-->
		</div>
		<div id="tab-tools" style="border-right:0px;">
			<a class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-clear',onClick:$.h.index.onCloseAll" title="关闭所有"></a>
			<a class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-reload',onClick:$.h.index.refresh" title="刷新当前"></a>
		</div>
	</div>
	<div data-options="region:'east',split:true,hideCollapsedContent:false,collapsed:true,title:'快捷',dataType:'json',href:'/{{$assets}}/index/quick_menu'" style="width:100px;min-width:100px;"></div>
    <div data-options="region:'south'" style="height:30px;overflow:hidden;">
    	<div style="float:left;line-height:30px;width:50%;">
			<!--<a href="javascript:void(0)" class="hr-award" style="padding:16px;" title="公司名称"></a>
			<span>坐席号：1001</span>
			<span style="margin:0 5px;color:#eee;">|</span>
			<span>分机号：8499</span>
			<span style="margin:0 5px;color:#ddd;">|</span>
			<span>坐席状态：未签入</span>
			<span style="margin:0 5px;color:#ddd;">|</span>
			<span>队列状态：未签入</span>-->
		</div>
        <div style="float:right;line-height:30px;padding:1px;">
            <a id='btn_menu' class="easyui-linkbutton" data-options="iconCls:'hr-menu',plain:true,height:26,
                onClick:function() {
                    logTarget.window(logTarget.window('options').closed ? 'open' : 'close');
                }"></a>
        </div>
		<div style="float:right;line-height:30px;padding:1px;">
            <a id='btn_im' class="easyui-linkbutton" data-options="iconCls:'hr-admin',plain:true,height:26,
                onClick:function() {
                    imTarget.window(imTarget.window('options').closed ? 'open' : 'close');
                }"></a>
        </div>
        <div style="float:right;line-height:30px;">
            <span id="operation_log_txt" class="l-btn-left l-btn-icon-left"><span class="l-btn-text"></span></span>
        </div>
    </div>
</div>
<!-- 操作日志 -->
<div id="operation_log" class="easyui-window" style="width:700px;height:392px;"
    data-options="
        title:'操作日志',
        modal:false,
        closed:true,
        collapsible:false,
        minimizable:false,
        maximizable:false,
        resizable:false,
        draggable:false,
        noheader:false,
        onOpen:function() {
            $('#btn_menu').linkbutton('select');
            var mCookieData = $.cookie('SysOperateInfo');
            if (mCookieData) {
                mCookieData = JSON.parse(mCookieData);
                logGridTarget.datagrid('loadData', mCookieData);
            }
        },
        onClose:function() {
            $('#btn_menu').linkbutton('unselect');
        }">
    <table id="operation_log_grid" class="easyui-datagrid" style="width:100%;" title=""
			data-options="
				fit:true,
				fitColumns:true,
				rownumbers:true,
				iconCls:'hr-record',
				singleSelect:true,
				rownumbers:true,
				border:false,
				method:'get',
				checkOnSelect:false,
				selectOnCheck:false
			">
			<thead>
				<tr>
                    <th data-options="field:'id',hidden:true"></th>
                    <th data-options="
                            field:'icon',
                            align:'center',
                            formatter:function(value,row,index) {
                                switch(value) {
                                    case 'hr-warn':
                                        return '<img src=/assets/common/css/themes/icons/warn.png />';
                                    case 'hr-error':
                                        return '<img src=/assets/common/css/themes/icons/error.png />';
                                    case 'icon-ok':
                                        return '<img src=/assets/common/css/themes/icons/ok.png />';
                                    case 'icon-save':
                                        return '<img src=/assets/common/css/themes/icons/filesave.png />';
                                }
                            }
                        ">&nbsp;&nbsp;&nbsp;&nbsp;</th>
					<th data-options="field:'module',align:'center',width:55"><b>功能</b></th>
					<th data-options="field:'operate',align:'center',width:55"><b>操作</b></th>
					<th data-options="field:'content',width:140"><b>说明</b></th>
                    <th data-options="field:'create_time',align:'center',formatter:function(value,row,index) {
					    if (!value) {
					      return  '';
					    }
					    return value;
					}">
                        <b>时间</b>
                    </th>
				</tr>
			</thead>
		</table>
</div>
<!-- 通讯录 -->
<div id="operation_im" class="easyui-window" style="width:300px;height:580px;overflow-x:hidden;overflow-y:visible;:vertical"
    data-options="
		iconCls:'hr-admin',
        title:'张志超',
        modal:false,
        closed:true,
		plain:true,
        collapsible:false,
        minimizable:false,
        maximizable:false,
        resizable:false,
        draggable:false,
        noheader:false,
		footer:'#operation_im_footer',
        onOpen:function() {
            $('#btn_im').linkbutton('select');
            var mCookieData = $.cookie('SysOperateInfo');
            if (mCookieData) {
                mCookieData = JSON.parse(mCookieData);
                logGridTarget.datagrid('loadData', mCookieData);
            }
        },
        onClose:function() {
            $('#btn_menu').linkbutton('unselect');
			$('#btn_im').linkbutton('unselect');
        }">
	<div class="easyui-tabs" data-options="tabHeight:50,border:false,justified:true">
        <div title="<span style='display:inline-block;line-height:12px;padding-top:12px;'><img style='border:0;width:28px;height:28px;' src='/assets/web/img/im_friend.png'/></span>">
			<div class="easyui-accordion" data-options="multiple:false,border:false" style="margin-top:5px;width:100%;height:443px;">
				<div title="电商事业部" data-options="iconCls:'icon-ok'" style="overflow:auto;">
					<ul class="easyui-tree">
						<li>
							<span>My Documents</span>
							<ul>
								<li data-options="state:'closed'">
									<span>Photos</span>
									<ul>
										<li>
											<span>Friend</span>
										</li>
										<li>
											<span>Wife</span>
										</li>
										<li>
											<span>Company</span>
										</li>
									</ul>
								</li>
								<li>
									<span>Program Files</span>
									<ul>
										<li>Intel</li>
										<li>Java</li>
										<li>Microsoft Office</li>
										<li>Games</li>
									</ul>
								</li>
								<li>index.html</li>
								<li>about.html</li>
								<li>welcome.html</li>
							</ul>
						</li>
					</ul>
				</div>
				<div title="技术服务部" style="padding:10px;">

				</div>
				<div title="行政人事部" style="padding:10px;">
					<p>C# is a multi-paradigm programming language encompassing strong typing, imperative, declarative, functional, generic, object-oriented (class-based), and component-oriented programming disciplines.</p>
				</div>
				<div title="财务法务部" style="padding:10px;">
					<p>A dynamic, reflective, general-purpose object-oriented programming language.</p>
				</div>
				<div title="分公司" style="padding:10px;">
					<p>Fortran (previously FORTRAN) is a general-purpose, imperative programming language that is especially suited to numeric computation and scientific computing.</p>
				</div>
			</div>
        </div>
        <div title="<span style='display:inline-block;line-height:12px;padding-top:12px;'><img style='border:0;width:28px;height:28px;' src='/assets/web/img/im_group.png'/></span>" style="padding:10px">
            
        </div>
		<div title="<span style='display:inline-block;line-height:12px;padding-top:12px;'><img style='border:0;width:28px;height:28px;' src='/assets/web/img/im_msg.png'/></span>" style="padding:10px">
            
        </div>
    </div>
	<div id="operation_im_footer" style="padding:0 2px;height:40px;display:flex;justify-content:space-between;align-items:center;">
		<div style="">
			<a href="#" class="easyui-linkbutton" data-options="plain:true,iconCls:'hr-settings'"></a>
		</div>
		<div style="text-align:right;">
			<a href="#" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-search'"></a>
			<a href="#" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add'"></a>
			<a href="#" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-sum'"></a>
			<a href="#" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-organisation'"></a>
		</div>
	</div>
</div>
<!-- Demo窗口 -->
<div id="win_base_demo" class="easyui-window" style="padding:5px;width:780px;height:400px;"
    data-options="title:'Demo窗口',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!-- Demo1窗口 -->
<div id="win_base_demo1" class="easyui-window" style="padding:5px;width:700px;height:650px;overflow:hidden;"
    data-options="title:'Demo1窗口',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!-- Demo2窗口 -->
<div id="win_base_demo2" class="easyui-window" style="padding:5px;width:700px;height:390px;overflow-y:hidden;"
    data-options="title:'Demo2窗口',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>


<!-- 客户分类窗口 -->
<div id="client_customer_classify_win" class="easyui-window" style="padding:5px;width:780px;height:400px;overflow-y:hidden;"
     data-options="title:'用户分类',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!-- 颜色列表窗口 -->
<div id="win_color_list" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'颜色管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!-- 材质列表窗口 -->
<div id="win_material_list" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'材质管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!-- 商品分类窗口 -->
<div id="win_category_list" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'商品分类',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!-- 单位分类窗口 -->
<div id="win_unit_list" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'单位管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!-- 品牌分类窗口 -->
<div id="win_brand_list" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'品牌管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true">
</div>

<!-- 尺寸管理窗口 -->
<div id="win_base_size" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'尺寸管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!--条码管理窗口-->
<div id="win_base_barcode" class="easyui-window" style="padding:5px;width:700px;height:650px;overflow:hidden;"
     data-options="title:'条码管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!--季节管理窗口-->
<div id="win_base_season" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'季节管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
<!--款式管理窗口-->
<div id="win_base_style" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'款式管理',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>
<!--账目类型窗口-->
<div id="win_base_account_type" class="easyui-window" style="padding:5px;width:1000px;height:400px;"
     data-options="title:'账目类型',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>
<!--个人信息窗口-->
<div id="win_base_user_info" class="easyui-window" style="padding:5px;width:450px;height:400px;"
     data-options="title:'个人信息',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>
<!--重置密码-->
<div id="win_base_reset_password" class="easyui-window" style="padding:5px;width:450px;height:400px;"
     data-options="title:'重置密码',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>
<!-- 组织机构管理窗口 -->
<div id="win_base_organization" class="easyui-window" style="padding:5px;width:880px;height:460px;"
     data-options="title:'组织机构',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/><!-- 组织机构管理窗口 -->
<!--系统参数设置窗口-->
<div id="win_base_system_parameter" class="easyui-window" style="padding:5px;width:700px;height:600px;"
     data-options="title:'系统参数',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>
</body>
</html>