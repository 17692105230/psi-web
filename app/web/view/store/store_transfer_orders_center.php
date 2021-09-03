<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:130px;background-color:#eee;"
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
				<a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.st.mDelOrders">删除单据</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.st.mNewOrders">新建单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',plain:true"></a>
            </div>
        </header>
        <form id="orders_form" method="post">
            <div class="form-clo2">
                    <div class="name" style="font-weight:bold;color:green">调出仓库:</div>
                    <div class="ctl">
                        <input id="out_warehouse_id" name="out_warehouse_id" class="easyui-combobox" style="width:100%;"
                            data-options="
                                valueField:'org_id',
                                textField:'org_name',
                                panelHeight:120,
                                editable:false,
                                required:true,
                                url:'/web/organization/loadWarehouseList'
                            " />
                    </div>
					<div class="name">调入仓库:</div>
					<div class="ctl">
						<input id="in_warehouse_id" name="in_warehouse_id" class="easyui-combobox" style="width:100%;"
                            data-options="
                                valueField:'org_id',
                                textField:'org_name',
                                panelHeight:120,
                                editable:false,
                                required:true,
                                url:'/web/organization/loadWarehouseList'
                            " />
					</div>
                    <div class="name">日期:</div>
                    <div class="ctl">
                        <input id="orders_date" name="orders_date" class="easyui-datebox" data-options="required:true" value="(new Date()).Format('yyyy-MM-dd')" style="width:100%;"/>
                    </div>
                </div>
                    <div class="form-clo2">
                    <div class="name w ">备注:</div>
                    <div class="ctl w" style="width: 100%">
                        <input id="orders_remark" name="orders_remark" class="easyui-textbox" data-options="prompt:'请输入备注......'" style="width:100%;"/>
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
				onBeforeLoad:$.h.st.mGrid.onBeforeLoad,
				onClickCell:$.h.st.mGrid.onClickCell,
				onEndEdit:$.h.st.mGrid.onEndEdit,
				onAfterEdit:$.h.st.mGrid.onAfterEdit,
				rownumbers:true,
				multiSort:true,
				remoteSort:false,
                onKeyUpDownEvent:$.h.st.mGrid.onClickCell
			">
			<thead>
				<tr>
					<th data-options="field:'goods_id',checkbox:true"></th>
					<th data-options="field:'goods_name',width:11,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:7,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:7,sortable:true"><b>货号</b></th>
					<th data-options="
							field:'color_id',
							editor:{
								type:'combobox',
								options:{
                                    panelHeight:135,
									editable:false,
									method:'get',
									valueField:'color_id',
									textField:'color_name'
								}
							},
							width:5,
							align:'center',
							formatter:function(value,row,index) {
								return row.color_name;
							}
						"><b>颜色</b></th>
					<th data-options="
							field:'size_id',
							editor:{
								type:'combobox',
								options:{
                                    panelHeight:135,
									editable:false,
									method:'get',
									valueField:'size_id',
									textField:'size_name'
								}
							},
							width:5,
							align:'center',
							formatter:function(value,row,index) {
								return row.size_name;
							}
						"><b>尺码</b></th>
					<th data-options="
							field:'goods_number',
							editor:{
								type:'numberspinner',
								options:{
									min:1,
									max:99999
								}
							},
							align:'center'
						">&nbsp;<b>调拨数量</b>&nbsp;</th>
					<th data-options="field:'out_warehouse_number',width:6,align:'center'"><b><font color=red>调出仓库当前数量</font></b></th>
					<th data-options="field:'in_warehouse_number',width:6,align:'center'"><b><font color=green>调入仓库当前数量</font></b></th>
				</tr>
			</thead>
		</table>
		<div id="orders_grid_details_toolbar" style="height:33px;padding:1px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.st.mDelGoods">删除</a>
            <select id="search_keyword" class="easyui-combogrid" data-options="
                    ordersType: 'st',
                    icons:[{
                            iconCls:'icon-clear',
                            handler:$.h.c.clearCombogrid
                        }],
                    buttonIcon:'icon-search',
                    onClickButton:function() {
                        var target = $('#search_keyword');
                        $.h.st.searchGoods(target);
                    },
                    width:280,
                    panelWidth:800,
                    panelHeight:320,
                    idField: 'goods_code',
					textField: 'goods_name',
                    method: 'get',
                    fitColumns: true,
                    striped: true,
                    editable: true,  
                    pagination: true,
                    pageSize: 10,
					pageList: [10,20,30,50],
                    rownumbers: true,
                    hasDownArrow: false,
                    delay: 0,
                    prompt:'请输入商品名称、货号、条码......',
                    searchUrl:'/web/goods/sPurchaseOrdersGoods',
                    columns: [[
						{field:'goods_id',hidden:true},
						{field:'goods_name',title:'名称',width:160},
						{field:'goods_code',title:'货号',width:100},
						{field:'goods_barcode',title:'条码',width:100}
					]],
                    onShowPanel:function() {
                        $('#orders_grid_details').datagrid('unbindKeyEvent');
                    },
                    onHidePanel:function() {
                        $('#orders_grid_details').datagrid('bindKeyEvent');
                    },
                    onClickRow:function() {
                        var target = $('#search_keyword');
                        target.combogrid('panel').panel('options').closed = false;
                        $.h.st.searchGoods(target);
                    }
                ">
            </select>
			<input class="easyui-switchbutton" data-options="
                onText:'查询销售',
                offText:'扫码销售',
                width:100,
                checked:true,
                onChange:$.h.s.goods.mQueryMode
                ">
			<div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.st.mSaveFormally">提交调拨</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.st.mSaveRoughDraft">保存草稿</a>
			</div>
		</div>

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
<!--颜色尺寸-->
<div id="win_color_size" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'颜色尺寸',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>