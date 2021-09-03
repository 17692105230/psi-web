<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_category_grid" class="easyui-treegrid"
               data-options="
                fit: true,
				fitColumns: true,
				rownumbers:true,
				border: false,
				toolbar: '#win_base_category_grid_tollbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
                url:'/web/category/loadData',
				method: 'get',
                idField:'id',
				lines:true,
                treeField:'text',
                onDblClickRow:$.h.window.winCategoryList.onDblClickRow,
				onLoadSuccess:$.h.window.winCategoryList.onLoadSuccess
			">
            <thead>
            <tr>
                <th data-options="field:'id',checkbox:true"></th>
                <th data-options="field:'text',align:'left',width:15"><strong>分类名称</strong></th>
                <th data-options="field:'sort',align:'center'"><strong>&nbsp;排序&nbsp;</strong></th>
            </tr>
            </thead>
        </table>
        <div id="win_base_category_grid_tollbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.winCategoryList.onDelete">删除</a>
            <div style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winCategoryList.onRefresh">刷新</a>
            </div>
        </div>
    </div>
    <div data-options="
        region:'east',
        split:true,
        collapsible:false,
        tools:[{
            iconCls:'icon-add',
            handler:$.h.window.winCategoryList.onAddNew
        }],
        footer:'#win_base_category_form_footer'
        " title="录入" style="width:360px;padding:10px;">
        <form id="win_base_category_form" method="post">
           
                <div class="form-clo1">
                    <div class="name">上级分类:</div>
                    <div class="ctl">
                        <input id="category_pid" name="category_pid" class="easyui-combotree" style="width:100%;"
                                data-options="
                                    editable:false,
                                    lines:true,
                                    required:true
                        "></input>
                    </div>
                    <div class="kbi_c"></div>
                </div>
                <div class="form-clo1">
                    <div class="name">分类名称:</div>
                    <div class="ctl">
                        <input id="category_name" name="category_name" class="easyui-textbox" type="text" data-options="required:true" style="width:100%;"></input>
                    </div>
                    <div class="kbi_c"></div>
                </div>
                <div class="form-clo1">
                    <div class="name">排序:</div>
                    <div class="ctl">
                        <input id="category_sort" name="sort" class="easyui-numberspinner" type="text" data-options="required:true" value="100" style="width:100px;"></input>
                    </div>
                    <div class="kbi_c"></div>
                </div>
            
        </form>
    </div>

    <div id="win_base_category_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winCategoryList.onClose" style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winCategoryList.onSave" style="width:100px;">提交</a>
    </div>
</div>