<div class="easyui-panel" style="padding:10px;background-color:#eee;"
	data-options="
		fit:true,
		border:false
	">
        <form id="query_form" method="get">
        <div class="form-clo1">
			<div class="name w">客户：</div>
			<div class="ctl w">
                <select id="search_client_id" name="search_client_id" class="easyui-combogrid" style='width:100%;' data-options="
                        panelWidth: 500,
                        panelHeight: 320,
                        required:true,
                        idField:'client_id',
                        textField:'client_name',
                        loadMsg:'加载数据中...',
                        method:'get',
                        fitColumns:true,
                        striped:true,
                        editable:false,
                        pagination: true,
                        pageSize:10,
                        showPageList: false,
                        displayMsg: '共 {total} 行',
                        rownumbers:true,
                        delay: 0,
                        prompt:'请选择客户......',
                        url:'/manage/client/loadList',
                        columns: [[
                            {field:'client_id',hidden:true},
                            {field:'client_name',title:'客户名称',width:100,align:'left'},
                            {field:'client_phone',title:'客户手机',width:100},
                            {field:'account_money',title:'账户金额',width:100}
                        ]]
                    ">
                </select>
			</div>
		</div>
        <div class="form-clo1">
			<div class="name w">开始日期:</div>
			<div class="ctl w">
				<input id="search_begin_date" name="search_begin_date" type="text" class="easyui-datebox" data-options="required:true,prompt:'选择日期...',editable:false" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w">结束日期:</div>
			<div class="ctl w">
				<input id="search_end_date" name="search_end_date" type="text" class="easyui-datebox" data-options="required:true,prompt:'选择日期...',editable:false" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w">账目类型:</div>
			<div class="ctl w">
				<input name="search_account_id" class="easyui-combobox"  style="width:100%;"
                    data-options="
                        valueField:'dict_id',
                        textField:'dict_name',
                        panelHeight:'auto',
                        editable:false,
                        prompt:'请选择账目类别......',
                        url:'/manage/dict/loadAccountList'
                    " /input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w">结算账户:</div>
			<div class="ctl w">
				<input name="search_settlement_id" class="easyui-combobox" style="width:100%;"
                    data-options="
                        valueField:'settlement_id',
                        textField:'settlement_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        prompt:'选择结算账户...',
                        url:'/manage/settlement/loadCombobox'
                    " /input>
			</div>
		</div>
        <div class="form-clo1">
			<div class="name w">单号:</div>
			<div class="ctl w">
				<input name="search_orders_code" type="text" class="easyui-textbox" data-options="prompt:'单据编号...'" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w">操作人:</div>
			<div class="ctl w">
				<input name="search_user_id" class="easyui-combobox" style="width:100%;"
                    data-options="
                        valueField:'user_id',
                        textField:'user_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        prompt:'选择操作人...',
                        url:'/manage/user/loadUserCombobox'
                    " /input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w">
                <a id="btnReset" class="easyui-linkbutton" data-options="" style="width:100%;height:30px;">重置</a>
            </div>
			<div class="ctl w">
				<a id="btnQuery" class="easyui-linkbutton" data-options="iconCls:'icon-search',onClick:$.h.fc.btnClientRecord" style="width:100%;height:30px;">查询</a>
			</div>
		</div>
        </form>

</div>