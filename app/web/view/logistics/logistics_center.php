<table id="member_grid" class="easyui-datagrid" style="width:100%;" title=""
    data-options="
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        fit:true,
        border:false,
		url:'/web/Logistics/loadGridData',
        method:'get',
        remoteSort:false,
        multiSort:true,
		pagination:true,
		pageSize:20,
		pageList:[20,50,100],
        toolbar:'#logistics_grid_toolbar',
    ">
    <thead>
        <tr>
            <th data-options="field:'logistics_id',checkbox:true"></th>
            <th data-options="field:'logistics_name',width:40"><b>名称</b></th>
			<th data-options="field:'logistics_type',width:40"><b>快递单类型</b></th>
            <th data-options="field:'logistics_company',width:60"><b>快递公司</b></th>
            <th data-options="field:'logistics_Url',width:70"><b>网址</b></th>
        </tr>
    </thead>
</table>
<div id="logistics_grid_toolbar" style="padding-left:5px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true">删除</a>
</div>