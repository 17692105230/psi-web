<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" title="销售单" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:170px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false
	">
        <header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">销售单 - <span id="orders_code_view" style="font-size:10pt;color:blue;"></span></span>
            </div>
            <div id="orders_grid_toolbar" style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true">导出Excel</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.so.mCopyToNewOrders">复制为新单</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.so.mNewOrders">新建单据</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.so.mSaveRevoke">撤销单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',plain:true"></a>
            </div>
        </header>
        <form id="orders_form" method="post">
            <input id="orders_code" name="orders_code" type="hidden" value=""/>
            <div class="form-clo1">
                    <div class="name w" style="font-weight:bold;color:green">客户:</div>
                    <div class="ctl w">
                        <div id="client_id" class="name" style="text-align: left"></div>
                    </div>
                    <div class="name w" style="font-weight:bold;color:red">销售员:</div>
                    <div class="ctl w">
						<div id="salesman_name" class="name" style="text-align: left"></div>
                    </div>
                    <div class="name w" style="font-weight:bold;color:brack">实付金额:</div>
                    <div class="ctl w">
						<div id="orders_rmoney" class="name" style="text-align: left">￥0.00</div>
                    </div>
                    <div class="name w" style="font-weight:bold;color:brack">日期:</div>
                    <div class="ctl w">
						<div id="orders_date" class="name" style="text-align: left">1999-01-01</div>
                    </div>
            </div>
            <div class="form-clo1">
                    <div class="name w" style="font-weight:bold;color:brack">仓库:</div>
                    <div class="ctl w">
						<div id="warehouse_id" class="name" style="text-align: left"></div>
                    </div>
                    <div class="name w" style="font-weight:bold;color:brack">结算账户:</div>
                    <div class="ctl w">
						<div id="settlement_id" class="name" style="text-align: left"></div>
                    </div>
                    <div class="name w" style="font-weight:bold;color:brack">发货方式:</div>
                    <div class="ctl w">
						<div id="delivery_id" class="name" style="text-align: left"></div>
                    </div>
                    <div class="name w" style="font-weight:bold;color:brack"></div>
                    <div class="ctl w">
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
	<div data-options="region:'center'" style="overflow:hidden;">
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
                onBeforeLoad:$.h.so.complete.onBeforeLoad
			">
			<thead>
				<tr>
					<th data-options="field:'goods_id',checkbox:true"></th>
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