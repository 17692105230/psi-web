  <div class="easyui-panel" style="padding:10px;background-color:#eee;"
	data-options="
		fit:true,
		border:false,
		onResize:function(w, h) {
			w = $(this).panel('panel').width();
			var width = '50%';
			if (w <= 2000 && w > 1500) {
				width = '20%';
			} else if (w <= 1500 && w > 1100) {
				width = '25%';
			} else if (w <= 1100 && w > 800) {
				width = '33.33%';
			} else if (w <= 800 && w > 440) {
				width = '50%';
			} else {
				width = '100%';
			}
			$('.kbi_column_left_100.a').each(function(i,n) {
				$(n).css('width', width);
			});
		}
	">
        <form id="query_form" method="get">
        <div class="form-clo1">
			<div class="name">供应商:</div>
			<div class="ctl">
                <select id="search_supplier_id" name="search_supplier_id" class="easyui-combogrid" style="width:100%"
                    data-options="
                        panelWidth: 520,
                        panelHeight: 310,
                        idField: 'supplier_id',
                        textField: 'supplier_name',
                        loadMsg:'加载数据中...',
                        prompt:'请选择供应商......',
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
                        fitColumns: true
                    ">
                </select>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">开始日期:</div>
			<div class="ctl">
				<input id="search_begin_date" name="search_begin_date" type="text" class="easyui-datebox" data-options="required:true,prompt:'选择日期...',editable:false" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">结束日期:</div>
			<div class="ctl">
				<input id="search_end_date" name="search_end_date" type="text" class="easyui-datebox" data-options="required:true,prompt:'选择日期...',editable:false" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">账目类别:</div>
			<div class="ctl">
				<input name="search_account_id" class="easyui-combobox"  style="width:100%;"
                    data-options="
                        valueField:'dict_id',
                        textField:'dict_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        prompt:'请选择账目类别...',
                        url:'/web/dict/loadAccountListY'
                    " />
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">结算账户:</div>
			<div class="ctl">
				<input name="search_settlement_id" class="easyui-combobox" style="width:100%;"
                    data-options="
                        valueField:'settlement_id',
                        textField:'settlement_name',
                        panelHeight:'auto',
                        panelMaxHeight:200,
                        editable:false,
                        prompt:'选择结算账户...',
                        url:'/web/settlement/loadCombobox'
                    " />
			</div>
		</div>
        <div class="form-clo1">
			<div class="name">单号:</div>
			<div class="ctl">
				<input name="search_orders_code" type="text" class="easyui-textbox" data-options="prompt:'单据编号...'" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">操作人:</div>
			<div class="ctl">
				<input name="search_user_id" class="easyui-combobox" style="width:100%;"
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
                <a id="btnEnter" class="easyui-linkbutton" style="width:100%;height:30px;">重置</a>
            </div>
			<div class="ctl">
				<a id="btnQuery" class="easyui-linkbutton" data-options="iconCls:'icon-search',onClick:$.h.fs.btnSupplierRecord" style="width:100%;height:30px;">查询</a>
			</div>
		</div>
        </form>

</div>