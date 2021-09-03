<div class="easyui-panel"
    data-options="
        fit:true,
        title:'&nbsp;',
        border:false
    ">
    <header class="header-hover">
        <div style="float:left;">
            <a id="btn_search" class="easyui-linkbutton"
				data-options="
					iconCls:'icon-search',
					plain:true,
					onClick:function(e) {
						var p = $('#list_layout');
						if (p.layout('panel','north').panel('options').closed) {
							p.layout('show','north');
						} else {
							p.layout('hide','north');
						}
					}
				">查询</a>
        </div>
        <div style="float:right;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g1',selected:true,plain:true,status:-1,onClick:$.h.si.loadOrdersList" style="color:blue;font-weight:bold">全部</a>
            <a class="easyui-linkbutton" data-options="toggle:true,group:'g1',plain:true,status:1,onClick:$.h.si.loadOrdersList">草稿</a>
            <a class="easyui-linkbutton" data-options="toggle:true,group:'g1',plain:true,status:9,onClick:$.h.si.loadOrdersList">已盘点</a>
        </div>
    </header>
	<div id="list_layout" class="easyui-layout" data-options="fit:true">
		<div style="height:172px;background-color:#eee;padding:10px 10px 0px 10px;border-bottom: 1px solid #ccc;"
			data-options="
				region:'north',
				border:false,
				closed:true
			">
			<form id="list_search_form" method="post">
				
					<div class="form-clo1">
						<div class="name w"><strong>日期</strong> 从:</div>
						<div class="ctl w" style="display:flex;justify-content:space-between;align-items:center;">
                            <div style="width:calc(50% - 15px);">
                                <input name="search_date_begin" class="easyui-datebox" style="width:100%;" />
                            </div>
                            <div style="width:30px; text-align:center;">至</div>
                            <div style="width:calc(50% - 15px);">
							<input name="search_date_end" class="easyui-datebox" style="width:100%;" />
                             </div>
						</div>
					</div>
                    <div class="form-clo1">
                        <div class="name w">仓库:</div>
                        <div class="ctl w">
                            <input name="search_warehouse_name" class="easyui-combobox" style="width:100%;"
                                   data-options="
                                        valueField:'org_id',
                                        textField:'org_name',
                                        editable:false,
                                        panelHeight:'auto',
                                        url:'/web/organization/loadWarehouseList'
                                    "
                            />
                        </div>
                    </div>
					<div class="form-clo1">
						<div class="name w">单号:</div>
						<div class="ctl w">
							<input name="search_orders_code" class="easyui-textbox" style="width:100%;" /input>
						</div>
                    </div>
					<div class="form-clo1">
                        <div class="name w"></div>
                        <div class="ctl w" style="text-align: right">
						<a class="easyui-linkbutton" style="width:70px;margin-right:15px;"
							data-options="
								iconCls:'icon-clear',
								onClick:function(e) {
									$('#list_layout').layout('hide','north');
								}
							">关闭</a>
                        <a class="easyui-linkbutton" style="width:70px;margin-right:15px;"
							data-options="
								iconCls:'icon-reload',
								onClick:function(e) {
									$('#list_search_form').form('reset');
								}
							">重置</a>
						<a class="easyui-linkbutton" data-options="iconCls:'icon-search',onClick:$.h.si.searchOrdersList" style="width:100px;">查询</a>
					</div>
                    </div>
			</form>
		</div>
		<div data-options="region:'center',border:false">
			<table id="orders_list" class="easyui-datagrid"
				data-options="
					fit: true,
					fitColumns:true,
					border: false,
					rownumbers:true,
					singleSelect:true,
					method:'post',
					url:'/web/store/loadInventoryList',
					pagination: true,
					pageSize: 25,
                    showPageList: false,
                    displayMsg: '共 {total} 行',
					onClickCell:$.h.si.onOrdersClickCell,
                    onDblClickRow:$.h.si.onOrdersDblClickRow
					">
				<thead>
					<tr>
						<th data-options="field:'orders_id',checkbox:true"></th>
						<th data-options="
								field:'create_time',
								align:'left',
								fixed:true,
								width:100,
								formatter: function(value,row,index) {
									return $.DT.UnixToDate(value,0,true);
								}
							"><strong>日期</strong></th>
						<th data-options="field:'warehouse_name',align:'left',width:100"><strong>仓库名称</strong></th>
						<th data-options="
								field:'orders_status',
								align:'center',
								fixed:true,
								width:50,
								formatter: function(value,row,index) {
									switch(parseInt(value)) {
										case 0:
											return '<font color=blue>临时</font>';
										case 1:
											return '<font color=blue><b>草稿</b></font>';
										case 9:
											return '<font color=green><b>完成</b></font>';
									}
								}
							"><strong>状态</strong></th>
						<th data-options="field:'orders_id2',align:'center',formatter:$.h.si.modifyFormatter"></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
    
</div>
