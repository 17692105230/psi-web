<div class="easyui-layout" data-options="fit:true">
    <div class="easyui-panel" title="销售单" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:135px;background-color:#eee;"
         data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false,
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '25%';
				} else if (w <= 1500 && w > 1100) {
					width = '25%';
				} else if (w <= 1100 && w > 800) {
					width = '33.33%';
				} else if (w <= 800 && w > 440) {
					width = '50%';
				} else {
					width = '100%';
				}
				$('.kbi_column_left.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
	">
        <header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">销售退货单 - <span id="orders_code_view" style="font-size:10pt;color:blue;"></span></span>
            </div>
            <div id="reject_orders_toolbar" style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.sro.mNewOrders">新建单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',plain:true"></a>
            </div>
        </header>
        <form id="orders_form" method="post">
            <input id="orders_code" name="orders_code" type="hidden" value=""/>
            <div class="form-clo1">
                <div class="name" style="font-weight:bold;color:green">客户:</div>
                <div class="ctl">
                    <div id="client_name" class="name" style="text-align: left"></div>
                </div>
                <div class="name" style="font-weight:bold;color:brack">实付金额:</div>
                <div class="ctl">
                    <div id="orders_rmoney" class="name" style="text-align: left">￥100.00</div>
                </div>
                <div class="name" style="font-weight:bold;color:brack">日期:</div>
                <div class="ctl">
                    <div id="orders_date" class="name" style="text-align: left">1999-01-01</div>
                </div>
                <div class="name" style="font-weight:bold;color:brack">仓库:</div>
                <div class="ctl">
                    <div id="warehouse_name" class="name" style="text-align: left"></div>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name" style="font-weight:bold;color:brack">结算账户:</div>
                <div class="ctl">
                    <div id="settlement_name" class="name" style="text-align: left"></div>
                </div>
                <div class="name" style="font-weight:bold;color:brack">发货方式:</div>
                <div class="ctl">
                    <div id="delivery_name" class="name" style="text-align: left"></div>
                </div>
                <div class="name" style="font-weight:bold;color:brack">备注:</div>
                <div class="ctl">
                    <div id="orders_remark" class="name" style="text-align: left"></div>
                </div>
                <div class="name"></div>
                <div class="ctl"></div>
            </div>

        </form>
    </div>
    <div data-options="region:'center'" style="overflow:hidden;">
        <table id="orders_grid_details" class="easyui-datagrid" style="width:100%;"
               data-options="
                fit:true,
				fitColumns:true,
				rownumbers:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				border:false,
				toolbar:'#orders_grid_details_toolbar',
				footer:'#orders_grid_details_footer',
				method:'get',
                onBeforeLoad:$.h.sro.complete.onBeforeLoad
			">
            <thead>
            <tr>
                <th data-options="field:'goods_id',checkbox:true"></th>
                <th data-options="field:'goods_type',align:'center',formatter:$.h.sro.mButton.formatter">退/销</th>
                <th data-options="field:'goods_name',width:11,sortable:true"><b>商品</b></th>
                <th data-options="field:'goods_barcode',width:7,sortable:true"><b>条码</b></th>
                <th data-options="field:'goods_code',width:7,sortable:true"><b>货号</b></th>
                <th data-options="field:'color_name',width:5,align:'center'"><b>颜色</b></th>
                <th data-options="field:'size_name',width:3,align:'center'"><b>尺码</b></th>
                <th data-options="field:'goods_number',align:'center'">&nbsp;<b>数量</b>&nbsp;</th>
                <th data-options="
							field:'goods_price',
							width:7,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>单价</b></th>
                <th data-options="field:'goods_discount',width:5,align:'right'"><b>折扣</b></th>
                <th data-options="
							field:'goods_daprice',
							width:5,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>折后价</b></th>
                <th data-options="
							field:'goods_tmoney',
							width:7,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>金额</b></th>
                <th data-options="
							field:'goods_tdamoney',
							width:7,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>折后金额</b></th>
            </tr>
            </thead>
        </table>
        <div id="orders_grid_details_toolbar" style="height:28px;padding-top:5px;padding-left:5px;">

        </div>

        <div id="orders_grid_details_footer" style="height:70px;padding:2px 2px;">
            <div style="float:left;height:100%;">
                <table style="width:100%;height:100%;" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;">抹零</td>
                        <td style="text-align:left;">
                            <input id="orders_emoney" class="easyui-numberbox"
                                   data-options="
                                        width:140,
                                        min:0,
                                        max:99999999,
                                        prefix:'￥',
                                        precision:2,
                                        readonly:true,
                                        value:0"/></td>
                        <td colspan="4" style="text-align:left;padding-left:5px;"><div style="color:red;">退销相抵-应<span name="iop_text" style="color:blue">付</span>金额：<span id="orders_rs_money" style="font-weight:bold;">￥0.00</span></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;color:red;">应<span name="iop_text" style="color:blue">付</span>总计</td>
                        <td style="text-align:left;">
                            <input id="orders_pmoney" class="easyui-numberbox"
                                   data-options="
                                        readonly:true,
                                        width:140,
                                        prefix:'￥',
                                        precision:2,
                                        readonly:true,
                                        value:0"/></td>
                        <td colspan="4" style="text-align:left;padding-left:5px;"><div style="color:red;">退销相抵-折后应<span name="iop_text" style="color:blue">付</span>金额：<span id="orders_rsd_money" style="font-weight:bold;">￥0.00</span></div></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="float:right;height:100%;">
                <table style="width:100%;height:100%;" cellspacing="2" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;color:green;padding-right:5px;">销售合计</td>
                        <td style="text-align:right;width:35px;padding-right:5px;color:green;">数量</td>
                        <td style="text-align:left;"><input id="orders_gs_number" class="easyui-numberbox" style="width:60px;" data-options="readonly:true,min:0,max:9999999,precision:0,value:0"/></td>
                        <td style="text-align:right;width:35px;padding-right:5px;color:green;">金额</td>
                        <td style="text-align:left;"><input id="orders_gs_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                        <td style="text-align:right;width:70px;padding-right:5px;color:green;">折后金额</td>
                        <td style="text-align:left;"><input id="orders_gsd_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;color:red;">退货合计</td>
                        <td style="text-align:right;width:35px;font-weight:bold;padding-right:5px;color:red;">数量</td>
                        <td style="text-align:left;"><input id="orders_gr_number" class="easyui-numberbox" style="width:60px;" data-options="readonly:true,min:0,max:9999999,precision:0,value:0"/></td>
                        <td style="text-align:right;width:35px;font-weight:bold;padding-right:5px;color:red;">金额</td>
                        <td style="text-align:left;"><input id="orders_gr_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;color:red;">折后金额</td>
                        <td style="text-align:left;"><input id="orders_grd_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>