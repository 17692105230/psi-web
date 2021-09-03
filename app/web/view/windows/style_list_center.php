<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_style_grid" class="easyui-treegrid"
               data-options="
                idField:'id',
                treeField:'text',
                fit: true,
				fitColumns: true,
				rownumbers:true,
				border: false,
				toolbar: '#win_base_style_grid_toolbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
				method: 'get',
				url:'/web/dict/loadTreeData?dict_type=style',
				onDblClickRow:$.h.window.winBaseStyle.onDblClickRow,
				onLoadSuccess:$.h.window.winBaseStyle.onLoadSuccess
			">
            <thead>
            <tr>
                <th data-options="field:'id',hidden:true"></th>
                <th data-options="field:'text',align:'left',width:15"><strong>款式名称</strong></th>
            </tr>
            </thead>
        </table>
        <div id="win_base_style_grid_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton"
               data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.winBaseStyle.onDelete">删除</a>
            <div style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton"
                   data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winBaseStyle.onRefresh">刷新</a>
            </div>
        </div>
    </div>
    <div data-options="
		region:'east',
		split:true,
		collapsible:false,
		tools:[{
            iconCls:'icon-add',
            handler:$.h.window.winBaseStyle.onAddNew
        }],
		footer:'#win_base_style_form_footer'" title="添加" style="width:320px;padding:10px;">
        <form id="win_base_style_form" method="post">
            <div class="form-clo1">
                <div class="name w">上级节点:</div>
                <div class="ctl w">
                    <select id="style_select" name="dict_pid" class="easyui-combotree" style="width:100%;"
                            data-options="
                                    editable:false,
                                    lines:true,
									required:true
								">
                    </select>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">款式名称:</div>
                <div class="ctl w">
                    <input id="dict_name" name="dict_name" class="easyui-textbox" type="text"
                           data-options="required:true" style="width:100%;"></input>
                </div>
            </div>

        </form>
    </div>
    <div id="win_base_style_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winBaseStyle.onClose"
           style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseStyle.onSave"
           style="width:100px;">提交</a>
    </div>
</div>

