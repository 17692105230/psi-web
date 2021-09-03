<div id="zzcc" class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:130px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false
	">
		<header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">销售订单 - <span id="orders_code_view" style="font-size:10pt;color:blue;"></span>
            </div>
            <div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.sp.mNewOrders">新建订单</a>
            </div>
        </header>
		<form id="orders_form" method="post">
			<input id="orders_code" name="orders_code" type="hidden" value=""/>
            <div class="form-clo1">
                <div class="name w" style="font-weight:bold;">客户:</div>
                <div class="ctl w">
                    <div id="client_name" class="name" style="text-align: left"></div>
                </div>
                <div class="name w" style="font-weight:bold">销售日期:</div>
                <div class="ctl w">
                    <div id="orders_date" class="name" style="text-align: left"></div>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w" style="font-weight:bold;color:brack">备注:</div>
                <div class="ctl w">
                    <div id="orders_remark" class="name" style="text-align: left"></div>
                </div>
            </div>
		</form>
	</div>
	<div data-options="region:'center'" style="overflow:hidden;border-top:1px solid #ddd;">
		<table id="orders_grid_details" class="easyui-datagrid" style="width:100%;"
			data-options="
				fit:true,
				fitColumns:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				rownumbers:true,
				border:false,
				footer:'#orders_grid_details_footer',
				method:'get',
                onBeforeLoad:$.h.sp.complete.onBeforeLoad
			">
			<thead>
				<tr>
					<th data-options="field:'goods_id',checkbox:true"></th>
					<!-- th data-options="
						field:'id',
						formatter:function(value,row,index) {
							return '<a href=\'javascript:$.H.PL.deleteCommodity('+index+');\' class=\'datagrid-row-del\' style=\'display:inline-block;width:16px;height:16px;\'></a>';
						}
					"></th -->
					<th data-options="field:'goods_name',width:12,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:12,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:12,sortable:true"><b>货号</b></th>
					<th data-options="field:'color_name',width:7,align:'center'"><b>颜色</b></th>
					<th data-options="field:'size_name',width:7,align:'center'"><b>尺码</b></th>
					<th data-options="field:'goods_number',align:'center'">&nbsp;&nbsp;&nbsp;<b>数量</b>&nbsp;&nbsp;&nbsp;</th>
					<th data-options="
							field:'goods_price',
							width:7,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>单价</b></th>
					<th data-options="
							field:'goods_tmoney',
							width:9,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>金额</b></th>
				</tr>
			</thead>
		</table>
		<div id="orders_grid_details_footer" style="padding:2px 5px;">
			<div style="float:left;">
			</div>
			<div style="float:right;">
				<input id="goods_number" class="easyui-numberbox" data-options="label:'合计数量',labelWidth:65,width:130,readonly:true,precision:0,value:0"/>
				<input id="orders_pmoney" class="easyui-numberbox" data-options="label:'合计金额',labelWidth:65,width:180,readonly:true,prefix:'￥',precision:2,value:0"/>
			</div>
		</div>
	</div>
</div>