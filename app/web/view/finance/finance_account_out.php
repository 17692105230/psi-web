<div class="easyui-layout" data-options="fit:true">
	<div data-options="
        region:'center',
        split:true,
        collapsible:false,
        footer:'#win_finance_account_out_footer'" style="width:360px;padding:10px;">
		<form id="win_finance_account_out_form" method="post">
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
                            url:'/web/settlement/loadCombobox',
                            onSelect:function(record) {
                                $('#account_balance').numberbox('setValue',record.settlement_money);
                            }
                        " /input>
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
                            url:'/web/dict/loadAccountListY'
                        " /input>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">支出金额:</div>
				<div class="ctl w">
                    <input id="expend" name="expend" class="easyui-numberspinner" data-options="width:200,buttonText:'元',required:true,validType:'zero',value:0,prefix:'￥',precision:2"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name w">业务日期:</div>
				<div class="ctl w">
					<input id="create_time" name="order_date" type="text" class="easyui-datebox" data-options="prompt:'选择日期...',required:true,editable:false" style="width:100%;"/>
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
    <div id="win_finance_account_out_footer" style="text-align:right;padding:5px;overflow:hidden;height:45px;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.fa.winOut.save" style="width:100%;height:100%;">提交</a>
    </div>
</div>