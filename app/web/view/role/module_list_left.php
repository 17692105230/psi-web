<table id="module_treegrid" class="easyui-treegrid"
		data-options="
			fit:true,
			border:false,
			fitColumns:true,
			animate:false,
			autoRowHeight:false,
			collapsible:true,
			rownumbers:true,
			idField: 'node_code',
			treeField:'node_name',
			lines: true,
			method:'get',
			singleSelect:false,
			ctrlSelect:true,
			url:$.toUrl('role', 'loadNodeMenuTree')
		">
	<thead>
		<tr>
			<th data-options="field:'node_id',checkbox:true"></th>
			<th data-options="field:'node_code',align:'center',fixed:true,width:80">&nbsp;<b>节点编号</b>&nbsp;</th>
			<th data-options="
					field:'node_name',
					width:120,
					styler: function(value,row,index){
						if (row.node_category == 1) {
							return 'color:red;font-weight:bold;';
						}
					}
				"><b>名称</b></th>
			<th data-options="field:'role_remark',width:60"><b>备注</b></th>
			
		</tr>
	</thead>
</table>