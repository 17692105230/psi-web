<div class="easyui-layout" data-options="fit:true">
    <div class="easyui-panel" title="销售退货单" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:170px;background-color:#eee;"
         data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false
	">
        <header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">销售退货单 - <span id="orders_code_view" style="font-size:10pt;color:blue;">新单据</span></span>
            </div>
            <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.sro.mDelOrders">删除单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.sro.mNewOrders">新建单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',plain:true"></a>
            </div>
        </header>
        <form id="orders_form" method="post">
            <div class="form-clo2">
                <div class="name w120">客户:</div>
                <div class="ctl w120">
                    <select id="client_id" name="client_id" class="easyui-combogrid" style="width:100%"
                            data-options="
                                toolbar:'#client_id_toolbar',
                                panelWidth: 550,
                                panelHeight: 310,
                                idField:'client_id',
                                textField:'client_name',
                                rownumbers: true,
                                method: 'get',
                                prompt:'请选择客户......',
                                editable:false,
                                required:true,
                                url:'/web/client/loadClientCombox',
                                columns: [[
                                    {field:'client_id',hidden:true},
                                    {field:'client_name',title:'客户名称',width:100,align:'left'},
                                    {field:'client_phone',title:'客户手机',width:100},
                                    {field:'client_discount',title:'默认折扣',width:90,align:'center'},
                                    {field:'account_money',title:'账户金额',width:100}
                                ]],
                                fitColumns: true
                            ">
                    </select>
                </div>
                <div class="name w120">销售员:</div>
                <div class="ctl w120">
                    <input id="orders_salesman_id" name="salesman_id" class="easyui-combobox"  style="width:100%;"
                           data-options="
                                required:true,
                                prompt:'选择销售员...',
                                editable:false,
                                panelHeight:'auto',
                                panelMaxHeight:160,
                                valueField:'user_id',
                                textField:'user_name',
                                url:'/web/user/loadUserCombobox'
                            " />

                </div>
                <div class="name w120">仓库:</div>
                <div class="ctl w120">
                    <input id="warehouse_id" name="warehouse_id" class="easyui-combobox" style="width:100%;"
                           data-options="
								valueField:'org_id',
                                textField:'org_name',
								panelHeight:'auto',
								editable:false,
								required:true,
								url:'/web/organization/loadWarehouseList'
							" />
                </div>
                <div class="name w120">日期:</div>
                <div class="ctl w120">
                    <input id="orders_date" name="orders_date" class="easyui-datebox" data-options="required:true,value:''" style="width:100%;"/>
                </div>
            </div>
            <div class="form-clo2">
                <div class="name w120">实付金额:</div>
                <div class="ctl w120">
                    <input id="orders_rmoney" name="orders_rmoney" class="easyui-numberspinner" style="width:100%;" value="0.00" data-options="required:true,prefix:'￥',precision:2"/>
                </div>
                <div class="name w120">结算方式:</div>
                <div class="ctl w120">
                    <input id="settlement_id" name="settlement_id" class="easyui-combobox" style="width:100%;"
                           data-options="
                                valueField:'settlement_id',
                                textField:'settlement_name',
                                panelHeight:'auto',
                                panelMaxHeight:200,
                                editable:false,
                                required:true,
                                prompt:'选择结算账户...',
                                url:'/web/settlement/loadCombobox'
                            " />
                </div>
                <div class="name">发货方式:</div>
                <div class="ctl">
                    <input id="delivery_id" name="delivery_id" class="easyui-combobox" style="width:100%;"
                           data-options="
                                valueField:'dict_id',
                                textField:'dict_name',
                                panelHeight:'auto',
                                editable:false,

                                url:'/web/dict/loadList?dict_type=delivery'
                            " />
                </div>
                <div class="name"></div>
                <div class="ctl"></div>
            </div>
            <div class="form-clo2">
                <div class="name">备注:</div>
                <div class="ctl" style="width: 100%">
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
				onBeforeLoad:$.h.sro.mGrid.onBeforeLoad,
				onClickCell:$.h.sro.mGrid.onClickCell,
				onEndEdit:$.h.sro.mGrid.onEndEdit,
				onAfterEdit:$.h.sro.mGrid.onAfterEdit,
				rownumbers:true,
				multiSort:true,
				remoteSort:false,
                onKeyUpDownEvent:$.h.sro.mGrid.onClickCell
			">
            <thead>
            <tr>
                <th data-options="field:'goods_id',checkbox:true"></th>
                <th data-options="field:'goods_type',align:'center',formatter:$.h.sro.mButton.formatter">退/销</th>
                <th data-options="field:'goods_name',width:110,sortable:true"><b>商品</b></th>
                <th data-options="field:'goods_barcode',width:70,sortable:true"><b>条码</b></th>
                <th data-options="field:'goods_code ',width:90,sortable:true"><b>货号</b></th>
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
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.sro.mDelGoods">删除</a>
            <select id="search_keyword" class="easyui-combogrid" data-options="
                    ordersType: 'sro',
                    icons:[{
                            iconCls:'icon-clear',
                            handler:$.h.c.clearCombogrid
                        }],
                    buttonIcon:'icon-search',
                    onClickButton:function() {
                        var target = $('#search_keyword');
                        $.h.sro.searchGoods(target);
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
                    searchUrl:'/web/goods/loadGoodsGrid',
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
                        $.h.sro.searchGoods(target);
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
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.sro.mSaveFormally">提交单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.sro.mSaveRoughDraft">保存草稿</a>
            </div>
        </div>

        <div id="orders_grid_details_footer" style="height:70px;padding:2px 2px;">
            <div style="float:left;height:100%;">
                <table style="width:100%;height:100%;" cellspacing="0" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;">抹零</td>
                        <td style="text-align:left;">
                            <input id="orders_emoney" class="easyui-numberspinner"
                                   data-options="
                                        width:140,
                                        min:0,
                                        max:99999999,
                                        prefix:'￥',
                                        precision:2,
                                        value:0,
                                        onChange:$.h.sro.mEraseChange"/></td>
                        <td colspan="4" style="text-align:left;padding-left:5px;"><div style="color:red;">退销相抵-应<span name="iop_text" style="color:blue">付</span>金额：<span id="orders_rs_money" style="font-weight:bold;">￥0.00</span></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;color:red;">应<span name="iop_text" style="color:blue">付</span>总计</td>
                        <td style="text-align:left;">
                            <input id="orders_pmoney" class="easyui-numberbox"
                                   data-options="
                                        readonly:true,
                                        width:140,
                                        prefix:'￥',
                                        precision:2,
                                        value:0,
                                        onChange:$.h.sro.onGoodsMoneyChange"/></td>
                        <td colspan="4" style="text-align:left;padding-left:5px;"><div style="color:red;">退销相抵-折后应<span name="iop_text" style="color:blue">付</span>金额：<span id="orders_rsd_money" style="font-weight:bold;">￥0.00</span></div></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div style="float:right;height:100%;">
                <table style="width:100%;height:100%;" cellspacing="2" cellpadding="0" border="0">
                    <tbody>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;color:green;padding-right:5px;">销售合计</td>
                        <td style="text-align:right;width:35px;padding-right:5px;color:green;">数量</td>
                        <td style="text-align:left;"><input id="orders_gs_number" class="easyui-numberbox" style="width:60px;" data-options="readonly:true,min:0,max:9999999,precision:0,value:0"/></td>
                        <td style="text-align:right;width:35px;padding-right:5px;color:green;">金额</td>
                        <td style="text-align:left;"><input id="orders_gs_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                        <td style="text-align:right;width:70px;padding-right:5px;color:green;">折后金额</td>
                        <td style="text-align:left;"><input id="orders_gsd_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;color:red;">退货合计</td>
                        <td style="text-align:right;width:35px;font-weight:bold;padding-right:5px;color:red;">数量</td>
                        <td style="text-align:left;"><input id="orders_gr_number" class="easyui-numberbox" style="width:60px;" data-options="readonly:true,min:0,max:9999999,precision:0,value:0"/></td>
                        <td style="text-align:right;width:35px;font-weight:bold;padding-right:5px;color:red;">金额</td>
                        <td style="text-align:left;"><input id="orders_gr_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                        <td style="text-align:right;width:70px;font-weight:bold;padding-right:5px;color:red;">折后金额</td>
                        <td style="text-align:left;"><input id="orders_grd_money" class="easyui-numberbox" style="width:120px;" data-options="readonly:true,min:0,max:9999999,prefix:'￥',precision:2,value:0"/></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="mPrivateMenu" class="easyui-menu" data-options="onClick:$.h.c.handleCommboboxMenu"></div>
    </div>
</div>
<div id="client_id_toolbar" style="height:33px;padding:1px;">
    <input id="search_orders_client" class="easyui-textbox"
           data-options="
                buttonText:'查询',
                buttonIcon:'icon-search',
                prompt:'名称、手机、卡号...',
                onClickButton:$.h.sp.searchClient,
                onKeyDownEnter:$.h.sp.searchClient"
           style="width:100%;" />
</div>
<!--颜色尺寸-->
<div id="win_color_size" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'颜色尺寸',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>