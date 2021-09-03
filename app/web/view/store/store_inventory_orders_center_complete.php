<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:125px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false,
			onResize: function(w, h) {
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
			}">
		<header class="header-hover">
            <div style="float:left;margin-top:5px;">
                <span style="font-weight:bold;">库存盘点单 - <span id="orders_code_view" style="font-size:10pt;color:blue;"></span>
            </div>
            <div style="float:right;">
				<a id="new_build_orders" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.si.mNewOrders">新建订单</a>
            </div>
        </header>
		<form id="orders_form" method="post">
			<div class="kbi_column">
				<div class="kbi_column_left a">
					<div class="kbi_column_t1"><strong>仓库:</strong></div>
                    <div class="kbi_column_i">
						<div id="warehouse_name" class="hjtr_read_text"></div>
                    </div>
				</div>
				<div class="kbi_column_left a">
					<div class="kbi_column_t1"><strong>盘点总数:</strong></div>
					<div class="kbi_column_i">
						<div id="goods_number" class="hjtr_read_text"></div>
					</div>
				</div>
				<div class="kbi_column_left a">
					<div class="kbi_column_t1"><strong>盈亏总数:</strong></div>
					<div class="kbi_column_i">
						<div id="goods_plnumber" class="hjtr_read_text"></div>
					</div>
				</div>
                <div class="kbi_column_left a">
					<div class="kbi_column_t1"><strong>盈亏金额:</strong></div>
					<div class="kbi_column_i">
						<div id="goods_plmoney" class="hjtr_read_text"></div>
					</div>
				</div>
				<div class="kbi_column_left b" style="width:100%;">
					<div class="kbi_column_t1"><strong>备注:</strong></div>
					<div class="kbi_column_i">
						<div id="orders_remark" class="hjtr_read_text"></div>
					</div>
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
				pagination: true,
				pageSize: 20,
				pageList: [20,30,50,100],
				border:false,
				toolbar:'#orders_grid_details_toolbar',
				method:'post',
				onBeforeLoad:$.h.si.complete.onBeforeLoad,
				onAfterLoad:$.h.si.mGrid.onAfterLoad,
				checkOnSelect:false,
				selectOnCheck:false
			">
			<thead>
				<tr>
					<th data-options="field:'details_id',checkbox:true"></th>
					<th data-options="field:'goods_name',width:200,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:200,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:200,sortable:true"><b>货号</b></th>
					<th data-options="field:'color_name',width:100,align:'center'"><b>颜色</b></th>
					<th data-options="field:'size_name',width:100,align:'center'"><b>尺码</b></th>
					<th data-options="field:'goods_number',width:100,align:'center',fixed:true"><b>盘点数量</b></th>
					<th data-options="field:'goods_anumber',width:100,align:'center',fixed:true"><b>盘点前数量</b></th>
					<th data-options="field:'goods_lnumber',width:100,align:'center',fixed:true"><b>盈亏数量</b></th>
                    <th data-options="
                        field:'goods_lmoney',
                        align:'right',
                        fixed:true,
                        width:120,
                        formatter:function(value, row) {
                            return $.formatMoney(value,'￥');
                        }
                        "><b>盈亏金额</b></th>
				</tr>
			</thead>
		</table>
		<div id="orders_grid_details_toolbar" style="height:33px;padding:1px;">
            <div style="float:left;">
                <a class="easyui-linkbutton" data-options="iconCls:'hjtr-ico-view',plain:true,onClick:$.h.si.winDetails.open">单据盘点明细</a>
            </div>
			<div style="float:right;">
                <a id="btnGridQuery" class="easyui-linkbutton easyui-tooltip"
                    data-options="
                        showEvent:'click',
                        hideEvent:'none',
                        iconCls:'icon-search',
                        plain:true,
                        position:'bottom',
                        content: function(){
                            return $('#btnGridQueryDiv');
                        },
                        onShow: function(){
                            var t = $(this);
                            $(document).one('click', function(e) { t.tooltip('hide'); });
                            t.tooltip('tip').click(function() { event.stopPropagation(); });
                        }
                    ">搜索商品</a>
            </div>
		</div>
        <div style="display:none">
            <div id="btnGridQueryDiv" class="easyui-panel" data-options="width:300,height:225" style="padding:10px 5px 0px 5px;">
                <form id="btnGridQueryFrom" method="post">
                    <div class="kbi_column">
                        <div class="kbi_column_left_100">
                            <div class="kbi_column_t">商品:</div>
                            <div class="kbi_column_i">
                                <input class="easyui-textbox" data-options="width:200,prompt:'名称、货号、条码...'"/>
                            </div>
                            <div class="kbi_c"></div>
                        </div>
                        <div class="kbi_column_left_100">
                            <div class="kbi_column_t">商品颜色:</div>
                            <div class="kbi_column_i">
                                <input class="easyui-combobox"
                                    data-options="
                                        valueField:'color_id',
                                        textField:'color_name',
                                        panelHeight:'auto',
                                        panelMaxHeight:200,
                                        width:200,
                                        editable:false,
                                        prompt:'选择颜色...',
                                        url:'/web/color/loadCombobox'
                                    " /input>
                            </div>
                            <div class="kbi_c"></div>
                        </div>
                        <div class="kbi_column_left_100">
                            <div class="kbi_column_t">商品尺码:</div>
                            <div class="kbi_column_i">
                                <input class="easyui-combobox"
                                    data-options="
                                        valueField:'size_id',
                                        textField:'size_name',
                                        panelHeight:'auto',
                                        panelMaxHeight:200,
                                        width:200,
                                        editable:false,
                                        prompt:'选择颜色...',
                                        url:'/web/size/loadCombobox'
                                    " />
                            </div>
                            <div class="kbi_c"></div>
                        </div>
                        <div class="kbi_column_left_100">
                            <div class="kbi_column_t">盈亏状态:</div>
                            <div class="kbi_column_i">
                                <input class="easyui-combobox" data-options="
                                    editable:false,
                                    prompt:'请选择...',
                                    panelHeight:'auto',
                                    panelMaxHeight:380,
                                    width:200,
                                    valueField:'value',
                                    textField:'label',
                                    data: [{label:'盘盈',value:'1'},{label:'正常',value:'0'},{label:'盘亏',value:'2'}]"
                                />
                            </div>
                            <div class="kbi_c"></div>
                        </div>
                        <div class="kbi_column_left_100">
                            <div class="kbi_column_t"></div>
                            <div class="kbi_column_i">
                                <a class="easyui-linkbutton" data-options="iconCls:'icon-search',width:200,height:40">查 询</a>
                            </div>
                            <div class="kbi_c"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>