<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_material_grid" class="easyui-datagrid"
               data-options="
                fit: true,
				fitColumns: true,
				rownumbers:true,
				border: false,
				toolbar: '#win_material_list_grid_toolbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
				onDblClickRow:$.h.window.winMaterialList.onDblClickRow
			">
            <thead>
            <tr>
                <th data-options="field:'dict_id',checkbox:true"></th>
                <th data-options="field:'dict_name',align:'left',width:15"><strong>材料名称</strong></th>
                <th data-options="field:'create_time',align:'left',width:17"><strong>创建时间</strong></th>
                <th data-options="field:'sort',align:'center'"><strong>&nbsp;排序&nbsp;</strong></th>
            </tr>
            </thead>
        </table>
        <div id="win_material_list_grid_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.winMaterialList.onDelete">删除</a>
            <div style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winMaterialList.onRefresh">刷新</a>
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
		footer:'#win_material_form_footer'" title="录入" style="width:320px;padding:10px;">
        <form id="win_base_material_form" method="post">

            <div class="form-clo1">
                <div class="name w">材质名称:</div>
                <div class="ctl w">
                    <input id="dict_name" name="dict_name" class="easyui-textbox" type="text" data-options="required:true" style="width:100%;"></input>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">排序:</div>
                <div class="ctl w">
                    <input id="sort" name="sort" class="easyui-numberspinner" type="text" data-options="required:true" value="1" style="width:100px;"></input>
                </div>
                <div class="kbi_c"></div>
            </div>
        </form>
    </div>
    <div id="win_material_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winMaterialList.onClose" style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winMaterialList.onSave" style="width:100px;">提交</a>
    </div>
</div>
