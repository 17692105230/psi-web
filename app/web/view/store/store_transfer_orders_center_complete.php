<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" title="调拨单" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:115px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false
	">
        <header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">调拨单 - <span id="orders_code_view" style="font-size:10pt;color:blue;">新单据</span></span>
            </div>
            <div style="float:right;">
				<a id="new_build_orders" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.st.mNewOrders">新建单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',plain:true"></a>
            </div>
        </header>
        <form id="orders_form" method="post">
            <div class="form-clo2">
                <div class="w">调出仓库:</div>
                <div class="ctl w">
                    <div id="out_warehouse_name"></div>
                </div>
                <div class="w">调入仓库:</div>
                <div class="ctl w">
                    <div id="in_warehouse_name"></div>
                </div>
            </div>
            <div class="form-clo2">
                <div class="w ">日期:</div>
                <div class="ctl w">
                    <div id="orders_date"></div>
                </div>
                <div class="w">备注:</div>
                <div class="ctl w">
                    <div id="orders_remark"></div>
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
                checkOnSelect:false,
                selectOnCheck:false,
				border:false,
				toolbar:'#orders_grid_details_toolbar',
				footer:'#orders_grid_details_footer',
				method:'get',
				onBeforeLoad:$.h.st.complete.onBeforeLoad,
				rownumbers:true,
				multiSort:true,
				remoteSort:false
			">
			<thead>
				<tr>
					<th data-options="field:'goods_id',checkbox:true"></th>
					<th data-options="field:'goods_name',width:11,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:7,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:7,sortable:true"><b>货号</b></th>
					<th data-options="field:'color_id',width:5,align:'center',formatter:function(value,row){
					    return row.color_name;
					}"><b>颜色</b></th>
					<th data-options="field:'size_id',width:5,align:'center',formatter:function(value,row){
					    return row.size_name;
					}"><b>尺码</b></th>
					<th data-options="field:'goods_number',align:'center'">&nbsp;<b>调拨数量</b>&nbsp;</th>
					<th data-options="field:'out_warehouse_number',width:6,align:'center'"><b><font color=red>调出仓库当前数量</font></b></th>
					<th data-options="field:'in_warehouse_number',width:6,align:'center'"><b><font color=green>调入仓库当前数量</font></b></th>
				</tr>
			</thead>
		</table>

		<div id="orders_grid_details_footer" style="padding:2px 5px;">
			<div style="float:left;">
			</div>
			<div style="float:right;">
				<input id="goods_number" class="easyui-numberbox"
                    data-options="
                        label:'合计数量',
                        labelWidth:65,
                        width:150,
                        readonly:true,
                        precision:0,
                        value:0
                    "/>
			</div>
		</div>
	</div>
</div>