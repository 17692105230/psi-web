<table id="user_grid" class="easyui-datagrid" style="width:100%;" title=""
    data-options="
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        fit:true,
		fitColumns:true,
        border:false,
        method:'get',
        remoteSort:false,
        multiSort:true,
        toolbar:'#user_grid_toolbar',
		url:$.toUrl('role', 'loadNodeMenuRows')
    ">
    <thead>
        <tr>
			<th data-options="field:'node_id',checkbox:true"></th>
			<th data-options="
					field:'node_name',
					align:'left',
					width:100,
					styler: function(value,row,index) {
                        return row.node_category && 'color:red;font-weight:bold;';
					}
				"><strong>节点名称</strong></th>
			<th data-options="field:'node_code',align:'left',width:100"><strong>节点编号</strong></th>
			<th data-options="field:'parent_node_code',width:100,align:'center'"><b>父节点编号</b></th>
			<th data-options="
					field:'node_category',
					fixed:true,
					width:100,
					align:'center',
					formatter: function(value,row,index){
                        return value == 1 ? '菜单' : '权限';
					},
					styler: function(value,row,index){
                        return value && 'color:red;font-weight:bold;';
					}
				"><b>节点类型</b></th>
            <th data-options="
					field:'node_status',
					fixed:true,
					width:70,
					align:'center',
					formatter: function(value,row,index){
						return value == 1 ? '系统' : '用户';
					}
				"><b>默认状态</b></th>
			<th data-options="field:'node_sort',fixed:true,width:50,align:'center'"><b>排序</b></th>
        </tr>
    </thead>
</table>
<div id="user_grid_toolbar" style="padding-left:5px;">
    <input class="easyui-textbox" data-options="label:'帐号：',labelWidth:50,width:180"/>
    <input class="easyui-textbox" data-options="label:'姓名：',labelWidth:50,width:180"/>
    <select class="easyui-combobox" data-options="label:'状态：',labelWidth:50,width:150,editable:false,panelHeight:'auto'">
		<option value="">请选择...</option>
		<option value="失效">失效</option>
		<option value="启用">启用</option>
	</select>
    <a href="javascript:void(0)" title="Ctrl+0" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">搜索</a>
    <span style="float:right;">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true">保存</a>
    </span>
</div>