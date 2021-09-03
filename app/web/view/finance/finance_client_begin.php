<div class="easyui-layout" data-options="fit:true">
	<div data-options="
        region:'center',
        split:true,
        collapsible:false,
        footer:'#win_finance_client_begin_footer'" style="width:360px;padding:10px;">
		<form id="win_finance_client_begin_form" method="post">
			<div class="form-clo1">
				<div class="name w">客户:</div>
				<div class="ctl w">
                    <select id="client_id" name="client_id" class="easyui-combogrid" style='width:100%;' data-options="
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
                            ]],
                            onSelect: $.h.fc.winBegin.onSelect
                        ">
                    </select>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">账户余额:</div>
				<div class="ctl w">
                    <input id="account_balance" class="easyui-numberbox" data-options="width:200,editable:false,value:0,prefix:'￥',precision:2"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">期初金额:</div>
				<div class="ctl w">
					<input id="begin_money" name="begin_money" class="easyui-numberspinner" data-options="width:200,buttonText:'元',required:true,validType:'nonzero',value:0,prefix:'￥',precision:2"/>
				</div>
			</div>
            <div class="form-clo1">
                <div class="name w"></div>
                <div class="ctl w" style="color: grey">提示：正数-期初存款，负数-期初欠款。</div>
            </div>
			<div class="form-clo1">
				<div class="name w">业务日期:</div>
				<div class="ctl w">
					<input id="create_time" name="create_time" type="text" class="easyui-datebox" data-options="prompt:'选择日期...',required:true,editable:false" style="width:100%;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">备注:</div>
				<div class="ctl w">
					<input id="remark" name="remark" class="easyui-textbox" data-options="validType:'length[0,50]',multiline:true" style="width:100%;height:90px;padding:5px;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
		</form>
	</div>
    <div id="win_finance_client_begin_footer" style="text-align:right;padding:5px;overflow:hidden;height:50px;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.fc.winBegin.save" style="width:100%;height:100%;">提交</a>
    </div>
</div>