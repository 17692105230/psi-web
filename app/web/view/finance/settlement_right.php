<div class="easyui-panel" style="padding:10px;"
	data-options="
		fit:true,
		border:false
	">
	<form id="settlement_form" method="post">
	
		<div class="form-clo1">
			<div class="name">账户名称:</div>
			<div class="ctl">
				<input id="settlement_name" name="settlement_name" class="easyui-textbox" style="width:100%;" data-options="required:true" /input>
			</div>
		</div>
        <div class="form-clo1">
			<div class="name">帐号:</div>
			<div class="ctl">
				<input id="settlement_code" name="settlement_code" class="easyui-textbox" style="width:100%;" /input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">开户人:</div>
			<div class="ctl">
				<input id="account_holder" name="account_holder" class="easyui-textbox" style="width:100%;" /input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">隶属门店:</div>
			<div class="ctl">
                <input id="subjection_store" name="subjection_store" class="easyui-combobox" style="width:100%;"
                    data-options="
                        valueField:'org_id',
                        textField:'org_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        required:true,
                        prompt:'选择隶属门店...',
                        url:'/manage/organization/loadStoreList'
                    " /input>
			</div>
		</div>
        <div class="form-clo1">
			<div class="name">账户类型:</div>
			<div class="ctl">
                <input id="account_type" name="account_type" class="easyui-combobox" style="width:100%;"
                    data-options="
                        valueField:'dict_id',
                        textField:'dict_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        required:true,
                        prompt:'选择结算账户...',
                        url:'/manage/dict/loadList?dict_type=settlement'
                    " /input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">状态:</div>
			<div class="ctl">
				<input id="settlement_status" name="settlement_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,width:70,height:30" checked/input>
			</div>
		</div>
        <div class="form-clo1">
            <div class="name">排序:</div>
            <div class="ctl">
                <input id="settlement_sort" name="settlement_sort" class="easyui-numberspinner" data-options="required:true,labelPosition:'top',spinAlign:'horizontal',min:0,max:9999999,precision:0,width:100,value:100"/>
            </div>
        </div>
        <div class="form-clo1">
            <div class="name">备注:</div>
            <div class="ctl">
                <input id="settlement_remark" name="settlement_remark" class="easyui-textbox" style="width:100%;height:80px;padding:4px;" data-options="multiline:true"/input>
            </div>
        </div>

	</form>
</div>