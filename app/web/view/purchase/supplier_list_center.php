<table id="supplier_grid" class="easyui-datagrid" style="width:100%;"
       data-options="
		fit:true,
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        border:false,
        method:'get',
        remoteSort:false,
        multiSort:true,
        pagination:true,
		pageSize:30,
		pageList:[30,50,100],
        toolbar:'#supplier_grid_toolbar',
		url:'/web/Supplier/loadGrid'
    ">
    <thead>
    <tr>
        <th data-options="field:'supplier_id',checkbox:true"></th>
        <th data-options="field:'supplier_name',halign:'center',width:120"><b>供应商名称</b></th>
        <th data-options="field:'supplier_director',halign:'center',width:100"><b>负责人</b></th>
        <th data-options="field:'supplier_mphone',align:'center',width:100"><b>手机</b></th>
        <th data-options="field:'supplier_money',halign:'center',align:'right',width:100"><b>账户金额</b></th>
        <th data-options="field:'supplier_discount',align:'center',fixed:true,width:80"><b>默认折扣</b></th>
        <th data-options="
				field:'supplier_status',
				align:'center',
				fixed:true,
				width:60,
				formatter:function(value, row) {
					return value == 1 ? '<font color=green>启用</font>' : '<font color=red>禁用</font>';
				}
			"><b>状态</b></th>
        <th data-options="field:'sort',fixed:true,width:60,align:'center'"><b>排序</b></th>
        <th data-options="field:'supplier_id1',align:'center',formatter:$.h.supplier.modifyFormatter">修改</th>
        <th data-options="field:'supplier_id2',align:'center',formatter:$.h.supplier.delFormatter">删除</th>
    </tr>
    </thead>
</table>
<div id="supplier_grid_toolbar" style="padding-left:5px;">
    <input id="supplier_name_query" class="easyui-textbox" style="width:220px;" data-options="label:'供应商',labelWidth:50"/input>
    <input id="supplier_director_query" class="easyui-textbox" style="width:160px;" data-options="label:'负责人',labelWidth:50"/input>
    <select id="supplier_status_query" class="easyui-combobox" style="width:100px;" data-options="panelHeight:'auto'">
        <option value="all">全部</option>
        <option value= 1>启用</option>
        <option value= 0>禁用</option>
    </select>
    <a href="javascript:void(0)" query='1' class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,onClick:$.h.supplier.onQuery">搜索</a>
    <span style="float:right;">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.supplier.onRefresh">刷新</a>
    </span>
</div>