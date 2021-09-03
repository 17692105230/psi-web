<div class="easyui-layout" data-options="fit:true">
    <div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:135px;background-color:#eee;"
         data-options="
			region:'north',
			split:false,
			hideCollapsedContent:false,
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '25%';
				} else if (w <= 1500 && w > 1100) {
					width = '25%';
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
                <span style="font-weight:bold;">销售单 - <span id="orders_code_view" style="font-size:10pt;color:blue;">新单据</span></span>
            </div>
            <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.so.mDelOrders">删除订单</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.so.mNewOrders">新建订单</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',plain:true"></a>
            </div>
        </header>
        <form id="orders_form" method="post">
            <div class="form-clo2">
                <div class="name">客户:</div>
                <div class="ctl">
                    <select id="client_id" name="client_id" class="easyui-combogrid" style='width:100%;' data-options="
                                icons:[{
                                    iconCls:'icon-clear',
                                    handler:function(e) {
                                        var target = $(e.data.target);
                                        target.combogrid('clear');
                                        $('#account_money').numberbox('reset');
                                    }
                                }],
                                panelWidth: 600,
                                panelHeight: 320,
								toolbar:'#client_id_toolbar',
                                required:true,
                                idField:'client_id',
                                textField:'client_name',
								loadMsg:'加载数据中...',
                                method:'get',
                                fitColumns:true,
                                striped:true,
                                editable:false,
                                pagination:true,			//是否分页
                                rownumbers:true,			//序号
                                delay: 0,
                                prompt:'请选择客户......',
                                url:'/manage/client/loadList',
                                columns: [[
                                    {field:'client_id',hidden:true},
                                    {field:'client_name',title:'客户名称',width:100,align:'left'},
                                    {field:'client_phone',title:'客户手机',width:100},
                                    {field:'client_discount',title:'默认折扣',width:90,align:'center'},
                                    {field:'account_money',title:'账户金额',width:100}
                                ]]
                            ">
                    </select>
                    <div id="client_id_toolbar" style="height:30px;padding:1px;">
                        <input id="search_orders_client" class="easyui-textbox"
                               data-options="
                                    buttonText:'查询',
                                    buttonIcon:'icon-search',
                                    prompt:'名称、手机、卡号...',
                                    onClickButton:$.h.so.searchCustomer,
                                    onKeyDownEnter:$.h.so.searchCustomer
                                " style="width:100%;" />
                    </div>
                </div>
                <div class="name">销售员:</div>
                <div class="ctl">
                    <input id="orders_salesman_id" name="salesman_id" class="easyui-combobox"  style="width:100%;"
                       data-options="
                            required:true,
                            prompt:'选择销售员...',
                            editable:false,
                            panelHeight:'auto',
                            panelMaxHeight:160,
                            valueField:'user_id',
                            textField:'user_name',
                            url:'/manage/user/loadUserCombobox'
                    "/>
                </div>
                <div class="name" style="font-weight:bold;color:brack">实收金额:</div>
                <div class="ctl">
                    <input id="orders_rmoney" name="orders_rmoney" class="easyui-numberspinner" style="width:100%;" value="0.00" data-options="min:0,max:9999999,prefix:'￥',precision:2"/>
                </div>
                <div class="name">日期:</div>
                <div class="ctl">
                    <input id="orders_date" name="orders_date" class="easyui-datebox" data-options="required:true" value="(new Date()).Format('yyyy-MM-dd')" style="width:100%;"/>
                </div>
            </div>
            <div class="form-clo2">
                <div class="name">仓库:</div>
                <div class="ctl">
                    <input id="warehouse_id" name="warehouse_id" class="easyui-combobox" style="width:100%;"
                       data-options="
                        prompt:'选择仓库...',
                        valueField:'org_id',
                        textField:'org_name',
                        panelHeight:120,
                        editable:false,
                        required:true,
                        url:'/manage/organization/loadWarehouseList'
                    "/>
                </div>
                <div class="name">结算账户:</div>
                <div class="ctl">
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
                    "/>
                </div>
                <div class="name">发货方式:</div>
                <div class="ctl">
                    <input id="delivery_id" name="delivery_id" class="easyui-combobox" style="width:100%;"
                       data-options="
                            prompt:'选择发货方式...',
                            valueField:'dict_id',
                            textField:'dict_name',
                            panelHeight:135,
                            editable:false,
                            required:true,
                            url:'/manage/dict/loadList?dict_type=delivery'
                    "/>
                </div>
                <div class="name">备注:</div>
                <div class="ctl">
                    <input id="orders_remark" name="orders_remark" class="easyui-textbox" data-options="prompt:'请输入备注......'" style="width:100%;"/>
                </div>
            </div>

        </form>
    </div>
    <div data-options="region:'center'" style="overflow:hidden;">
        <table id="orders_grid_details" class="easyui-datagrid" style="width:100%;"
               data-options="
                fit:true,
				fitColumns:true,
				rownumbers:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
                checkOnSelect:false,
                selectOnCheck:false,
				border:false,
				toolbar:'#orders_grid_details_toolbar',
				footer:'#orders_grid_details_footer',
				method:'get',
				onBeforeLoad:$.h.so.mGrid.onBeforeLoad,
				onClickCell:$.h.so.mGrid.onClickCell,
				onEndEdit:$.h.so.mGrid.onEndEdit,
				onAfterEdit:$.h.so.mGrid.onAfterEdit,
				rownumbers:true,
				multiSort:true,
				remoteSort:false,
                onKeyUpDownEvent:$.h.so.mGrid.onClickCell
			">
            <thead>
            <tr>
                <th data-options="field:'goods_id',checkbox:true"></th>
                <!--th data-options="
                    field:'id',
                    formatter:function(value,row,index) {
                        return '<a href=\'javascript:$.h.PL.mDelGoods('+index+');\' class=\'datagrid-row-del\' style=\'display:inline-block;width:16px;height:16px;\'></a>';
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
        <div id="orders_grid_details_toolbar" style="height:33px;padding:1px;">
            <div style="float:left;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.so.mDelGoods">删除</a>
                <select id="search_keyword" class="easyui-combogrid" data-options="
                        ordersType: 'so',
                        icons:[{
                                iconCls:'icon-clear',
                                handler:$.h.c.clearCombogrid
                            }],
                        buttonIcon:'icon-search',
                        onClickButton:function() {
                            var target = $('#search_keyword');
                            $.h.so.searchGoods(target);
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
                            $.h.so.searchGoods(target);
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
            </div>
            <div style="float:right;">

                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.so.mSaveFormally">提交销售</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.so.mSaveRoughDraft">保存草稿</a>
            </div>
        </div>

        <div id="orders_grid_details_footer" style="padding:2px 5px;">
            <div style="float:left;">
                <input id="orders_other_type" class="easyui-combobox"
                       data-options="
                        valueField:'dict_id',
                        textField:'dict_name',
                        width:200,
                        label:'<font color=blue><strong>其它费用</strong></font>',
                        labelWidth:65,
                        panelHeight:'auto',
                        editable:false,
                        prompt:'请选择......',
                        url:'/manage/dict/loadAccountListY'
                    " /input>
                <input id="orders_other_money" class="easyui-numberbox"
                       data-options="
                        label:'<font color=blue><strong>金额</strong></font>',
                        labelWidth:35,
                        width:160,
                        prefix:'￥',
                        precision:2,
                        value:0,
                        onChange:$.h.so.onOtherMoneyChange
                    "/>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true">附件</a>
            </div>
            <div style="float:right;">
                <input id="orders_erase_money" class="easyui-numberbox" v-ez-model="message"
                       data-options="
                        label:'<font color=blue><strong>抹零</strong></font>',
                        labelWidth:35,
                        width:130,
                        prefix:'￥',
                        precision:2,
                        value:0,
                        onChange:$.h.so.onEraseMoneyChange
                    "/>
                <input id="goods_number" class="easyui-numberbox"
                       data-options="
                        label:'合计数量',
                        labelWidth:65,
                        width:130,
                        readonly:true,
                        precision:0,
                        value:0
                    "/>
                <input id="orders_pmoney" class="easyui-numberbox"
                       data-options="
                        label:'合计金额',
                        labelWidth:65,
                        width:190,
                        readonly:true,
                        prefix:'￥',
                        precision:2,
                        value:0,
                        onChange:$.h.so.onGoodsMoneyChange
                    "/>
            </div>
        </div>
        <div id="mPrivateMenu" class="easyui-menu" data-options="onClick:$.h.c.handleCommboboxMenu"></div>
    </div>
</div>