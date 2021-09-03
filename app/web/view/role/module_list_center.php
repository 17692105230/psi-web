<div class="easyui-layout" data-options="fit:true">
	<div style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:130px;border-bottom:1px solid #ddd;background-color:#eee;"
		data-options="
			region:'north',
			title:'权限节点列表',
			split:true,
			hideCollapsedContent:false,
			onResize: function(w, h) {}
	">
		<form id="module_form" method="post">
			<div class="form-clo1">
				<div class="name w120">模块名称:</div>
				<div class="ctl w120">
					<input id="module_name" name="module_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
				</div>
				<div class="name w120">控制器名称:</div>
				<div class="ctl w120">
					<input id="controller_name" name="controller_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
				</div>
				<div class="name w120">操作名称:</div>
				<div class="ctl w120">
					<input id="action_name" name="action_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
				</div>
			</div>
            <div class="kbi_column_left_100" style="width:100%;text-align:center;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-reload'" style="width:120px;">重置</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="width:120px;">保存</a>
			</div>
		</form>
	</div>
	<div data-options="border:false,region:'center'" style="overflow:hidden;">
        <table id="module_datagrid" class="easyui-datagrid" style="width:100%;" fitColumns="true"
				data-options="
					fitColumns:true,
					rownumbers:true,
					iconCls:'kbi-icon-record',
					singleSelect:true,
					toolbar:'#module_datagrid_toolbar',
					fit:true,
					method:'get',
					remoteSort:false,
					multiSort:true,
					rownumbers: true,
					collapsible: false,
					url:$.toUrl('role', 'loadModuleRows')
				">
			<thead>
				<tr>
					<th data-options="field:'id',checkbox:true"></th>
					<th data-options="field:'module_controller_code',align:'center',fixed:true,width:80"><b>编号</b></th>
					<th data-options="field:'module_name',editor:'textbox',align:'center',fixed:true,width:150"><b>模块名称</b></th>
					<th data-options="field:'controller_name',editor:'textbox',fixed:true,width:150"><b>控制器名称</b></th>
					<th data-options="field:'action_name',editor:'textbox',fixed:true,width:180"><b>操作名称</b></th>
					<th data-options="field:'node_code',editor:'textbox',align:'left',width:120"><b>节点编号</b></th>
				</tr>
			</thead>
		</table>
		<div id="module_datagrid_toolbar" style="padding-left:10px;">
			<input class="easyui-textbox" data-options="label:'控制器名称:',labelWidth:85,width:200"/input>
			<input class="easyui-textbox" data-options="label:'操作名称',labelWidth:60,width:260"/input>
			<a class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,text:'查询'"/a>
		</div>
    </div>
</div>