<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:90px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false
	">
		<header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">采购订单 - <span id="orders_code_view" style="font-size:10pt;color:blue;">新建计划</span>
            </div>
            <div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.pp.mDelPlans">删除订单</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.pp.mNewPlans">新建订单</a>
            </div>
        </header>
		<form id="plans_form" method="post">
			<div class="form-clo2">
					<div class="name w120">供应商:</div>
					<div class="ctl w120">
						<select id="supplier_id" name="supplier_id" class="easyui-combogrid" style="width:100%"
                            data-options="
                                panelWidth: 550,
                                panelHeight: 310,
                                idField: 'supplier_id',
                                textField: 'supplier_name',
                                pagination: true,
                                pageSize: 10,
					            pageList: [10,20,30,50],
                                rownumbers: true,
                                url:'/web/supplier/loadGrid',
                                method: 'get',
                                editable:false,
                                required:true,
                                columns: [[
                                    {field:'supplier_id',hidden:true},
                                    {field:'supplier_name',title:'<strong>供应商名称</strong>',width:120},
                                    {field:'supplier_discount',fixed:true,width:50,title:'<strong>折扣</strong>',align:'center'},
                                    {field:'supplier_money',title:'<strong>账户金额</strong>',fixed:true,width:150,align:'right'},
                                    {field:'supplier_status',fixed:true,width:50,title:'<strong>状态</strong>',align:'center',formatter:function(value,row,index){return value ? '正常' : '禁用';}}
                                ]],
                                fitColumns: true,
                                onChange:$.h.pp.ordersDataCache
                            ">
                        </select>
					</div>
					<div class="name w120">仓库:</div>
					<div class="ctl w120">
						<input id="warehouse_id" name="warehouse_id" class="easyui-combobox" style="width:100%;"
							data-options="
								valueField:'org_id',
                                textField:'org_name',
								panelHeight:'auto',
                                panelMaxHeight:200,
								editable:false,
								required:true,
								url:'/web/organization/loadWarehouseList',
								onChange:$.h.pp.ordersDataCache
							" /input>
					</div>
					<div class="name w120">日期:</div>
					<div class="ctl w120">
						<input id="orders_date" name="orders_date" class="easyui-datebox" data-options="required:true,value:'',onChange:$.h.pp.ordersDataCache" style="width:100%;"/>
					</div>
					<div class="name w120">备注:</div>
					<div class="ctl w120">
						<input id="orders_remark" name="orders_remark" class="easyui-textbox" data-options="prompt:'请输入备注......',onChange:$.h.pp.ordersDataCache" style="width:100%;"/>
					</div>
			</div>
		</form>
	</div>
	<div data-options="region:'center'" style="overflow:hidden;border-top:1px solid #ddd;">
		<table id="plans_grid_details" class="easyui-datagrid" style="width:100%;" title=""
			data-options="
				fit:true,
				fitColumns:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				rownumbers:true,
				border:false,
				toolbar:'#plans_grid_details_toolbar',
				footer:'#plans_grid_details_footer',
				method:'get',
				onBeforeLoad:$.h.pp.mGrid.onBeforeLoad,
				onClickCell:$.h.pp.mGrid.onClickCell,
                onEndEdit:$.h.pp.mGrid.onEndEdit,
				onAfterEdit:$.h.pp.mGrid.onAfterEdit,
				checkOnSelect:false,
				selectOnCheck:false,
                onKeyUpDownEvent:$.h.pp.mGrid.onClickCell,
                onChange:$.h.pp.ordersDataCacheGrid,
                onLoadSuccess:$.h.pp.ordersDataCacheGrid,
			">
			<thead>
				<tr>
					<th data-options="field:'goods_id',checkbox:true"></th>
					<!-- th data-options="
						field:'id',
						formatter:function(value,row,index) {
							return '<a href=\'javascript:$.h.pp.deleteCommodity('+index+');\' class=\'datagrid-row-del\' style=\'display:inline-block;width:16px;height:16px;\'></a>';
						}
					"></th -->
					<th data-options="field:'goods_name',width:110,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:70,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:90,sortable:true"><b>货号</b></th>
					<th data-options="
							field:'color_id',
							editor:{
								type:'combobox',
								options:{
									editable:false,
									method:'get',
									valueField:'color_id',
									textField:'color_name',
                                    panelHeight:'auto',
                                    panelMaxHeight:200
								}
							},
							width:50,
							align:'center',
							formatter:function(value,row,index) {
								return row.color_name;
							}
						"><b>颜色</b></th>
					<th data-options="
							field:'size_id',
							editor:{
								type:'combobox',
								options:{
									editable:false,
									method:'get',
									valueField:'size_id',
									textField:'size_name',
                                    panelHeight:'auto',
                                    panelMaxHeight:200
								}
							},
							width:50,
							align:'center',
							formatter:function(value,row,index) {
								return row.size_name;
							}
						"><b>尺码</b></th>
					<th data-options="
							field:'goods_number',
							editor:{
								type:'numberbox'
							},
                            fixed:true,
                            width:60,
							align:'center'
						"><b>数量</b></th>
					<th data-options="
							field:'goods_price',
							width:60,
							align:'right',
							editor:{
								type:'numberbox',
								options:{
                                    buttonIcon:'hjtr-money',
                                    prefix:'￥',
									precision:2,
                                    onClickButton:function() {
                                        var mPrivateMenu = $('#mPrivateMenu');
                                        mPrivateMenu.menu('options').tag = $(this);
                                        mPrivateMenu.menu('show', {
                                            left: $(this).parent().offset().left,
                                            top: $(this).parent().offset().top + $(this).parent().height()
                                        });
                                    }
								}
							},
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>单价</b></th>
					<th data-options="
							field:'goods_discount',
                            fixed:true,
							width:50,
							align:'center',
							editor:{
								type:'numberbox',
								options:{
									min:0,
									max:100
								}
							},
							formatter:function(value, row) {
								if (value == null) {
									return 85;
								} else {
									return value;
								}
							}
						"><b>折扣</b></th>
					<th data-options="
							field:'goods_daprice',
							width:60,
							align:'right',
							editor:{
								type:'numberbox',
								options:{
									buttonIcon:'hjtr-money',
                                    prefix:'￥',
									precision:2,
                                    onClickButton:function() {
                                    }
								}
							},
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>折后价</b></th>
					<th data-options="
							field:'goods_tmoney',
							width:60,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>金额</b></th>
					<th data-options="
							field:'goods_tdamoney',
							width:60,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>折后金额</b></th>
				</tr>
			</thead>
		</table>
		<div id="plans_grid_details_toolbar" style="height:33px;padding:1px;">
			<a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.pp.mDelGoods">删除商品</a>
			<select id="search_keyword" class="easyui-combogrid" data-options="
                    ordersType: 'pp',
					icons:[{
                        iconCls:'icon-clear',
                        handler:$.h.c.clearCombogrid
                    }],
                    buttonIcon:'icon-search',
                    onClickButton:function() {
                        var target = $('#search_keyword');
                        $.h.pp.searchGoods(target);
                    },
                    width:280,
					panelWidth: 800,
					panelHeight: 320,
					idField: 'goods_code',
					textField: 'goods_name',
                    loadMsg:'数据加载中...',
					method: 'get',
					fitColumns: true,
					striped: true,
					editable: true,  
					pagination: true,
					rownumbers: true,
					hasDownArrow: false,
					delay: 1000,
					prompt:'请输入商品名称、货号、条码......',
					searchUrl:'/web/goods/sPurchaseOrdersGoods',
					columns: [[
						{field:'goods_id',hidden:true},
						{field:'goods_name',title:'名称',width:160},
						{field:'goods_code',title:'货号',width:100},
						{field:'goods_barcode',title:'条码',width:100},
						{field:'goods_pprice',title:'采购价',width:80}
					]],
					onShowPanel:function() {
						$('#plans_grid_details').datagrid('unbindKeyEvent');
					},
					onHidePanel:function() {
						$('#plans_grid_details').datagrid('bindKeyEvent');
					},
					onClickRow:function() {
						var target = $('#search_keyword');
						target.combogrid('panel').panel('options').closed = false;
						$.h.pp.searchGoods(target);
					}
				">
			</select>
			<input class="easyui-switchbutton" data-options="onText:'查询销售',offText:'扫码销售',width:100,checked:true,onChange:$.h.s.goods.mQueryMode"/>
			<div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.pp.mSaveFormally">提交采购</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.pp.mSaveRoughDraft">保存草稿</a>
			</div>
		</div>
		<div id="plans_grid_details_footer" style="padding:2px 5px;">
			<div style="float:left;">
			</div>
			<div style="float:right;">
				<input id="goods_number" class="easyui-numberbox" data-options="label:'合计数量',labelWidth:65,width:130,readonly:true,precision:0,value:0,onChange:$.h.pp.ordersDataCache"/>
				<input id="orders_pmoney" class="easyui-numberbox" data-options="
                    label:'合计金额',
                    labelWidth:65,
                    width:180,
                    readonly:true,
                    prefix:'￥',
                    precision:2,
                    value:0,
                    onChange:$.h.pp.ordersDataCache"/>
			</div>
		</div>
        <div id="mPrivateMenu" class="easyui-menu" data-options="onClick:$.h.c.handleCommboboxMenu"></div>
	</div>
</div>
<!--颜色尺寸-->
<div id="win_color_size" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'颜色尺寸',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>