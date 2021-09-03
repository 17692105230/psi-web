<div class="easyui-panel" style="padding:10px;background-color:#eee;"
	data-options="
		fit:true,
		border:false
	">

        <form id="query_form" method="get">
		<div class="form-clo1">
			<div class="name">开始日期:</div>
			<div class="ctl">
				<input id="begin_date" name="begin_date" type="text" class="easyui-datebox" data-options="required:true,prompt:'选择日期...',editable:false" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">结束日期:</div>
			<div class="ctl">
				<input id="end_date" name="end_date" type="text" class="easyui-datebox" data-options="required:true,prompt:'选择日期...',editable:false" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">账目类别:</div>
			<div class="ctl">
				<input name="account_id" class="easyui-combobox"  style="width:100%;"
                    data-options="
                        valueField:'dict_id',
                        textField:'dict_name',
                        panelHeight:'auto',
                        editable:false,
                        prompt:'请选择......',
                        url:'/web/dict/loadAccountListY'
                    "/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">结算账户:</div>
			<div class="ctl">
				<input name="settlement_id" class="easyui-combobox" style="width:100%;"
                    data-options="
                        valueField:'settlement_id',
                        textField:'settlement_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        prompt:'选择结算账户...',
                        url:'/web/settlement/loadCombobox'
                    "/>
			</div>
		</div>
        <div class="form-clo1">
			<div class="name">单号:</div>
			<div class="ctl">
				<input name="orders_code" type="text" class="easyui-textbox" data-options="prompt:'单据编号...'" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">操作人:</div>
			<div class="ctl">
                <input name="user_id" class="easyui-combobox" style="width:100%;"
                    data-options="
                        valueField:'user_id',
                        textField:'user_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        prompt:'选择操作人...',
                        url:'/web/user/loadUserCombobox'
                    " />
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">
                <a id="btn" class="easyui-linkbutton" data-options="" style="width:100%;height:30px;">重置</a>
            </div>
			<div class="ctl">
				<a id="btn" class="easyui-linkbutton" data-options="iconCls:'icon-search',onClick:$.h.fa.btnAccountRecord" style="width:100%;height:30px;">查询</a>
			</div>
		</div>
        </form>

</div>