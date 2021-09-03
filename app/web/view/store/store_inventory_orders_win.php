<table id="orders_grid_details_c" class="easyui-datagrid" style="width:100%;" title=""
			data-options="
				fit:true,
				fitColumns:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				rownumbers:true,
				pagination: true,
				pageSize: 20,
				pageList: [20,30,50,100],
				border:false,
				toolbar:'#orders_grid_details_c_toolbar',
				method:'post',
				checkOnSelect:false,
				selectOnCheck:false
			">
			<thead>
				<tr>
					<th data-options="field:'details_id',checkbox:true"></th>
					<th data-options="field:'goods_name',width:200,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:200,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:200,sortable:true"><b>货号</b></th>
					<th data-options="field:'color_name',width:100,align:'center'"><b>颜色</b></th>
					<th data-options="field:'size_name',width:100,align:'center'"><b>尺码</b></th>
					<th data-options="field:'goods_number',align:'center',fixed:true,width:100"><b>盘点数量</b></th>
					<th data-options="field:'goods_anumber',width:7,align:'center',fixed:true,width:100"><b>盘点前数量</b></th>
				</tr>
			</thead>
		</table>
		<div id="orders_grid_details_c_toolbar" style="height:33px;padding:1px 10px 1px 10px;">
            <form id="ws_search_form" method="post">
            <input id="children_orders" class="easyui-combobox" data-options="
                label:'盘点人',
                labelWidth:50,
                editable:false,
                panelHeight:'auto',
                panelMaxHeight:200,
                width:200,
                valueField:'children_code',
                textField:'children_name',
                onSelect:$.h.si.winDetails.onSelectChildrenOrders
            "/>
            <input id="ws_goods" name="ws_goods" class="easyui-textbox" data-options="label:'商品',width:200,labelWidth:35,prompt:'名称、货号、条码...'"/>
            <input id="ws_color_id" name="ws_color_id" class="easyui-combobox"
                data-options="
                    label:'颜色',
                    labelWidth:35,
                    valueField:'color_id',
                    textField:'color_name',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    width:160,
                    editable:false,
                    prompt:'选择颜色...',
                    url:'/web/color/loadCombobox'
                ">
            <input id="ws_size_id" name="ws_size_id" class="easyui-combobox"
                data-options="
                    label:'尺码',
                    labelWidth:35,
                    valueField:'size_id',
                    textField:'size_name',
                    panelHeight:'auto',
                    panelMaxHeight:200,
                    width:160,
                    editable:false,
                    prompt:'选择颜色...',
                    url:'/web/size/loadCombobox'
                ">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-search',width:70,onClick:$.h.si.winDetails.searchDetailsList">查 询</a>
            </form>
		</div>