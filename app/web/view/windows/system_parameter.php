<div class="easyui-layout" data-options="fit:true">
<div id="member_tabs" class="easyui-tabs"
     data-options="
		fit:true,
		border:false,
        tools:'',
		justified:true,
		plain:true,
		onSelect: function(title,index) { /*alert(title);*/ }
	">
    <div title='公司资料' style="padding:10px;"
         data-options="
			footer:'#company_info_footer',
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '20%';
				} else if (w <= 1500 && w > 1100) {
					width = '25%';
				} else if (w <= 1100 && w > 800) {
					width = '33.33%';
				} else if (w <= 800 && w > 440) {
					width = '50%';
				} else {
					width = '100%';
				}
				$('.kbi_column_left_50.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
		">
        <form id="member_base_from" method="post" style="padding-top: 50px">
            <div style="width: auto;text-align: center;margin-bottom: 50px">
               <img src="" id="preview_img" width="200px" height="100px" alt=""">
            </div>
            <div class="form-clo1">
                <div class="name w130">公司Logo:</div>
                <div class="ctl w">
                    <input id="conpany_logo" name="conpany_logo" class="easyui-filebox" value="" data-options="buttonText:'选择',onChange:$.h.systemparameter.lookimg" style="width:75%;"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">公司名称:</div>
                <div class="ctl w">
                    <input id="company_name" name="company_name" class="easyui-textbox" style="width:75%;" data-options="required:true"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">电话:</div>
                <div class="ctl w">
                    <input id="member_iphone" name="member_iphone" class="easyui-numberbox" value="" data-options="required:true" style="width:75%;"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">地址:</div>
                <div class="ctl w">
                    <input id="company_address" name="company_address" class="easyui-textbox" style="width:75%;" data-options="required:true"/>
                </div>
            </div>
        </form>
    </div>
    <div title='系统配置' style="padding:10px;"
         data-options="
			footer:'#system_config_footer',
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '20%';
				} else if (w <= 1500 && w > 1100) {
					width = '25%';
				} else if (w <= 1100 && w > 800) {
					width = '33.33%';
				} else if (w <= 800 && w > 440) {
					width = '50%';
				} else {
					width = '100%';
				}
				$('.kbi_column_left_50.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
		">
        <form id="member_account_from" method="post">

            <div class="form-clo1">
                <div class="name w130">默认库存预警上限:</div>
                <div class="ctl">
                    <input id="prestore_ceilling" name="prestore_ceilling" class="easyui-numberspinner" style="width:100%;" data-options="min:0,precision:0,value:0"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">默认库存预警下限:</div>
                <div class="ctl">
                    <input id="prestore_floor" name="prestore_floor" class="easyui-numberspinner" style="width:100%;" data-options="min:0,precision:0,value:0"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">自动生成货号:</div>
                <div class="ctl w">
                    <input id="create_artno" name = "create_artno" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用',onChange: $.h.systemparameter.hiddennext,checked:true" style="width:100px;height:30px" " >
                </div>
            </div>
            <div id="prefis_name_length">
            <div class="form-clo1">
                <div class="name w130">前缀</div>
                <div class="ctl">
                    <input id="prefis_name" class="easyui-textbox" style="width:100px;height:30px">
                    数字长度<input id="number_length" name="number_length" class="easyui-numberspinner" style="width:25%;" data-options="min:0,precision:0,value:0"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，新增商品时将自动生成货号，货号前缀必须为字母或数字。 示例：</div>
            </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">负库存出售:</div>
                <div class="ctl w">
                    <input id="negative_hand_sale" name = "negative_hand_sale" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置停用后，负库存的商品将不允许出售或调拨。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">开单关联归属销售员:</div>
                <div class="ctl w">
                    <input id="relevance_salesman" name = "relevance_salesman" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，开销售单、退货单选择客户的时候会自动将开单的销售员选择为客户所归属的销售员。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">出入库功能:</div>
                <div class="ctl w">
                    <input id="out_put" name = "out_put" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，销售、采购等业务都会经过出库、入库，并由仓管员统一管理出入库。（注：设置后需要退出重新登陆方才生效）</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">出库单价格展示:</div>
                <div class="ctl w">
                    <input id="price_show" name = "price_show" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，待出库单和出库单将会显示商品的单价和金额(销售单的折后价和折后金额)。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">出库单其他费用为零:</div>
                <div class="ctl w">
                    <input id="other_cost_zero" name = "other_cost_zero" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，销售单中【去出库】、【全部出库】时对应出库单的其他费用均默认为0；停用后，销售单中【去出库】时对应出库单的其他费用与销售单一致，【全部出库】时对应出库单的其他费用默认为0。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">核销功能:</div>
                <div class="ctl w">
                    <input id="cav_function" name = "cav_function" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">核销功能用于客户对账中，开启后可将收款单的金额填补至欠款单据中，将欠款抹平。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">超退提示:</div>
                <div class="ctl w">
                    <input id="super_back" name = "super_back" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，商品退货数大于退货有效期内实际销售数将会弹出提示。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">退货有效期(天)</div>
                <div class="ctl">
                    <input id="sales_return" name="sales_return" class="easyui-numberspinner" style="width:50%;" data-options="min:1,max:90,precision:0,value:1"/>
                (填入1~90整数)
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">访问控制:</div>
                <div class="ctl w">
                    <input id="visit_controll" name = "visit_controll" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，商品退货数大于退货有效期内实际销售数将会弹出提示。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">下游同步价格规则:</div>
                <div class="ctl w" style="margin-top: 4px">
                        <input class="easyui-radiobutton" name="product_price" value="0"  label="单据的商品价格:" data-options="checked:true,labelWidth:115">
                        <input class="easyui-radiobutton" name="zero_sale_price" value="1" labelWidth = 130px label="商品管理的零售价:"">
                        <input class="easyui-radiobutton" name="default_zero" value="2" labelWidth = 65px label="默认为0:">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">库存成本算法:</div>
                <div class="ctl w" style="margin-top: 4px">
                        <input class="easyui-radiobutton" name="product_price" value="0" labelWidth = 115px label="单据的商品价格:" data-options="checked:true"">
                        <input class="easyui-radiobutton" name="zero_sale_price" value="1" labelWidth = 130px label="商品管理的零售价:"">
                        <input class="easyui-radiobutton" name="default_zero" value="2" labelWidth = 65px label="默认为0:">
                </div>
            </div>
        </form>
    </div>
    <div title='收款配置' style="padding:10px;"
         data-options="
			footer:'#gathering_config_footer',
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '20%';
				} else if (w <= 1500 && w > 1100) {
					width = '25%';
				} else if (w <= 1100 && w > 800) {
					width = '33.33%';
				} else if (w <= 800 && w > 440) {
					width = '50%';
				} else {
					width = '100%';
				}
				$('.kbi_column_left_50.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
		">
        <form id="member_account_from" method="post">

            <div class="form-clo1">
                <div class="name w150">微信/支付宝收款默认账户:</div>
                <div class="ctl">
                    <input id="prestore_ceilling" name="prestore_ceilling" class="easyui-numberspinner" style="width:100%;" data-options="min:0,precision:0,value:0"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w150">微信/支付宝收款默认账目类型:</div>
                <div class="ctl">
                    <input id="prestore_floor" name="prestore_floor" class="easyui-numberspinner" style="width:100%;" data-options="min:0,precision:0,value:0"/>
                </div>
            </div>

            <div class="form-clo1">
                <div class="name w150">电子小票支持在线支付:</div>
                <div class="ctl w">
                    <input id="pay_set" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w150"></div>
                <div class="ctl w" style="color: #9d9d9d">
                    支持销售单电子小票分享或纸质小票二维码扫码后在线支付，付款记录的结算账户和账目类型记录与对应单据相同。
                </div>
            </div>
        </form>
    </div>
    <div title='用户配置' style="padding:10px;"
         data-options="
			footer:'#guser_config_footer',
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '20%';
				} else if (w <= 1500 && w > 1100) {
					width = '25%';
				} else if (w <= 1100 && w > 800) {
					width = '33.33%';
				} else if (w <= 800 && w > 440) {
					width = '50%';
				} else {
					width = '100%';
				}
				$('.kbi_column_left_50.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
		">
        <form id="member_account_from" method="post">
            <div class="form-clo1">
                <div class="name w130">实收\实付为零:</div>
                <div class="ctl w">
                    <input id="set_is_zero" name = "set_is_zero" class="easyui-switchbutton" data-options="onText:'开启',offText:'停用'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">设置启用后，开单页面实收(付)金额将默认为零。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">销售开单默认价格:</div>
                <div class="ctl w">
                    <input id="default_sale_price" name = "default_sale_price" class="easyui-switchbutton" data-options="onText:'批发价',offText:'零售价'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">在销售单或销售退货单中，商品初始化价格默认为设置的批发价或零售价。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">销售单默认上次价格:</div>
                <div class="ctl w">
                    <input id="default_last_sale_price" name = "default_last_sale_price" class="easyui-switchbutton" data-options="onText:'批发价',offText:'零售价'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">启用后，销售单及销售退货单的商品价格将默认设置为同个客户上次的出售价格。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">采购单默认上次价格:</div>
                <div class="ctl w">
                    <input id="purchase_last_price" name = "purchase_last_price" class="easyui-switchbutton" data-options="onText:'批发价',offText:'零售价'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130"></div>
                <div class="ctl" style="color: #9d9d9d">启用后，采购单及采购退货单的商品价格将默认设置为同个供应商上次的采购价格。</div>
            </div>
            <div class="form-clo1">
                <div class="name w130">在线充值通知:</div>
                <div class="ctl w">
                    <input id="line_pay_message" name = "line_pay_message" class="easyui-switchbutton" data-options="onText:'批发价',offText:'零售价'" style="width:100px;height:30px">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w130">默认收款方式:</div>
                <div class="ctl w" style="margin-top: 4px">
                    <div data-toggle="topjui-radio">
                        <input class="easyui-radiobutton" name="product_price" value="0" labelWidth = 130px  label="默认上次收款方式:" data-options="checked:true"">
                        <input class="easyui-radiobutton" name="zero_sale_price" value="1" labelWidth = 70px label="扫码收款:"">
                        <input class="easyui-radiobutton" name="default_zero" value="2" labelWidth = 70px label="现金收款:">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="company_info_footer" style="height:40px;padding:5px;text-align:right;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">保存</a>
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">重置</a>
</div>
<div id="system_config_footer" style="height:40px;padding:5px;text-align:right;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">保存</a>
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">重置</a>
</div>
<div id="gathering_config_footer" style="height:40px;padding:5px;text-align:right;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">保存</a>
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">重置</a>
</div>
<div id="guser_config_footer" style="height:40px;padding:5px;text-align:right;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">保存</a>
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">重置</a>
</div>
</div>