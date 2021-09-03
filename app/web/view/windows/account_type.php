<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_account_grid" class="easyui-datagrid"
			data-options="
                fit:true,
				fitColumns:true,
				rownumbers:true,
				border:false,
				toolbar:'#win_base_account_grid_toolbar',
				iconCls:'kbi-icon-record',
				singleSelect: true,
				method:'get',
                nowrap:true,
				onDblClickRow:$.h.window.winBaseAccountType.onDblClickRow
			">
			<thead>
				<tr>
					<th data-options="field:'dict_id',checkbox:true"></th>
					<th data-options="
                        field:'dict_name',
                        align:'center',
                        fixed:true,
                        width:120,
                        styler:function(value,row,index) {
                            return row.dict_disabled == 1 ? 'color:red;' : '';
                        }
                        "><strong>账目类别</strong></th>
                    <th data-options="
                        field:'dict_status',
                        align:'center',
                        fixed:true,
                        width:60,
                        formatter: function(value,row,index) {
                            return value == 1 ? '启用' : '禁用';
                        }"><strong>状态</strong></th>
                    <th data-options="
                        field:'dict_text',
                        align:'left',
                        width:15,
                        formatter: function(value,row,index) {
                            return '<span title=' + value + '>' + value + '</span>';
                        }"><strong>备注</strong></th>
					<th data-options="field:'sort',align:'center'"><strong>&nbsp;排序&nbsp;</strong></th>
				</tr>
			</thead>
		</table>
		<div id="win_base_account_grid_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.winBaseAccountType.onDelete">删除</a>
            <span style="float:right;">
				<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winBaseAccountType.onRefresh">刷新</a>
			</span>
		</div>
    </div>
	<div data-options="
        region:'east',
        split:true,
        collapsible:false,
        tools:[{
            iconCls:'icon-add',
            handler:$.h.window.winBaseAccountType.onAddNew
        }],
        footer:'#win_base_account_form_footer'" title="录入" style="width:300px;padding:10px;">
		<form id="win_base_account_form" method="post">
			<div class="form-clo1">
				<div class="name">账目类别:</div>
				<div class="ctl">
					<input id="dict_name" name="dict_name" class="easyui-textbox" type="text" data-options="prompt:'账目类别名称...',required:true" style="width:100%;"></input>
				</div>
				<div class="kbi_c"></div>
			</div>
            <div class="form-clo1">
				<div class="name">状态:</div>
				<div class="ctl">
					<input id="dict_status" name="dict_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,width:70,height:30" checked/input>
				</div>
				<div class="kbi_c"></div>
			</div>
            <div class="form-clo1">
                <div class="name">排序:</div>
                <div class="ctl">
                    <input id="dict_sort" name="sort" class="easyui-numberspinner" type="text" data-options="required:true" value="100" style="width:100px;"></input>
                </div>
                <div class="kbi_c"></div>
            </div>
            <div class="form-clo1">
				<div class="name">备注:</div>
				<div class="ctl">
					<input id="dict_text" name="dict_text" class="easyui-textbox" data-options="multiline:true," style="width:100%;height:50px;padding:5px;" /input>
				</div>
				<div class="kbi_c"></div>
			</div>
		</form>
	</div>
    <div id="win_base_account_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winBaseAccountType.onClose" style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseAccountType.onSave" style="width:100px;">提交</a>
    </div>
</div>