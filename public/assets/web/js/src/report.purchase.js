/**
 * 销售报表函数
 */
(function($) {
    var history = { category:1, begin_date:0, end_date:0 }
	var s = {};
	var d = {};

    /**
     * 主要的
     */
    _.r.pur = {
		load : function() {
			var t = $.DT.Today();
			history.begin_date = t.begin;
			history.end_date = t.end;
			setGridColumns(history);

			d.beginFormat = t.beginFormat;
			d.endFormat = t.endFormat;
			if (!$('#main_layout').layout('panel','east').panel('options').collapsed) {
				$('#begin_date').textbox('setValue', d.beginFormat);
				$('#end_date').textbox('setValue', d.endFormat);
			}
		},
        sDate : function(e) {
			var time = $(this).linkbutton('options').category;
			var t = $.DT.Today();
			switch(time) {
				case 1:
					break;
				case 2:
					t = $.DT.Yesterday();
					break;
				case 3:
					t = $.DT.ThisWeek();
					break;
				case 4:
					t = $.DT.ThisMonth();
					break;
				case 5:
					t = $.DT.ThisQuarter();
					break;
			}
			d.beginFormat = t.beginFormat;
			d.endFormat = t.endFormat;
			if (!$('#main_layout').layout('panel','east').panel('options').collapsed) {
				$('#begin_date').textbox('setValue', d.beginFormat);
				$('#end_date').textbox('setValue', d.endFormat);
			}
			_.r.pur.btnQuery();
		},
		dateChange : function(newValue, oldValue) {
			$('#btn_query').linkbutton('select');
		},
		sColumns : function(e) {
			history.category = $(this).linkbutton('options').category;
			if ($.trim($('#begin_date').datebox('getValue'))) {
				history.begin_date = $.DT.DateToUnix($.trim($('#begin_date').datebox('getValue')));
			}
			if ($.trim($('#end_date').datebox('getValue'))) {
				history.end_date = $.DT.DateToUnix($.trim($('#end_date').datebox('getValue')));
			}
			setGridColumns(history);
		},
		btnQuery : function(e) {
			var condition = {};
			if ($.trim($('#orders_code').textbox('getValue'))) {
				condition.orders_code = $.trim($('#orders_code').textbox('getValue'));
			}
			if ($.trim($('#begin_date').datebox('getValue'))) {
				condition.begin_date = $.DT.DateToUnix($.trim($('#begin_date').datebox('getValue')));
			}
			if ($.trim($('#end_date').datebox('getValue'))) {
				condition.end_date = $.DT.DateToUnix($.trim($('#end_date').datebox('getValue')));
			}
			if ($.trim($('#supplier_id').combobox('getValue'))) {
				condition.supplier_id = $.trim($('#supplier_id').combobox('getValue'));
			}
			if ($.trim($('#warehouse_id').combobox('getValue'))) {
				condition.warehouse_id = $.trim($('#warehouse_id').combobox('getValue'));
			}
			if ($.trim($('#goods_ncb').textbox('getValue'))) {
				condition.goods_ncb = $.trim($('#goods_ncb').textbox('getValue'));
			}
			if ($.trim($('#category_id').combotree('getValue'))) {
				condition.category_id = $.trim($('#category_id').combotree('getValue'));
			}
			if ($.trim($('#brand_id').combobox('getValue'))) {
				condition.brand_id = $.trim($('#brand_id').combobox('getValue'));
			}
			if ($.trim($('#goods_year').numberspinner('getValue'))) {
				condition.goods_year = $.trim($('#goods_year').numberspinner('getValue'));
			}
			if ($.trim($('#goods_season').combobox('getValue'))) {
				condition.goods_season = $.trim($('#goods_season').combobox('getValue'));
			}
			if ($.trim($('#goods_status').combobox('getValue'))) {
				condition.goods_status = $.trim($('#goods_status').combobox('getValue'));
			}
			
			condition.category = history.category;


			$('#main_grid').datagrid({
				queryParams:condition,
				method:'get',
				onLoadSuccess:function(data) {
					$('#goods_count').textbox('setValue', data.goods_count);
					$('#goods_money').numberbox('setValue', data.goods_money);
				}
			});
		},
		btnTopQuery : function(e) {
			if ($('#main_layout').layout('panel','east').panel('options').collapsed) {
				$('#main_layout').layout('expand','east');
			}
			delete history.begin_date;
			delete history.end_date;
		},
		btnReset : setQueryForm
    }
	
	/**
	 * 设置 datagrid columns
	 * 
	 */
	function setGridColumns(condition) {
		var frozenColumns = [[]];
		var columns = [[]];
		var fitColumns = false;
		switch(history.category) {
			case 1:
				s = {
					orders_code:true,
					begin_date:true,
					end_date:true,
					supplier_id:true,
					warehouse_id:true,
					goods_ncb:true,
					category_id:true,
					brand_id:true,
					goods_year:true,
					goods_season:true,
					goods_status:true
				};
				frozenColumns = [[
						{field:'details_id',checkbox:true},
						{field:'goods_name',title:'商品名称',width:220},
						{field:'orders_code',title:'单据编号',width:180},
						{field:'goods_code',title:'货号',width:150}
					]];
				columns = [[
						{field:'orders_date',title:'单据日期',width:120,align:'center',formatter:function(value,row) { return $.DT.UnixToDate(value,0,true); }},
						{field:'color_name',title:'颜色',width:90,align:'center'},
						{field:'size_name',title:'尺码',width:90,align:'center'},
						{field:'goods_number',title:'数量',width:70,align:'center'},
						{field:'goods_price',title:'零售价',width:100,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'goods_tmoney',title:'金额',width:120,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'goods_discount',title:'折扣',width:70,align:'center'},
						{field:'goods_daprice',title:'折后价',width:100,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'goods_tdamoney',title:'折后金额',width:120,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'user_name',title:'采购员',width:80,align:'center'},
						{field:'supplier_name',title:'供应商',width:180,align:'left'},
						{field:'org_name',title:'仓库',width:120,align:'left'},
						{field:'goods_year',title:'年份',width:70,align:'center'},
						{field:'goods_season',title:'季节',width:70,align:'center',formatter:function(value,row) { switch(value) {case 1: return '春季';case 2: return '夏季';case 3: return '秋季';case 4: return '冬季';} }},
						{field:'settlement_name',title:'结算账户',width:120,align:'left'},
						{field:'orders_pmoney',title:'单据金额',width:140,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'orders_rmoney',title:'实付金额',width:140,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						
						{field:'goods_barcode',title:'条码',width:120},
						{field:'brand_name',title:'品牌',width:120,align:'center'},
						{field:'category_name',title:'分类',width:100,align:'center'},
						{field:'unit_name',title:'单位',width:70,align:'center'}
					]];
				break;
			case 2:
				s = {
					orders_code:false,
					begin_date:true,
					end_date:true,
					supplier_id:false,
					warehouse_id:false,
					goods_ncb:true,
					category_id:true,
					brand_id:true,
					goods_year:true,
					goods_season:true,
					goods_status:true
				};
				frozenColumns = [[
						{field:'details_id',checkbox:true},
						{field:'goods_name',title:'商品名称',width:220},
						{field:'goods_code',title:'货号',width:180},
						{field:'goods_barcode',title:'条码',width:150}
					]];
				columns = [[
						{field:'brand_name',title:'品牌',width:120,align:'center'},
						{field:'category_name',title:'分类',width:120,align:'center'},
						{field:'unit_name',title:'单位',width:70,align:'center'},
						{field:'goods_number',title:'单品数量',width:80,align:'center'},
						{field:'goods_price',title:'零售价',width:100,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'goods_tmoney',title:'金额',width:150,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'goods_discount',title:'折扣',width:80,align:'center'},
						{field:'goods_daprice',title:'折后价',width:100,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'goods_tdamoney',title:'折后金额',width:160,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'goods_year',title:'年份',width:80,align:'center'},
						{field:'goods_season',title:'季节',width:70,align:'center',formatter:function(value,row) { switch(value) {case 1: return '春季';case 2: return '夏季';case 3: return '秋季';case 4: return '冬季';} }}
					]];
				break;
			case 3:
				s = {
					orders_code:true,
					begin_date:true,
					end_date:true,
					supplier_id:true,
					warehouse_id:true,
					goods_ncb:false,
					category_id:false,
					brand_id:false,
					goods_year:false,
					goods_season:false,
					goods_status:true
				};
				frozenColumns = [[
						{field:'orders_id',checkbox:true},
						{field:'orders_code',title:'单据编号',width:200},
						{field:'goods_number',title:'单品数量',width:100,align:'center'}
					]];
				columns = [[
						{field:'user_name',title:'采购员',width:80,align:'center'},
						{field:'supplier_name',title:'供应商',width:200,align:'left'},
						{field:'org_name',title:'仓库',width:120,align:'left'},
						{field:'settlement_name',title:'结算账户',width:100,align:'left'},
						{field:'orders_pmoney',title:'单据金额',width:120,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'orders_rmoney',title:'实付金额',width:120,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }},
						{field:'orders_date',title:'单据日期',width:120,align:'center',formatter:function(value,row) { return $.DT.UnixToDate(value,0,true); }}
					]];
				fitColumns = true;
				break;
			case 4:
				s = {
					orders_code:false,
					begin_date:true,
					end_date:true,
					supplier_id:true,
					warehouse_id:false,
					goods_ncb:false,
					category_id:false,
					brand_id:false,
					goods_year:false,
					goods_season:false,
					goods_status:true
				};
				columns = [[
						{field:'goods_number',title:'单品数量',width:100,align:'center'},
						{field:'supplier_name',title:'供应商',width:260,align:'left'},
						{field:'orders_rmoney',title:'折后金额',width:160,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }}
					]];
				fitColumns = false;
				break;
			case 5:
				s = {
					orders_code:false,
					begin_date:true,
					end_date:true,
					supplier_id:false,
					warehouse_id:false,
					goods_ncb:false,
					category_id:false,
					brand_id:false,
					goods_year:false,
					goods_season:false,
					goods_status:true
				};
				columns = [[
						{field:'goods_number',title:'单品数量',width:100,align:'center'},
						{field:'user_name',title:'采购员',width:100,align:'left'},
						{field:'orders_rmoney',title:'折后金额',width:160,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }}
					]];
				fitColumns = false;
				break;
		}
		var east = $('#main_layout').layout('panel','east');
		var collapsed = east.panel('options').collapsed;
		if (!collapsed) {
			setQueryForm();
			east.panel('doLayout');
		}
		$('#main_grid').datagrid({
			url:'/web/report/purchase',
			queryParams:condition,
			method:'get',
			fitColumns:fitColumns,
			frozenColumns:frozenColumns,
			columns:columns,
			onLoadSuccess:function(data) {
				$('#goods_count').textbox('setValue', data.goods_count);
				$('#goods_money').numberbox('setValue', data.goods_money);
			}
		});
	}

	function setQueryForm() {
		var list = $('.kbi_column').children();
		var i = 0;
		$.each(s, function(key, value) {
			value ? $(list[i]).show() : $(list[i]).hide();
			switch(key) {
				case 'goods_year':
					$('#goods_year').numberspinner('setValue', value ? new Date().getFullYear() : '');
					break;
				case 'begin_date':
				case 'end_date':
					break;
				default:
					$('#'+key).textbox('setValue', '');
			}
			i++;
		});
	}

	function getToDay() {
		return $.DT.DateToUnix((new Date()).Format("yyyy-M-d"));
	}
})(jQuery);