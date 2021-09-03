<table id="user_organization_grid" class="easyui-treegrid" style="width:100%;" fitColumns="true"
		data-options="
			collapsible:true,
			toolbar:'#user_organization_grid_toolbar',
			rownumbers:true,
			idField: 'node_id',
			treeField:'node_name',
			lines: true,
			fit:true,
			fitColumns:true,
			autoRowHeight:false,
            url:$.toUrl('role', 'loadMenuTree'),
			border:false
		">
	<thead>
		<tr>
			<th data-options="field:'node_id',hidden:true"></th>
			<th data-options="field:'node_name',width:120"><b>菜单名称</b></th>
			<th data-options="field:'node_code',fixed:true,width:100,align:'center'"><b>菜单编号</b></th>
		</tr>
	</thead>
</table>
<div id="user_organization_grid_toolbar" style="text-align:right;">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true">刷新</a>
</div>