<div class="easyui-layout" data-options="fit:true">
	<div data-options="
        region:'center',
        split:true,
        collapsible:false,
        footer:'#win_finance_client_in_footer'" style="width:360px;padding:10px;">
		<form id="win_finance_client_in_form" method="post">
		<div class="kbi_column">
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
                            pageSize:2,
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
                            onSelect:function(index,data) {
                                $('#account_balance').numberbox('setValue',data.account_money);
                            }
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
			</div>
            <div class="form-clo1">
                <div class="name w"></div>
                <div class="ctl w" style="color: grey">提示：负数表示客户欠款。</div>
            </div>
            <div class="form-clo1">
				<div class="name w">结算账户:</div>
				<div class="ctl w">
					<input id="settlement_id" name="settlement_id" class="easyui-combobox" style="width:100%;"
                        data-options="
                            valueField:'settlement_id',
                            textField:'settlement_name',
                            panelHeight:'auto',
                            panelMaxHeight:200,
                            editable:false,
                            required:true,
                            prompt:'选择结算账户...',
                            url:'/manage/settlement/loadCombobox'
                        " /input>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">账目类别:</div>
				<div class="ctl w">
					<input id="account_id" name="account_id" class="easyui-combobox"  style="width:100%;"
                        data-options="
                            valueField:'dict_id',
                            textField:'dict_name',
                            panelHeight:'auto',
                            editable:false,
                            required:true,
                            prompt:'请选择......',
                            url:'/manage/dict/loadAccountListY'
                        " /input>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">收款金额:</div>
				<div class="ctl w">
					<input id="in_money" name="in_money" class="easyui-numberspinner" data-options="width:200,buttonText:'元',required:true,validType:'zero',value:0,prefix:'￥',precision:2"/>
                </div>
			</div>
            <div class="form-clo1">
                <div class="name w"></div>
                <div class="ctl w" style="color: grey">提示：正数表示收款,负数表示退款给客户。</div>
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
					<input id="remark" name="remark" class="easyui-textbox" data-options="validType:'length[0,50]',multiline:true" style="width:100%;height:65px;padding:5px;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
		</div>
		</form>
	</div>
    <div id="win_finance_client_in_footer" style="text-align:right;padding:5px;overflow:hidden;height:50px;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.fc.winIn.save" style="width:100%;height:100%;">提交</a>
    </div>
</div>