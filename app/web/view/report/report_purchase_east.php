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
		<div class="form-clo1">
			<div class="name">采购单号:</div>
			<div class="ctl">
				<input id="orders_code" class="easyui-textbox" data-options="prompt:'采购单编号'" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">开始日期:</div>
			<div class="ctl">
				<input id="begin_date" class="easyui-datebox" data-options="onChange:_.r.pur.dateChange" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">结束日期:</div>
			<div class="ctl">
				<input id="end_date" class="easyui-datebox" data-options="onChange:_.r.pur.dateChange" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">供应商:</div>
			<div class="ctl">
				<input id="supplier_id" class="easyui-combobox" style="width:100%;"
					data-options="
                        prompt:'选择供应商',
						valueField:'supplier_id',
						textField:'supplier_name',
						panelHeight:120,
						editable:false,
						url:'/web/supplier/loadCombobox',

					" />
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">仓库:</div>
			<div class="ctl">
				<input id="warehouse_id" class="easyui-combobox" style="width:100%;"
                                    data-options="
                        prompt:'选择仓库',
						valueField:'org_id',
						textField:'org_name',
						panelHeight:'auto',
						editable:false,
						url:'/web/organization/loadWarehouseList'
					" />
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">商品:</div>
			<div class="ctl">
				<input id="goods_ncb" class="easyui-textbox" data-options="prompt:'名称、货号、条码'" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">分类:</div>
			<div class="ctl">
				<select id="category_id" class="easyui-combotree" style="width:100%;" data-options="panelHeight:'auto',url:'/web/category/loadData',lines:true,prompt:'选择分类...'"></select>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">品牌:</div>
			<div class="ctl">
				<input id="brand_id" class="easyui-combobox" style="width:100%;" data-options="url:'/web/dict/loadlist?dict_type=brand',panelHeight:135,valueField:'dict_id',textField:'dict_name',editable:false,prompt:'选择品牌...'"></input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">年份:</div>
			<div class="ctl">
				<input id="goods_year" class="easyui-numberspinner" style="width:110px;" data-options="spinAlign:'horizontal',min:1980,max:2100,precision:0"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">季节:</div>
			<div class="ctl">
				<input id="goods_season" class="easyui-combobox" style="width:100%;"
					data-options="
                        prompt:'选择季节',
						valueField:'season_id',
						textField:'season_name',
						panelHeight:110,
						editable:false,
						data:[{season_id:1,season_name:'春季'},{season_id:2,season_name:'夏季'},{season_id:3,season_name:'秋季'},{season_id:4,season_name:'冬季'}]
					" />
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">状态:</div>
			<div class="ctl">
				<input id="goods_status" class="easyui-combobox" style="width:100%;"
					data-options="
                        prompt:'选择商品状态',
						valueField:'status_id',
						textField:'status_name',
						panelHeight:95,
						editable:false,
						data:[{status_id:9,status_name:'已采购'},{status_id:0,status_name:'草稿'},{status_id:1,status_name:'撤销'}]
					" />
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">
				<a id="btn" class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:_.r.pur.btnReset" style="width:100%;height:30px;">重置</a>
			</div>
			<div class="ctl">
				<a id="btn" class="easyui-linkbutton" data-options="iconCls:'icon-search',onClick:_.r.pur.btnQuery" style="width:100%;height:30px;">查询</a>
			</div>
		</div>
	
</div>