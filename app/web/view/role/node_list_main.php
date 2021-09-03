<div class="easyui-layout" data-options="fit:true">
	<div data-options="region:'west',split:true,title:'权限节点信息',dataType:'json',href:'/web/role/node_list_left',footer:'#role_info_form_footer'" style="width:340px;min-width:320px;max-width:460px;overflow:hidden;padding:10px;background-color:#eee;"></div>
	<div data-options="region:'center',title:'权限节点列表',dataType:'json',href:'/web/role/node_list_center'" style="overflow:hidden;"></div>
	<div data-options="region:'east',split:true,hideCollapsedContent:false,title:'功能菜单',dataType:'json',href:'/web/role/node_list_right'" style="width:340px;"></div>
</div>
<div id="role_info_form_footer" style="display:flex;justify-content:space-between;align-items:center;padding:5px;overflow:hidden;">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload'" style="width:46%;">重置</a>
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="width:46%;">提交</a>
</div>