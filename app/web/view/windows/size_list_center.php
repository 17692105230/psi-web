<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_size_grid" class="easyui-treegrid"
               data-options="
                idField:'id',
                treeField:'text',
                fit: true,
				fitColumns: true,
				rownumbers:true,
				border: false,
				toolbar: '#win_base_size_grid_toolbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
				method: 'get',
				url:'/web/Size/loadTreeList',
				onDblClickRow:$.h.window.winBaseSize.onDblClickRow,
				onLoadSuccess:$.h.window.winBaseSize.onLoadSuccess
			">
            <thead>
            <tr><!--,-->
                <th data-options="field:'id',checkbox:true"></th>
                <th data-options="field:'text',align:'left',width:15"><strong>尺寸名称</strong></th>
                <th data-options="field:'sort',align:'center'"><strong>&nbsp;排序&nbsp;</strong></th>
            </tr>
            </thead>
        </table>
        <div id="win_base_size_grid_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton"
               data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.winBaseSize.onDelete">删除</a>
            <div style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton"
                   data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winBaseSize.onRefresh">刷新</a>
            </div>
        </div>
    </div>
    <div data-options="
		region:'east',
		split:true,
		collapsible:false,
		tools:[{
            iconCls:'icon-add',
            handler:$.h.window.winBaseSize.onAddNew
        }],
		footer:'#win_base_size_form_footer'" title="添加" style="width:320px;padding:10px;">
        <form id="win_base_size_form" method="post">
            <div class="form-clo1">
                <div class="name w">上级节点:</div>
                <div class="ctl w">
                    <input id="size_parent" name="size_group" class="easyui-combotree" style="width:100%;"
                            data-options="
                                    panelHeight:'auto',
                                    editable:false
								">
                </div>
            </div>
            <input id="size_id" name="size_id" class="easyui-textbox" type="hidden">
            <input id="lock_version" name="lock_version" class="easyui-textbox" type="hidden">
            <div class="form-clo1">
                <div class="name w">尺寸名称:</div>
                <div class="ctl w">
                    <input id="size_name" name="size_name" class="easyui-textbox" type="text"
                           data-options="required:true" style="width:100%;"></input>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">排序:</div>
                <div class="ctl w">
                    <input id="size_sort" name="sort" class="easyui-numberspinner" type="text"
                           data-options="required:true" value="100" style="width:100px;"></input>
                </div>
            </div>
        </form>
    </div>
    <div id="win_base_size_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winBaseSize.onClose"
           style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseSize.onSave"
           style="width:100px;">提交</a>
    </div>
</div>

