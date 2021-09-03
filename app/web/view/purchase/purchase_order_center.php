<div class="easyui-layout" data-options="fit:true">
    <div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:135px;background-color:#eee;"
         data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false,
			onResize: function(w, h) {
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '20%';
				} else if (w <= 1500 && w > 1100) {
					width = '20%';
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
                <span style="font-weight:bold;">采购单 - <span id="orders_code_view" style="font-size:10pt;color:blue;">新建单据</span>
            </div>
            <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.po.mDelOrders">删除单据</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.po.mNewOrders">新建单据</a>
            </div>
        </header>
        <form id="orders_form" method="post">

              <div class="form-clo2">
                    <div class="name w120">供应商:</div>
                    <div class="ctl w120">
                        <select id="supplier_id" name="supplier_id" class="easyui-combogrid" style="width:100%"
                                data-options="
                                panelWidth: 550,
                                panelHeight: 'auto',
                                pagination: true,
                                pageSize: 10,
					            pageList: [10,20,30,50],
                                idField: 'supplier_id',
                                textField: 'supplier_name',
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
                                onChange:$.h.po.ordersDataCache,
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
                                prompt:'选择仓库...',
								url:'/web/organization/loadWarehouseList',
								onChange:$.h.po.ordersDataCache,
							" /input>
                    </div>
                    <div class="name w120">结算账户:</div>
                    <div class="ctl w120">
                        <input id="settlement_id" name="settlement_id" class="easyui-combobox" style="width:100%;"
                               data-options="
                                valueField:'settlement_id',
                                textField:'settlement_name',
                                panelHeight:'auto',
                                panelMaxHeight:200,
                                editable:false,
                                prompt:'选择结算账户...',
                                url:'/web/settlement/loadCombobox',
                                onChange:$.h.po.ordersDataCache,
                            " /input>
                    </div>

              </div>
            <div class="form-clo2">
                    <div class="name w120">日期:</div>
                    <div class="ctl w120">
                        <input id="orders_date" name="orders_date" class="easyui-datebox" data-options="prompt:'选择日期...',required:true,value:'',onChange:$.h.po.ordersDataCache" style="width:100%;"/>
                    </div>
                    <div class="name w120">实付金额:</div>
                    <div class="ctl w120">
                        <input id="orders_rmoney" name="orders_rmoney" class="easyui-numberspinner" style="width:100%;" value="0.00" required="required" data-options="onChange:$.h.po.ordersDataCache,prompt:'￥0.00',min:0,max:9999999,prefix:'￥',precision:2"/>
                    </div>
                    <div class="name w120">备注:</div>
                    <div class="ctl w120">
                        <input id="orders_remark" name="orders_remark" class="easyui-textbox" data-options="prompt:'请输入备注......',onChange:$.h.po.ordersDataCache" style="width:100%;"/>
                    </div>
            </div>
        </form>
    </div>
    <div data-options="region:'center'" style="overflow:hidden;border-top:1px solid #ddd;">
        <table id="orders_grid_details" class="easyui-datagrid" style="width:100%;" title=""
               data-options="
				fit:true,
				fitColumns:true,
				rownumbers:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				rownumbers:true,
				border:false,
				toolbar:'#orders_grid_details_toolbar',
				footer:'#orders_grid_details_footer',
				method:'get',
				onBeforeLoad:$.h.po.mGrid.onBeforeLoad,
				onClickCell:$.h.po.mGrid.onClickCell,
                onEndEdit:$.h.po.mGrid.onEndEdit,
				onAfterEdit:$.h.po.mGrid.onAfterEdit,
				checkOnSelect:false,
				selectOnCheck:false,
                onKeyUpDownEvent:$.h.po.mGrid.onClickCell,
                onChange:$.h.po.ordersDataCacheGrid,
                onLoadSuccess:$.h.po.ordersDataCacheGrid,
			">
            <thead>
            <tr>
                <th data-options="field:'goods_id',checkbox:true"></th>
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
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.po.mDelGoods">删除</a>
            <select id="search_keyword" class="easyui-combogrid" data-options="
					ordersType: 'po',
                    icons:[{
                        iconCls:'icon-clear',
                        handler:$.h.c.clearCombogrid
                    }],
                    buttonIcon:'icon-search',
                    onClickButton:function() {
                        var target = $('#search_keyword');
						$.h.po.searchGoods(target);
                        target.combogrid('textbox').focus();
                    },
                    width:280,
					panelWidth: 800,
					panelHeight: 310,
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
					searchUrl:'/web/goods/sPurchaseOrdersGoods',
					columns: [[
						{field:'goods_id',hidden:true},
						{field:'goods_name',title:'名称',width:160},
						{field:'goods_code',title:'货号',width:100},
						{field:'goods_barcode',title:'条码',width:100},
						{field:'goods_pprice',title:'采购价',width:80}
					]],
					onShowPanel:function() {
						$('#orders_grid_details').datagrid('unbindKeyEvent');
					},
					onHidePanel:function() {
						$('#orders_grid_details').datagrid('bindKeyEvent');
					},
					onClickRow:function(index, row) {
						var target = $('#search_keyword');
						target.combogrid('panel').panel('options').closed = false;
						$.h.po.searchGoods(target);
					}
				">
            </select>
            <input class="easyui-switchbutton" data-options="onText:'查询销售',offText:'扫码销售',width:100,checked:true,onChange:$.h.s.goods.mQueryMode" /input>
            <div style="float:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.po.mSaveFormally">提交采购</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.po.mSaveRoughDraft">保存草稿</a>
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
                        url:'/web/dict/loadAccountListY',
                        onChange:$.h.po.ordersDataCache
                    " /input>
                <input id="orders_other_money" class="easyui-numberbox"
                       data-options="
                        label:'<font color=blue><strong>金额</strong></font>',
                        labelWidth:35,
                        width:160,
                        prefix:'￥',
                        precision:2,
                        value:0,
                        onChange:$.h.po.onOtherMoneyChange,
                        onchange:$.h.po.ordersDataCache
                    "/>
<!--                <a id="fj_images" class="easyui-linkbutton easyui-tooltip" data-options="-->
<!--                         iconCls:'icon-add',-->
<!--                         plain:true,-->
<!--                         showEvent:'click',-->
<!--                         hideEvent:'none',-->
<!--                         position:'bottom',-->
<!--                         content: function(){-->
<!--                            return $('#load_images');-->
<!--                        },-->
<!--                        onShow: function(){-->
<!--                            var t = $(this);-->
<!--                            $(document).one('click', function(e) { t.tooltip('hide'); });-->
<!--                            t.tooltip('tip').click(function() { event.stopPropagation(); });-->
<!--                        }-->
<!--                        ">-->
<!--                    附件</a>-->
                <a id="fj_images" class="easyui-linkbutton" data-options="
                         iconCls:'icon-add',
                         plain:true,
                         onClick:$.h.po.fileUpload
                        ">
                    附件</a>
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
                        onChange:$.h.po.onEraseMoneyChange
                    "/>
                <input id="goods_number" class="easyui-numberbox"
                       data-options="
                        label:'合计数量',
                        labelWidth:65,
                        width:130,
                        readonly:true,
                        precision:0,
                        value:0,
                        onChange:$.h.po.ordersDataCache
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
                        onChange:$.h.po.onGoodsMoneyChange
                    "/>
            </div>
        </div>
        <div id="mPrivateMenu" class="easyui-menu" data-options="onClick:$.h.c.handleCommboboxMenu"></div>
    </div>
</div>
<!--附件图片-->
<!--<div id="load_images">-->
<!--    <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.po.fileUpload">上传文件</a>-->
<!--    <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true,onClick:$.h.po.seeFile">查看文件</a>-->
<!--</div>-->
<div id="upload_fj_po"  class="easyui-window"  data-options="
closed:true,dataType:'json',
maximizable:false,minimizable:false,collapsible:false,
width:1024,height:450,title:'上传文件',
modal:true,
href:'/web/purchase/data_import'">
</div>

<!--<div id="see_fj_po_file" class="easyui-window" data-options="-->
<!--closed:true,dataType:'json',-->
<!--maximizable:false,minimizable:false,collapsible:false,-->
<!--width:520,height:450,-->
<!--modal:true,-->
<!--href:'/web/purchase/file_view'">-->
<!--</div>-->


<!--颜色尺寸-->
<div id="win_color_size" class="easyui-window" style="padding:5px;width:780px;height:400px;"
     data-options="title:'颜色尺寸',modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>