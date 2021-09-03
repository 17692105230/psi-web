<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:100px;border-bottom:1px solid #ddd;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
            border:false,
			hideCollapsedContent:false
	">
        <input type="hidden" id="role_id" value=""/>
	
			<div class="form-clo1">
				<div class="name">角色名称:</div>
				<div class="ctl">
					<input id="role_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
				</div>
                <div class="name">备注:</div>
                <div class="ctl">
                    <input id="role_annotate" type="text" class="easyui-textbox" data-options="prompt:'请输入备注......'" style="width:100%;"/>
                </div>
			</div>
            <div class="kbi_column_left b" style="width:100%;text-align:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',onClick:$.h.role.newRole" style="width:120px;">新增角色</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-reload',onClick:$.h.role.newRole" style="width:120px;">重置</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.role.submit" style="width:120px;">保存</a>
			</div>
		
	</div>
	<div data-options="border:false,region:'center'" style="overflow:hidden;border-top:1px solid #ddd;">
        <table id="role_treegrid" class="easyui-treegrid"
                data-options="
					fit:true,
                    fitColumns:true,
                    animate:false,
					autoRowHeight:false,
					collapsible:true,
                    rownumbers:true,
                    idField: 'node_code',
                    treeField:'node_name',
                    lines: true,
                    border:false,
                    checkbox:true,
					method:'get',
					url:'/manage/role/loadNodeMenuTree',
					onClickRow: function(row) {
                        if (row.checked) {
						    $(this).treegrid('uncheckNode',row.node_code);
                        } else {
                            $(this).treegrid('checkNode',row.node_code);
                        }
					}
                ">
            <thead>
                <tr>
                    <th data-options="field:'node_id',hidden:true"><b>ID</b></th>
                    <th data-options="
							field:'node_name',
							width:100,
							styler: function(value,row,index){
								if (row.node_category == 1) {
									return 'color:red;font-weight:bold;';
								}
							}
						"><b>名称</b></th>
                    <th data-options="field:'node_remark',width:150"><b>备注</b></th>
                    <th data-options="field:'node_code',align:'center'">&nbsp;<b>编号</b>&nbsp;</th>
                </tr>
            </thead>
        </table>
    </div>
</div>