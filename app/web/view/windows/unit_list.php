<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_unit_grid" class="easyui-datagrid"
               data-options="
                fit: true,
				fitColumns: true,
				rownumbers:true,
				border: false,
				toolbar: '#win_base_unit_grid_toolbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
				onDblClickRow:$.h.window.winUnitList.onDblClickRow
			">
            <thead>
            <tr>
                <th data-options="field:'dict_id',checkbox:true"></th>
                <th data-options="field:'dict_name',align:'center',width:15"><strong>单位名称</strong></th>
                <th data-options="field:'sort',align:'center'"><strong>&nbsp;排序&nbsp;</strong></th>
            </tr>
            </thead>
        </table>
        <div id="win_base_unit_grid_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" onclick="$.h.window.winUnitList.onDelete()" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true">删除</a>
            <span style="float:right;">
				<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winUnitList.onRefresh">刷新</a>
			</span>
        </div>
    </div>
    <div data-options="
        region:'east',
        split:true,
        collapsible:false,
        tools:[{
            iconCls:'icon-add',
            handler:$.h.window.winUnitList.onAddNew
        }],
        footer:'#win_base_unit_form_footer'" title="录入" style="width:300px;padding:10px;">
        <form id="win_base_unit_form" method="post">
            <div class="form-clo1">
                <div class="name w120">单位名称:</div>
                <div class="ctl w120">
                    <div>
                        <input id="dict_name" name="dict_name" class="easyui-textbox" type="text" data-options="required:true" style="width:100%;"></input>
                    </div>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w120">排序:</div>
                <div class="ctl w120">
                    <div>
                        <input id="sort" name="sort" class="easyui-numberspinner" type="text" data-options="required:true" value="100" style="width:100px;"></input>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="win_base_unit_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winUnitList.onClose" style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winUnitList.onSave" style="width:100px;">提交</a>
    </div>
</div>