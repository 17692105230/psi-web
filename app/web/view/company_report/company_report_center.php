<table id="member_grid" class="easyui-datagrid" style="width:100%;" title=""
    data-options="
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        fit:true,
        border:false,
		url:'/web/company_report/loadDataGrid',
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
            <th data-options="field:'report_id',checkbox:true"></th>
            <th data-options="field:'report_name',width:40"><b>标题</b></th>
			<th data-options="field:'report_type',width:40"><b>发布状态</b></th>
            <th data-options="field:'report_user',width:60"><b>发布人</b></th>
            <th data-options="field:'report_time',width:70"><b>发布时间</b></th>
        </tr>
    </thead>
</table>
<div id="logistics_grid_toolbar" style="padding-left:5px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true">删除</a>
</div>