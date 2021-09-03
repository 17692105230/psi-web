<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:130px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false

	">
		<header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">库存盘点单 - <span id="orders_code_view" style="font-size:10pt;color:blue;">新单据</span>
            </div>
            <div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.si.mDelOrders">删除订单</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.si.mNewOrders">新建订单</a>
            </div>
        </header>
		<form id="orders_form" method="post">
            <div class="form-clo1">
                <div class="name">仓库:</div>
                <div class="ctl">
                    <input id="warehouse_id" name="warehouse_id" class="easyui-combobox" style="width:100%;"
                           data-options="
                                valueField:'org_id',
                                textField:'org_name',
                                panelHeight:120,
                                editable:false,
                                required:true,
                                panelHeight:'auto',
                                url:'/web/organization/loadWarehouseList',
								onChange:$.h.si.mChangeWarehouse
                            " />
                </div>
                <div class="name"></div>
                <div class="ctl"></div>
                <div class="name"></div>
                <div class="ctl"></div>
            </div>
            <div class="form-clo1">
                <div class="name">备注:</div>
                <div class="ctl">
                    <input id="orders_remark" name="orders_remark" class="easyui-textbox" data-options="prompt:'请输入备注......'" style="width:100%;"/>
                </div>
            </div>
        </form>
	</div>
	<div data-options="region:'center'" style="overflow:hidden;border-top:1px solid #ddd;">
        <table id="orders_grid_details" class="easyui-datagrid" style="width:100%;"
               data-options="
                fit:true,
                fitColumns:true,
                iconCls:'kbi-icon-record',
                singleSelect:true,
                rownumbers:true,
                pagination: true,
                pageSize: 20,
                pageList: [20,30,50,100],
                border:false,
                toolbar:'#orders_grid_details_toolbar',
                method:'post',
                onBeforeLoad:$.h.si.mGrid.onBeforeLoad,
                onClickCell:$.h.si.mGrid.onClickCell,
                onEndEdit:$.h.si.mGrid.onEndEdit,
                onAfterEdit:$.h.si.mGrid.onAfterEdit,
                onAfterLoad:$.h.si.mGrid.onAfterLoad,
                checkOnSelect:false,
                selectOnCheck:false,
                onKeyUpDownEvent:$.h.si.mGrid.onClickCell
            ">
            <thead>
                <tr>
                    <th data-options="field:'details_id',checkbox:true"></th>
                    <th data-options="field:'goods_name',width:200,sortable:true"><b>商品</b></th>
                    <th data-options="field:'goods_barcode',width:200,sortable:true"><b>条码</b></th>
                    <th data-options="field:'goods_code',width:200,sortable:true"><b>货号</b></th>
                    <th data-options="
                            field:'color_id',
                            editor:{
                                type:'combobox',
                                options:{
                                    editable:false,
                                    panelHeight:80,
                                    valueField:'color_id',
                                    textField:'color_name',
                                    panelHeight:'auto',
                                    panelMaxHeight:200
                                }
                            },
                            fixed:true,
                            width:120,
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
                                    panelHeight:80,
                                    valueField:'size_id',
                                    textField:'size_name',
                                    panelHeight:'auto',
                                    panelMaxHeight:200
                                }
                            },
                            fixed:true,
                            width:120,
                            align:'center',
                            formatter:function(value,row,index) {
                                return row.size_name;
                            }
                        "><b>尺码</b></th>
                    <th data-options="
                            fixed:true,
                            width:100,
                            field:'goods_number',
                            editor:{
                                type:'numberbox'
                            },
                            align:'center'
                        "><b>盘点数量</b></th>
                    <th data-options="
                            field:'goods_anumber',
                            fixed:true,
                            width:100,
                            align:'center'
                        "><b>盘点前数量</b></th>
                </tr>
            </thead>
        </table>
        <div id="orders_grid_details_toolbar" style="height:33px;padding:1px;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.si.mDelGoods">删除</a>
            <select id="search_keyword" class="easyui-combogrid" data-options="
                    ordersType: 'si',
                    icons:[{
                        iconCls:'icon-clear',
                        handler:$.h.c.clearCombogrid
                    }],
                    buttonIcon:'icon-search',
                    onClickButton:function() {
                        var target = $('#search_keyword');
                        $.h.si.searchGoods(target);
                    },
                    width:280,
                    panelWidth:800,
                    panelHeight:320,
                    idField:'goods_code',
                    textField:'goods_name',
                    method:'get',
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
                    searchUrl:'/web/goods/sGeneralOrdersGoods',
                    columns: [[
                        {field:'goods_id',hidden:true},
                        {field:'goods_name',title:'名称',width:160},
                        {field:'goods_code',title:'货号',width:100},
                        {field:'goods_barcode',title:'条码',width:100}
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
                        $.h.si.searchGoods(target);
                    }
                ">
            </select>
            <input class="easyui-switchbutton" data-options="onText:'查询',offText:'扫码',width:80,checked:true,onChange:$.h.s.goods.mQueryMode"/>
            <span style="color:#ccc;">|</span>
            <input id="children_orders" class="easyui-combobox" data-options="
                buttonText:'盘点人',
                buttonAlign:'left',
                editable:false,
                panelHeight:'auto',
                panelMaxHeight:200,
                width:260,
                valueField:'children_code',
                textField:'children_name',
                icons: [{
                    iconCls:'icon-add',
                    handler:$.h.si.mGrid.onAddUserChildrenOrders
                },{
                    iconCls:'icon-remove',
                    handler:$.h.si.mGrid.onDelUserChildrenOrders
                }],
                onSelect:$.h.si.mGrid.onSelectChildrenOrders
            "/>
            <div style="float:right;">
                <a id="btnGridQuery" class="easyui-linkbutton easyui-tooltip"
                    data-options="
                        showEvent:'click',
                        hideEvent:'none',
                        iconCls:'icon-search',
                        plain:true,
                        position:'bottom',
                        content: function(){
                            return $('#btn_grid_query_div');
                        },
                        onShow: function(){
                            var t = $(this);
                            $(document).one('click', function(e) { t.tooltip('hide'); });
                            t.tooltip('tip').click(function() { event.stopPropagation(); });
                        }
                    ">搜索商品</a>
                <span style="color:#ccc;">|</span>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.si.mSaveFormally">提交销售</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.si.mSaveRoughDraft">保存草稿</a>
            </div>
        </div>
        <div style="display:none">
            <div id="btn_grid_query_div" class="easyui-panel" data-options="width:300,height:228" style="padding:10px 5px 0px 5px;">
                <form id="btn_grid_query_from" method="post">
                    <div class="form-clo2">
                            <div class="name">商品:</div>
                            <div class="ctl">
                                <input id="ws_goods" name="ws_goods" class="easyui-textbox" data-options="width:200,prompt:'名称、货号、条码...'"/>
                            </div>
                        </div>

                    <div class="form-clo2">
                            <div class="name">商品颜色:</div>
                            <div class="ctl">
                                <input id="ws_color_id" name="ws_color_id" class="easyui-combobox"
                                    data-options="
                                        valueField:'color_id',
                                        textField:'color_name',
                                        panelHeight:'auto',
                                        panelMaxHeight:200,
                                        width:200,
                                        editable:false,
                                        prompt:'选择颜色...',
                                        url:'/web/color/loadCombobox'
                                    "/>
                            </div>
                    </div>
                        <div class="form-clo2">
                            <div class="name">商品尺码:</div>
                            <div class="ctl">
                                <input id="ws_size_id" name="ws_size_id" class="easyui-combobox"
                                    data-options="
                                        valueField:'size_id',
                                        textField:'size_name',
                                        panelHeight:'auto',
                                        panelMaxHeight:200,
                                        width:200,
                                        editable:false,
                                        prompt:'选择颜色...',
                                        url:'/web/size/loadCombobox'
                                    "/>
                            </div>
                        </div>
                        <div class="form-clo2">
                            <div class="name">盈亏状态:</div>
                            <div class="ctl">
                                <input id="ws_gain_loss" name="ws_gain_loss" class="easyui-combobox" data-options="
                                    editable:false,
                                    prompt:'请选择...',
                                    panelHeight:'auto',
                                    panelMaxHeight:380,
                                    width:200,
                                    valueField:'value',
                                    textField:'label',
                                    data: [{label:'盘盈',value:'1'},{label:'正常',value:'2'},{label:'盘亏',value:'3'}]"
                                />
                            </div>
                        </div>
                        <div class="form-clo2">
                            <div class="name" style="padding:2px;">
                                <a class="easyui-linkbutton" data-options="width:60,height:40,onClick:function(e) {$('#btn_grid_query_from').form('reset');}">重置</a>
                            </div>
                            <div class="ctl" style="padding:0px;">
                                <a class="easyui-linkbutton" data-options="iconCls:'icon-search',width:200,height:40,onClick:$.h.si.mGrid.searchDetailsList">查 询</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>
<!--颜色尺寸-->
<div id="win_color_size" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'颜色尺寸',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>