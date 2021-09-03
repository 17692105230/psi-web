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
			<div class="name">销售单号：</div>
			<div class="ctl">
				<input type="text" class="easyui-textbox" data-options="prompt:'销售单编号'" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">开始日期：</div>
			<div class="ctl">
				<input type="text" class="easyui-datebox" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">结束日期：</div>
			<div class="ctl">
				<input type="text" class="easyui-datebox" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">供应商:</div>
			<div class="ctl">
				<input id="supplier_id" name="supplier_id" class="easyui-combobox" style="width:100%;"
					data-options="
						valueField:'supplier_id',
						textField:'supplier_name',
						panelHeight:120,
						editable:false,
						required:true,
						url:'/manage/supplier/loadCombobox'
					" /input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">仓库：</div>
			<div class="ctl">
				<select class="easyui-combobox" data-options="prompt:'选择仓库...',editable:false" style="width:100%;">
					<option value="总库">总库</option>
				</select>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">商品：</div>
			<div class="ctl">
				<input type="text" class="easyui-textbox" data-options="prompt:'名称、货号、条码'" style="width:100%;"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">分类：</div>
			<div class="ctl">
				<select id="category_id" name="category_id" class="easyui-combotree" style="width:100%;" data-options="url:'/manage/category/loadtreelist',lines:true,prompt:'选择分类...'"></select>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">品牌：</div>
			<div class="ctl">
				<input id="bland_id" name="bland_id" class="easyui-combobox" style="width:100%;" data-options="url:'/manage/dict/loadlist?dict_type=brand',panelHeight:135,valueField:'dict_id',textField:'dict_name',editable:false,prompt:'选择品牌...'"></input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">年份：</div>
			<div class="ctl">
				<input id="goods_year" name="goods_year" class="easyui-numberspinner" style="width:110px;" value="2018" data-options="spinAlign:'horizontal',min:1980,max:2100,precision:0"/>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">季节：</div>
			<div class="ctl">
				<select id="goods_season" name="goods_season" class="easyui-combobox" style="width:110px;" data-options="panelHeight:85,editable:false">
					<option value="1">春季</option>
					<option value="2">夏季</option>
					<option value="3">秋季</option>
					<option value="4">冬季</option>
				</select> 
			</div>
		</div>
		<div class="form-clo1">
			<div class="name">状态：</div>
			<div class="ctl">
				<select id="goods_season" name="goods_season" class="easyui-combobox" style="width:110px;" data-options="panelHeight:85,editable:false">
					<option value="1">已销售</option>
					<option value="2">草稿</option>
					<option value="3">撤销</option>
				</select> 
			</div>
		</div>
		<div class="form-clo1">
			<div class="name"></div>
			<div class="ctl" style="text-align:right">
				<a id="btn" class="easyui-linkbutton" data-options="iconCls:'icon-search',onClick:function(){alert();}" style="width:100%;height:30px;">查询</a>
			</div>
		</div>

</div>