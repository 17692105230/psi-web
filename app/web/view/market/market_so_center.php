<div class="easyui-layout" data-options="fit:true">
    <div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:135px;background-color:#eee;"
         data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false,
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '33.33%';
				if (w <= 2000 && w > 1500) {
					width = '33.33%';
				} else if (w <= 1500 && w > 1100) {
					width = '33.33%';
				} else if (w <= 1100 && w > 800) {
					width = '33.33%';
				} else if (w <= 800 && w > 440) {
					width = '50%';
				} else {
					width = '100%';
				}
				$('.kbi_column_left.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
	">
        <header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">销售订单 - <span id="orders_code_view" style="font-size:10pt;color:blue;">新订单</span>
            </div>
            <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.sp.mDelOrders">删除订单</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.sp.mNewOrders">新建订单</a>
            </div>
        </header>
        <form id="orders_form" method="post">
            <div class="form-clo2">
                <div class="name">本单金额:</div>
                <div class="ctl">
                    <input id="action_name" name="action_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
                </div>
                <div class="name">客户欠款:</div>
                <div class="ctl">
                    <input id="client_debt_now" name="client_debt_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
                </div>
                <div class="name">客户:</div>
                <div class="ctl">
                    <input class="easyui-combobox"
                           name="coustromlists"
                           data-options="
                            url:$.toUrl('market', 'loadClient'),
                            method:'get',
                            valueField:'id',
                            textField:'name',
                            panelHeight:'auto'
			            " style="width: 100%">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name">备注:</div>
                <div class="ctl">
                    <input id="controller_name" name="controller_name" type="text" class="easyui-textbox" data-options="required:false" style="width:100%;" />
                </div>
            </div>
        </form>
    </div>
    <div data-options="region:'center'" style="overflow:hidden;border-top:1px solid #ddd;">
        <table id="orders_grid_details" class="easyui-datagrid" style="width:100%;" title=""
               data-options="
				fit:true,
				fitColumns:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				rownumbers:true,
				border:false,
				toolbar:'#orders_grid_details_toolbar',
				footer:'#orders_grid_details_footer',
				method:'get',
                onBeforeLoad:$.h.sp.mGrid.onBeforeLoad,
				onClickCell:$.h.sp.mGrid.onClickCell,
                onEndEdit:$.h.sp.mGrid.onEndEdit,
				onAfterEdit:$.h.sp.mGrid.onAfterEdit,
				checkOnSelect:false,
				selectOnCheck:false,
                onKeyUpDownEvent:$.h.sp.mGrid.onClickCell
			">
            <thead>
            <tr>
                <th data-options="field:'details_id',checkbox:true"></th>
                <!-- th data-options="
                    field:'id',
                    formatter:function(value,row,index) {
                        return '<a href=\'javascript:$.H.PL.deleteCommodity('+index+');\' class=\'datagrid-row-del\' style=\'display:inline-block;width:16px;height:16px;\'></a>';
                    }
                "></th -->
                <th data-options="field:'goods_name',width:120,sortable:true"><b>商品</b></th>
                <th data-options="field:'goods_barcode',width:120,sortable:true"><b>条码</b></th>
                <th data-options="field:'goods_code',width:120,sortable:true"><b>货号</b></th>
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
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>单价</b></th>
                <th data-options="
							field:'goods_tmoney',
							width:60,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>金额</b></th>
            </tr>
            </thead>
        </table>
        <div id="orders_grid_details_toolbar" style="height:33px;padding:1px;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.sp.mDelGoods">删除</a>
            <select id="search_keyword" class="easyui-combogrid" data-options="
                    ordersType: 'sp',
					icons:[{
                        iconCls:'icon-clear',
                        handler:$.h.c.clearCombogrid
                    }],
					buttonIcon:'icon-search',
                    onClickButton:function() {
                        var target = $('#search_keyword');
                        $.h.sp.searchGoods(target);
                    },
					width:280,
					panelWidth: 800,
					panelHeight: 320,
					idField: 'goods_code',
					textField: 'goods_name',
					method: 'get',
                    loadMsg:'数据加载中...',
					fitColumns: true,
					striped: true,
					editable: true,
					pagination: true,
                    pageSize: 10,
					pageList: [10,20,30,50],
					rownumbers: true,
					hasDownArrow: false,
					delay: 0,
					prompt:'请输入商品名称、货号、条码......',
					searchUrl:'/manage/goods/sSaleOrdersGoods',
					columns: [[
						{field:'goods_id',hidden:true},
						{field:'goods_name',title:'名称',width:160},
						{field:'goods_code',title:'货号',width:100},
						{field:'goods_barcode',title:'条码',width:100},
						{field:'goods_wprice',title:'批发价',width:80}
					]],
					onShowPanel:function() {
						$('#orders_grid_details').datagrid('unbindKeyEvent');
					},
					onHidePanel:function() {
						$('#orders_grid_details').datagrid('bindKeyEvent');
					},
					onClickRow:function() {
						var target = $('#search_keyword');
						target.combogrid('panel').panel('options').closed = false;
						$.h.sp.searchGoods(target);
					}
				">
            </select>
            <input class="easyui-switchbutton" data-options="
                onText:'查询销售',
                offText:'扫码销售',
                width:100,
                checked:true,
                onChange:$.h.s.goods.mQueryMode
                ">

            <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.sp.mSaveFormally">提交销售</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.sp.mSaveRoughDraft">保存草稿</a>
            </div>
        </div>
        <div id="orders_grid_details_footer" style="padding:2px 5px;">
            <div style="float:left;">
                <input id="orders_user" class="easyui-combobox"
                       data-options="
						labelWidth:50,
						label:'销售员',
						width:200,
						editable:false,
						panelHeight:120,
						valueField:'user_code',
						textField:'user_name'
					" />
            </div>
            <div style="float:right;">
                <input id="goods_number" class="easyui-numberbox" data-options="label:'合计数量',labelWidth:65,width:130,readonly:true,precision:0,value:0"/>
                <input id="orders_pmoney" class="easyui-numberbox" data-options="label:'合计金额',labelWidth:65,width:180,readonly:true,prefix:'￥',precision:2,value:0"/>
            </div>
        </div>
    </div>
</div>