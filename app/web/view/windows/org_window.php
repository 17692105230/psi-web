<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_base_org_grid" class="easyui-treegrid"
			data-options="
				fitColumns:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				fit:true,
				border:false,
				method:'get',
				toolbar:'#win_base_org_grid_toolbar',
				collapsible:true,
				rownumbers:true,
				idField: 'id',
				treeField:'text',
				lines: true,
				url:'/web/organization/loadTree',
                onDblClickRow:$.h.window.winBaseOrg.onDblClickRow,
                onLoadSuccess:function(row, data) {
                    $('#org_pid').combotree('loadData', data);
                }
			">
			<thead>
				<tr>
					<th data-options="field:'id',checkbox:true"></th>
					<th data-options="field:'text',width:22"><b>名称</b></th>
					<th data-options="
							field:'org_type',
							fixed:true,
							width:80,
							align:'center',
							formatter:function(value,row,index) {
								switch(value) {
									case 0:
										return '内部机构';
									case 1:
										return '外部门店';
									case 2:
										return '仓库';
								}
							},
							styler:function(value,row,index) {
								switch(value) {
									case 1:
										return 'background-color:#ccc;color:red;';
									case 2:
										return 'background-color:#eee;color:blue;';
								}
							}
						"><b>机构类型</b></th>
					<th data-options="
							field:'org_status',
							formatter:function(value,row,index) {
								return value == 1 ? '<font color=green>启用</font>' : '<font color=red>禁用</font>'
							}
						"><b>状态</b></th>
					
				</tr>
			</thead>
		</table>
		<div id="win_base_org_grid_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.winBaseOrg.onDelete">删除</a>
            <span style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.winBaseOrg.onRefresh">刷新</a>
			</span>
		</div>
    </div>
	<div data-options="
        region:'east',
        split:true,
        collapsible:false,
        tools:[{
            iconCls:'icon-add',
            handler:$.h.window.winBaseOrg.onAddNew
        }],
        footer:'#win_base_org_form_footer'" title="录入" style="width:360px;padding:10px;">
		<form id="win_base_org_form" method="post">
		<div class="kbi_column">
			<div class="form-clo1">
				<div class="name w">上级机构:</div>
				<div class="ctl w">
                    <select id="org_pid" name="org_pid" class="easyui-combotree" style="width:100%;" data-options="editable:false,lines:true"></select>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">机构类型:</div>
				<div class="ctl w">
					<select id="org_type" name="org_type" class="easyui-combobox" data-options="editable:false,required:true,panelHeight:100" style="width:100%;">
						<option value="0">内部机构</option>
						<option value="1">外部门店</option>
						<option value="2">仓库</option>
					</select>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">机构名称:</div>
				<div class="ctl w">
					<input id="org_name" name="org_name" class="easyui-textbox" data-options="required:true" style="width:100%;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">负责人:</div>
				<div class="ctl w">
					<input id="org_head" name="org_head" class="easyui-textbox" data-options="required:true" style="width:100%;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">联系电话:</div>
				<div class="ctl w">
					<input id="org_phone" name="org_phone" class="easyui-textbox" style="width:100%;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">机构状态:</div>
				<div class="ctl w">
					<input id="org_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',width:100,checked:true" />
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">排序:</div>
				<div class="ctl w">
					<input id="org_sort" name="org_sort" class="easyui-numberspinner" style="width:100px;" value="100" data-options="required:true,spinAlign:'horizontal',min:0,max:9999999"/>
				</div>
				<div class="kbi_c"></div>
			</div>
		</div>
		</form>
	</div>
    <div id="win_base_org_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.winBaseOrg.onClose" style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseOrg.onSave" style="width:100px;">提交</a>
    </div>
</div>