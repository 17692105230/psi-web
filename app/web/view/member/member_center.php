<table id="member_grid" class="easyui-datagrid" style="width:100%;" title=""
    data-options="
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        fit:true,
        border:false,
		url:'/web/member/loadGridData',
        method:'get',
        remoteSort:false,
        multiSort:true,
		pagination:true,
		pageSize:20,
		pageList:[20,50,100],
        toolbar:'#user_grid_toolbar',
		onDblClickRow:$.h.member.onDblClickRow
    ">
    <thead>
        <tr>
            <th data-options="field:'member_id',checkbox:true"></th>
            <th data-options="field:'member_code',width:7"><b>帐号</b></th>
            <th data-options="field:'member_name',width:6"><b>姓名</b></th>
			<th data-options="field:'member_iphone',width:6,align:'center'"><b>手机</b></th>
            <th data-options="field:'member_gender',fixed:true,width:50,align:'center'"><b>性别</b></th>
            <th data-options="field:'receive_clerk_name',width:6,align:'center'"><b>接待导购</b></th>
            <th data-options="field:'member_category_name',width:6,align:'center'"><b>会员类别</b></th>
            <th data-options="field:'member_birthday',width:7,align:'center'"><b>会员生日</b></th>
            <th data-options="
					field:'member_status',
					align:'center',
					formatter:function(value, row) {
						if (value == 1) { return '启用'; } else { return '<strong>禁用</strong>'; }
					}
				">&nbsp;<b>状态</b>&nbsp;</th>
        </tr>
    </thead>
</table>
<div id="user_grid_toolbar" style="padding-left:5px;">
    帐号：<input class="easyui-textbox" style="width:120px;"/>
    姓名：<input class="easyui-textbox" style="width:120px;"/>
    状态：<input id="member_status" name="member_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,height:22" checked>
    <a href="javascript:void(0)" title="Ctrl+0" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">搜索</a>
    <span style="float:right;">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true">刷新</a>
    </span>
</div>