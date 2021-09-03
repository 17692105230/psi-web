<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_color_grid" class="easyui-treegrid"
               data-options="
                fit: true,
                idField:'id',
                treeField:'text',
				fitColumns: true,
				rownumbers:true,
				border: false,
				toolbar: '#win_color_list_grid_toolbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
				url:'/web/color/loadTreeList',
				method: 'get',
				onDblClickRow:$.h.window.winColorList.onDblClickRow,
				onLoadSuccess:$.h.window.winColorList.onLoadSuccess
			">
            <thead>
            <tr>
                <th data-options="field:'id',checkbox:true"></th>
                <th data-options="field:'text',align:'left',width:15"><strong>颜色名称</strong></th>
                <th data-options="field:'sort',align:'center'"><strong>&nbsp;排序&nbsp;</strong></th>
            </tr>
            </thead>
        </table>
        <div id="win_color_list_grid_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.winColorList.onDelete">删除</a>
            <div style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winColorList.onRefresh">刷新</a>
            </div>
        </div>
    </div>
    <div data-options="
		region:'east',
		split:true,
		collapsible:false,
		tools:[{
            iconCls:'icon-add',
            handler:$.h.window.winColorList.onAddNew
        }],
		footer:'#win_base_color_form_footer'" title="录入" style="width:320px;padding:10px;">
        <form id="win_base_color_form" method="post">
            <div class="form-clo1">
                <div class="name w">上级分类:</div>
                <div class="ctl w">
                    <input id="color_group" name="color_group" class="easyui-combotree" style="width:100%;"
                           data-options="
                                editable:false,
                                lines:true,
                                required:true
                    "/>
                </div>
                <div class="kbi_c"></div>
            </div>
<!--            <input id="lock_version" name="lock_version" class="easyui-textbox" type="hidden">-->
<!--            <input id="color_id" name="color_id" class="easyui-textbox" type="hidden">-->
            <div class="form-clo1">
                <div class="name w">类别名称:</div>
                <div class="ctl w">
                    <input id="color_name" name="color_name" class="easyui-textbox" type="text" data-options="required:true" style="width:100%;"></input>
                </div>
                <div class="kbi_c"></div>
            </div>
            <div class="form-clo1">
                <div class="name w">排序:</div>
                <div class="ctl w">
                    <input id="color_sort" name="sort" class="easyui-numberspinner" type="text" data-options="required:true,min:1" value="1" style="width:100px;"></input>
                </div>
                <div class="kbi_c"></div>
            </div>
        </form>
    </div>

    <div id="win_base_color_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winColorList.onClose" style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winColorList.onSave" style="width:100px;">提交</a>
    </div>
</div>