<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:98px;border-bottom:1px solid #ddd;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
            border:false,
			hideCollapsedContent:false,
			onResize: function(w, h) {}
	">
        <input type="hidden" id="role_id" value=""/>
		<div>
			<div style="display: flex;height:30px;margin-bottom:10px;">
				<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:20px;">角色名称:</div>
				<div style="flex: 0 0 200px;">
					<input id="role_name" type="text" class="easyui-textbox" data-options="required:true,width:200" />
				</div>
				<div style="flex: 0 0 90px;line-height:30px;text-align:right;padding-right:20px;">备注:</div>
				<div style="width:calc(100% - 400px);">
					<input id="role_annotate" type="text" class="easyui-textbox" data-options="prompt:'请输入备注......'" style="width:100%;"/>
				</div>
			</div>
            <div style="width:100%;text-align:center;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add'" style="width:120px;">新增角色</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-reload'" style="width:120px;">重置</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="width:120px;">保存</a>
			</div>
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
					url:$.toUrl('role', 'loadNodeMenuTree'),
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