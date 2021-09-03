<form id="node_info_form" method="post">
<div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">节点名称:</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_name" name="node_name" class="easyui-textbox" style="width:100%;"/>
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">节点编号:</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_code" name="node_code" class="easyui-textbox" data-options="prompt:'系统生成......',readonly:true" style="width:100%;"/>
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">父节点编号:</div>
		<div style="width:calc(100% - 90px);">
			<input id="parent_node_code" name="parent_node_code" class="easyui-textbox" data-options="required:true,readonly:true" style="width:100%;"/>
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">节点类型:</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_category" name="node_category" class="easyui-switchbutton" data-options="onText:'菜单',offText:'权限',width:80">
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">默认状态:</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_default_status" name="node_default_status" class="easyui-switchbutton" data-options="onText:'系统',offText:'用户',width:80">
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">排序:</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_sort" name="node_sort" class="easyui-numberspinner" style="width:120px;text-align:center;" value="10000" data-options="spinAlign:'horizontal',min:10000,max:9999999"/>
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">菜单信息</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_url" name="node_url" class="easyui-textbox" data-options="" style="width:100%;"/>
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">菜单分组:</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_group" name="node_group" class="easyui-textbox" data-options="" style="width:100%;"/>
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">叶子节点:</div>
		<div style="width:calc(100% - 90px);">
			<input id="isleaf" name="isleaf" class="easyui-textbox" data-options="" style="width:100%;"/>
		</div>
	</div>
	<div style="display: flex;height:30px;margin-bottom:10px;">
		<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:10px;">菜单图标:</div>
		<div style="width:calc(100% - 90px);">
			<input id="node_ico" name="node_ico" class="easyui-textbox" data-options="" style="width:100%;"/>
		</div>
	</div>
</div>
</form>