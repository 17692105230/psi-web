<table id="user_organization_grid" class="easyui-treegrid" style="width:100%;" fitColumns="true"
		data-options="
			collapsible:true,
			toolbar:'#user_organization_grid_toolbar',
			rownumbers:true,
			idField: 'org_code',
			treeField:'org_name',
			lines: true,
			fit:true,
			fitColumns:true,
            url:'/manage/Organization/OrgLoad',
			border:false
		">
	<thead>
		<tr>
			<th data-options="field:'id',hidden:true"><b>ID</b></th>
			<th data-options="field:'org_name',width:120"><b>名称</b></th>
			<th data-options="
					field:'org_category',
					fixed:true,
					width:80,
					align:'center',
					formatter:function(value,row,index) {
						switch(value) {
							case '0':
								return '内部机构';
							case '1':
								return '外部机构';
							case '2':
								return '仓库';
						}
					},
					styler:function(value,row,index) {
						switch(value) {
							case '1':
								return 'background-color:#ccc;color:red;';
							case '2':
								return 'background-color:#eee;color:blue;';
						}
					}
				"><b>机构类型</b></th>
			<th data-options="field:'org_code',fixed:true,width:80,align:'center'"><b>机构编号</b></th>
		</tr>
	</thead>
</table>
<div id="user_organization_grid_toolbar" style="text-align:right;">
	<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.user.reloadOrganization">刷新</a>
</div>