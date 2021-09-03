<table id="user_grid" class="easyui-datagrid" style="height: 100%"
    data-options="
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        collapsible: true,
        border:false,
        method:'get',
        remoteSort:false,
        multiSort:true,
        toolbar:'#user_grid_toolbar',
		url:'/manage/User/SearchUser',
        onDblClickRow:$.h.user.onDblClickUserGrid,
        data: [
	    ]
    ">
    <thead data-options="frozen:true">
		<tr>
			<th data-options="field:'id',checkbox:true"></th>
			<th data-options="field:'user_code',align:'center',width:100"><strong>员工工号</strong></th>
			<th data-options="field:'user_code',align:'left',width:100"><strong>帐号</strong></th>
			<th data-options="field:'user_name',width:80"><b>姓名</b></th>
			<th data-options="
				field:'user_allow_view',
				width:65,
				align:'center',
				formatter:function(value,row,index) {
					if (value == 0) {
						return '<img src=\'/assets/common/css/themes/icons/eyes_close.png\' style=\'width:16px;height:16px;cursor:pointer;\'>';
					} else {
						return '<img src=\'/assets/common/css/themes/icons/eyes_open.png\' style=\'width:16px;height:16px;cursor:pointer;\'>';
					}
				}
			"><b>其他门店</b></th>
		</tr>
	</thead>
    <thead>
        <tr>
			<th data-options="
				field:'user_is_sales',
				width:80,
				align:'center',
				formatter:function(value,row,index) {
					if (value == 1) {
						return '是';
					} else {
						return '否';
					}
				}
			"><b>营业员</b></th>
			<th data-options="field:'user_organization_name',width:160"><b>隶属机构</b></th>
			<th data-options="field:'user_idcode',width:140,align:'center'"><b>员工证件号</b></th>
			<th data-options="
                    field:'user_entry_time',
                    width:100,
                    align:'center',
                    formatter:function(value,row,index) {
                        return $.DT.UnixToDate(value);
                    }
                "><b>入职时间</b></th>
			<th data-options="field:'user_iphone',width:100,align:'center'"><b>员工手机</b></th>
			<th data-options="
                    field:'user_allow_login',
                    width:70,
                    align:'center',
                    formatter:function(value,row,index) {
                        if (value) { return '允许'; } else { return '<b>禁止</b>'; }
                    }
                "><b>允许登录</b></th>
            <th data-options="
                    field:'user_last_login_time',
                    width:120,
                    align:'center',
                    formatter:function(value,row,index) {
                        return $.DT.UnixToDate(value);
                    }
                "><b>最后登录时间</b></th>
            <th data-options="
                    field:'user_last_ip',
                    width:100,
                    align:'center',
                    formatter:function(value,row,index) {
                        if (value) { return value; } else { return '0.0.0.0'; }
                    }
                "><b>最后登录IP</b></th>
        </tr>
    </thead>
</table>
<div id="user_grid_toolbar" style="padding-left:5px;">
    帐号：<input class="easyui-textbox" style="width:120px;"/>
    姓名：<input class="easyui-textbox" style="width:120px;"/>
    状态：
	<input class="easyui-combobox" data-options="
		width:160,
		editable:false,
		valueField: 'value',
		textField: 'text',
		groupField:'group',
		data: [{
			value: 'LoginYes',
			text: '允许登录',
			group:'登录'
		},{
			value: 'LoginNo',
			text: '禁止登录',
			group:'登录'
		},{
			value: 'SalesYes',
			text: '是营业员',
			group:'营业员'
		},{
			value: 'SalesNo',
			text: '否营业员',
			group:'营业员'
		},{
			value: 'OrdersYes',
			text: '允许查看其他门店单据',
			group:'查看其他门店单据'
		},{
			value: 'OrdersNo',
			text: '禁止查看其他门店单据',
			group:'查看其他门店单据'
		}]" />
    <a class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">搜索</a>
    <span style="float:right;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true">刷新</a>
    </span>
</div>