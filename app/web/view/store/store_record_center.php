<div class="easyui-layout" data-options="fit:true">
	<div data-options="region:'center'" style="overflow:hidden;">
		<table id="record_grid" class="easyui-datagrid" style="width:100%;"
			data-options="
				fitColumns:true,
				rownumbers:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				fit:true,
				border:false,
				method:'get',
				remoteSort:false,
				multiSort:true,
                toolbar:'#record_grid_toolbar',
                onClickCell:$.h.sr.onClickCell
			">
			<thead>
				<tr>
					<th data-options="field:'details_id',checkbox:true"></th>
					<th data-options="field:'create_time',width:6"><b>业务日期</b></th>
                    <th data-options="field:'orders_code',width:8,align:'center',
                        styler: function(value,row,index){
                            return 'cursor:pointer;color:red;text-decoration:underline;';
                        }"><b>业务单号</b></th>
					<th data-options="field:'goods_name',width:12"><b>商品</b></th>
                    <th data-options="field:'goods_code',width:8"><b>货号</b></th>
					<th data-options="field:'goods_barcode',width:8"><b>条码</b></th>
					<th data-options="field:'color_name',width:5,align:'center'"><b>颜色</b></th>
					<th data-options="field:'size_name',width:5,align:'center'"><b>尺码</b></th>
					<th data-options="field:'orders_type',width:5,align:'center'"><b>业务类别</b></th>
					<th data-options="field:'goods_number',align:'center'">&nbsp;<b>单据数量</b>&nbsp;</th>
					<th data-options="field:'stock_number',align:'center'">&nbsp;<b>库存数量</b>&nbsp;</th>
				</tr>
			</thead>
		</table>
        <div id="record_grid_toolbar" style="height:33px;padding:1px;">
            <form id="query_form" method="get">
            <input id="warehouse_id" name="warehouse_id" class="easyui-combobox"
                data-options="
                    valueField:'org_id',
                    textField:'org_name',
                    prompt:'选择仓库...',
                    label:'仓库',
                    labelWidth:35,
                    width:200,
                    panelHeight:'auto',
                    editable:false,
                    required:true,
                    url:'/web/organization/loadWarehouseList'
                ">
            <select id="search_keyword" name="search_keyword" class="easyui-combogrid"
                data-options="
                    ordersType: 'pp',
                    buttonIcon:'icon-search',
                    onClickButton:function() {
                        var target = $('#search_keyword');
                        target.combogrid('clear');
                        $.h.sr.searchGoods(target);
                    },
                    label:'商品',
                    labelWidth:35,
                    width:220,
                    panelWidth: 800,
                    panelHeight: 390,
                    idField: 'goods_code',
                    textField: 'goods_name',
                    method: 'get',
                    fitColumns: true,
                    editable:false,
                    striped: true,
                    editable: true,  
                    pagination: true,
                    pageSize: 10,
                    pageList: [10,20,30,50],
                    rownumbers: true,
                    hasDownArrow: false,
                    required:true,
                    delay: 0,
                    prompt:'名称、货号、条码...',
                    searchUrl:'/web/goods/sPurchaseOrdersGoods',
                    keyHandler:{
                        enter : function(e) {
                            var target = $(e.data.target);
                            target.combogrid('clear');
                            $.h.sr.searchGoods(target);
                        }
                    },
                    columns: [[
                        {field:'goods_id',hidden:true},
                        {field:'goods_name',title:'名称',width:160},
                        {field:'goods_code',title:'货号',width:100},
                        {field:'goods_barcode',title:'条码',width:100}
                    ]],
                    onClickRow:function() {
                        var target = $('#search_keyword');
                        target.combogrid('panel').panel('options').closed = false;
                        $.h.sr.searchGoods(target);
                    }
                ">
            </select>
            <select id="color_id" name="color_id" class="easyui-combobox" data-options="valueField:'color_id',textField:'color_name',label:'颜色',labelWidth:35,width:130,panelHeight:'auto',editable:false,prompt:'选择颜色'"></select>
            <select id="size_id" name="size_id" class="easyui-combobox" data-options="valueField:'size_id',textField:'size_name',label:'尺码',labelWidth:35,width:130,panelHeight:'auto',editable:false,prompt:'选择尺码'"></select>
            <input id="begin_date" name="begin_date" type="text" class="easyui-datebox" data-options="label:'日期',labelWidth:35,width:155,editable:false,prompt:'开始日期'"/>
            <input id="end_date" name="end_date" type="text" class="easyui-datebox" data-options="label:'至',labelWidth:20,width:140,editable:false,prompt:'结束日期'"/>
            <a class="easyui-linkbutton" data-options="onClick:$.h.sr.btnRecordReset">重置</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-search',width:100,onClick:$.h.sr.btnRecordQuery">查询</a>
            </form>
        </div>
        <div id="record_grid_footer" style="padding:2px 5px;">
            <span style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="$rola.cashier.sale.removeBillDetails()">删除</a>
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="$rola.cashier.sale.returnGoods()">退款</a>
                <a href="javascript:void(0)" title="Ctrl+0" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="$rola.cashier.sale.newSale()">开新单</a>
                <input class="easyui-textbox" data-options="prompt:'请输入条码......'" style="width:160px;"/>
            </span>
        </div>
	</div>
</div>
