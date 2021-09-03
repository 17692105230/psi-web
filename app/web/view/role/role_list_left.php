<table id="role_datagrid" class="easyui-datagrid" style="width:100%;" fitColumns="true"
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
			rownumbers: true,
			collapsible: false,
            url:$.toUrl('role', 'loadRoleRows')
		">
	<thead>
        <tr>
            <th data-options="field:'id',checkbox:true"></th>
            <th data-options="field:'role_name',editor:'textbox',fixed:true,width:160"><b>角色名称</b></th>
            <th data-options="field:'role_annotate',width:200,align:'left'"><b>注释</b></th>
        </tr>
    </thead>
</table>