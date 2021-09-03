<div class="easyui-layout" data-options="fit:true">
	<div class="easyui-panel" style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:130px;background-color:#eee;"
		data-options="
			region:'north',
			split:true,
			hideCollapsedContent:false,
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w > 1500) {
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
                <span style="font-weight:bold;">采购计划 - <span id="orders_code_view" style="font-size:10pt;color:blue;"></span>
            </div>
            <div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.pp.mNewPlans">新建计划</a>
            </div>
        </header>
		<form id="purchase_plan_form" method="post">
			<div class="form-clo2">
                    <div class="name w120"><strong>供应商:</strong></div>
				    <div class="ctl w120"><div id="supplier_name" class="name" style="text-align: left"></div></div>
                    <div class="name w120"><strong>仓库:</strong></div>
				    <div class="ctl w120"><div id="warehouse_name" class="name" style="text-align: left"></div></div>
                    <div class="name w120"><strong>日期:</strong></div>
				    <div class="ctl w120"><div id="orders_date" class="name" style="text-align: left"></div></div>
            </div>
            <div class="form-clo2">
                    <div class="name w120"><strong>备注:</strong></div>
				    <div class="ctl w120"><div id="orders_remark" class="name" style="text-align: left"></div></div>
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
				footer:'#orders_grid_details_footer',
				method:'get',
				onBeforeLoad:$.h.pp.complete.onBeforeLoad
			">
			<thead>
				<tr>
					<th data-options="field:'goods_id',checkbox:true"></th>
					<th data-options="field:'goods_name',width:11,sortable:true"><b>商品</b></th>
					<th data-options="field:'goods_barcode',width:7,sortable:true"><b>条码</b></th>
					<th data-options="field:'goods_code',width:7,sortable:true"><b>货号</b></th>
					<th data-options="
							field:'color_id',
							width:5,
							align:'center',
							formatter:function(value,row,index) {
								return row.color_name;
							}
						"><b>颜色</b></th>
					<th data-options="
							field:'size_id',
							width:3,
							align:'center',
							formatter:function(value,row,index) {
								return row.size_name;
							}
						"><b>尺码</b></th>
					<th data-options="
							field:'goods_number',
							align:'center'
						">&nbsp;<b>数量</b>&nbsp;</th>
					<th data-options="
							field:'goods_rprice',
							width:5,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>单价</b></th>
					<th data-options="
							field:'goods_discount',
							width:5,
							align:'right',
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
							width:5,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>折后价</b></th>
					<th data-options="
							field:'goods_tmoney',
							width:7,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>金额</b></th>
					<th data-options="
							field:'goods_tdamoney',
							width:7,
							align:'right',
							formatter:function(value, row) {
								return $.formatMoney(value,'￥');
							}
						"><b>折后金额</b></th>
				</tr>
			</thead>
		</table>
		<div id="orders_grid_details_footer" style="padding:2px 5px;">
			<div style="float:left;">
			</div>
			<div style="float:right;">
                <input id="goods_number" class="easyui-numberbox" data-options="label:'合计数量',labelWidth:65,width:130,readonly:true,precision:0,value:0"/>
				<input id="orders_pmoney" class="easyui-numberbox" data-options="label:'合计金额',labelWidth:65,width:180,readonly:true,prefix:'￥',precision:2,value:0"/>
			</div>
		</div>
	</div>
</div>