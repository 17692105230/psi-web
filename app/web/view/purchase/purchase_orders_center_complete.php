<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:130px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false
	">
		<header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">采购单 - <span id="orders_code_view" style="font-size:10pt;color:blue;"></span></span>
            </div>
            <div id="orders_grid_toolbar" style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true">导出Excel</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.po.mCopyToNewOrders">复制为新单</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.po.mNewOrders">新建单据</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.po.mSaveRevoke">撤销单据</a>
            </div>
        </header>
        <form id="orders_form" method="post"></form>
		<input id="purchase_orders_code" type="hidden" value=""/>
		<div class="form-clo2">
				<div class="name w120"><strong>供应商:</strong></div>
				<div class="ctl w120"><div id="supplier_name" class="name" style="text-align: left"></div></div>
				<div class="name w120"><strong>仓库:</strong></div>
				<div class="ctl w120"><div id="warehouse_name" class="name" style="text-align: left"></div></div>
				<div class="name w120"><strong>结算账户:</strong></div>
				<div class="ctl w120"><div id="settlement_name" class="name" style="text-align: left"></div></div>
				<div class="name w120"><strong>实付金额:</strong></div>
				<div class="ctl w120"><div id="orders_rmoney" class="name" style="text-align: left">￥0.00</div></div>
				<div class="name w120"><strong>日期:</strong></div>
				<div class="ctl w120"><div id="orders_date" class="name" style="text-align: left"></div></div>
        </div>
        <div class="form-clo2">
				<div class="name w120"><strong>备注:</strong></div>
				<div class="ctl w120"><div id="orders_remark" class="name" style="text-align: left"></div></div>
        </div>

	</div>
	<div data-options="region:'center'" style="overflow:hidden;border-top:1px solid #ddd;">
		<table id="orders_grid_details" class="easyui-datagrid" style="width:100%;"
			data-options="
				fit:true,
				fitColumns:true,
				rownumbers:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				border:false,
				footer:'#orders_grid_details_footer',
				method:'get',
				onBeforeLoad:$.h.po.complete.onBeforeLoad,
				rownumbers:true,
				multiSort:true,
				remoteSort:false
			">
			<thead>
				<tr>
					<th data-options="field:'goods_id',checkbox:true"></th>
					<th data-options="field:'goods_name',width:11,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:7,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:10,sortable:true"><b>货号</b></th>
					<th data-options="
							field:'color_id',
							width:5,
							align:'center',
							formatter:function(value,row,index) {
								return row.color_name;
							}
						"><b>颜色</b></th>
					<th data-options="
							field:'size_id',
							width:3,
							align:'center',
							formatter:function(value,row,index) {
								return row.size_name;
							}
						"><b>尺码</b></th>
					<th data-options="
							field:'goods_number',
							align:'center'
						">&nbsp;<b>数量</b>&nbsp;</th>
					<th data-options="
							field:'goods_rprice',
							width:5,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>单价</b></th>
					<th data-options="
							field:'goods_discount',
							width:3,
							align:'right',
							formatter:function(value, row) {
								if (value == null) {
									return 85;
								} else {
									return value;
								}
							}
						"><b>折扣</b></th>
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
		<div id="orders_grid_details_footer" style="padding:2px 5px;">
			<div style="float:left;">
                <input id="orders_other_type" class="easyui-textbox"
                    data-options="
                        valueField:'dict_id',
                        textField:'dict_name',
                        width:200,
                        label:'<font color=blue><strong>其它费用</strong></font>',
                        labelWidth:65,
                        panelHeight:'auto',
                        readonly:true,
                        url:'/manage/dict/loadAccountList'
                    " /input>
                <input id="orders_other_money" class="easyui-numberbox"
                    data-options="
                        label:'<font color=blue><strong>金额</strong></font>',
                        labelWidth:35,
                        width:160,
                        prefix:'￥',
                        precision:2,
                        readonly:true,
                        value:0
                    "/>
			</div>
			<div style="float:right;">
				<input id="orders_erase_money" class="easyui-numberbox"
                    data-options="
                        label:'<font color=blue><strong>抹零</strong></font>',
                        labelWidth:35,
                        width:130,
                        prefix:'￥',
                        precision:2,
                        readonly:true,
                        value:0
                    "/>
				<input id="goods_number" class="easyui-numberbox"
                    data-options="
                        label:'合计数量',
                        labelWidth:65,
                        width:130,
                        readonly:true,
                        precision:0,
                        value:0
                    "/>
				<input id="orders_pmoney" class="easyui-numberbox"
                    data-options="
                        label:'合计金额',
                        labelWidth:65,
                        width:190,
                        readonly:true,
                        prefix:'￥',
                        precision:2,
                        value:0
                    "/>
			</div>
		</div>
	</div>
</div>