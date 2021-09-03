<div class="easyui-layout" data-options="fit:true">
	<div data-options="
        region:'center',
        split:true,
        collapsible:false,
        footer:'#win_finance_supplier_begin_footer'" style="width:500px;padding:10px;">
		<form id="win_finance_supplier_begin_form" method="post">
			<div class="form-clo1">
				<div class="name">供应商:</div>
				<div class="ctl">
                    <select id="supplier_id" name="supplier_id" class="easyui-combogrid" style="width:100%"
                        data-options="
                            panelWidth: 520,
                            panelHeight: 310,
                            idField: 'supplier_id',
                            textField: 'supplier_name',
                            loadMsg:'加载数据中...',
                            prompt:'请选择供应商......',
                            fitColumns: true,
                            pagination: true,
                            pageSize:20,
                            showPageList: false,
                            displayMsg: '共 {total} 行',
                            rownumbers: true,
                            url:'/web/supplier/loadGrid',
                            method: 'get',
                            editable:false,
                            required:true,
                            columns: [[
                                {field:'supplier_id',hidden:true},
                                {field:'supplier_name',title:'<strong>供应商名称</strong>',width:120},
                                {field:'supplier_discount',title:'<strong>折扣</strong>',align:'center',fixed:true,width:50},
                                {field:'supplier_money',title:'<strong>账户金额</strong>',width:80,align:'right'},
                                {field:'supplier_status',title:'<strong>状态</strong>',align:'center',fixed:true,width:50,formatter:function(value,row,index){return value ? '正常' : '禁用';}}
                            ]],
                            onSelect: $.h.fs.winBegin.onSelect
                        ">
                    </select>
				</div>
				<div class="kbi_c"></div>
			</div>
            <div class="form-clo1">
				<div class="name">账户余额:</div>
				<div class="ctl">
                    <input id="account_balance" class="easyui-numberbox" data-options="width:200,editable:false,value:0,prefix:'￥',precision:2"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name">期初金额:</div>
				<div class="ctl">
                    <input id="begin_money" name="begin_money" class="easyui-numberspinner" data-options="width:200,buttonText:'元',required:true,validType:'nonzero',value:0,prefix:'￥',precision:2"/>
				</div>

			</div>
            <div class="form-clo1">
                <div class="name"></div>
                <div class="ctl">
                    <div class="tag">提示：正数-期初存款，负数-期初欠款。</div>
                </div>
            </div>
			<div class="form-clo1">
				<div class="name">业务日期:</div>
				<div class="ctl">
					<input id="create_time" name="create_time" type="text" class="easyui-datebox" data-options="prompt:'选择日期...',required:true,editable:false" style="width:100%;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
			<div class="form-clo1">
				<div class="name">备注:</div>
				<div class="ctl">
					<input id="remark" name="remark" class="easyui-textbox" data-options="validType:'length[0,50]',multiline:true" style="width:100%;height:90px;padding:5px;"/>
				</div>
				<div class="kbi_c"></div>
			</div>
		
		</form>
	</div>
    <div id="win_finance_supplier_begin_footer" style="text-align:right;padding:5px;overflow:hidden;height:50px;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.fs.winBegin.save" style="width:100%;height:100%;">提交</a>
    </div>
</div>