/**
 * 供应商对账函数
 */
(function($) {
	var history = {_id : '',_ordersCode : '',_lockVersion : 0};
    /**
     * 主要的
     */
    $.h.fs = {
		/* 付款 */
		winOut : {
			open : function(_fun) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 500) / 5;
				$('#win_finance_supplier').window({
					title:'付款',
					dataType:'json',
					top:top,
					left:left,
					height:520,
					width:400,
					href: '/web/finance/finance_supplier_out',
					onLoad:  _fun || function() {
						history._id = '';
						history._lockVersion = 0;
						history._ordersCode = '';
						$('#create_time').datetimebox('setValue',(new Date()).getTime());
					}
				}).window('open');
			},
			/* 保存 */
			save : function() {
				var form = $('#win_finance_supplier_out_form');
				form.form('submit', {
					url: '/web/finance/saveSupplierOut',
					queryParams : {
						/* 数据 ID */
						details_id:history._id,
						/* 数据锁版本 */
						lock_version:history._lockVersion
					},
					onSubmit : function() {
						var v = $(this).form('validate')
						if (v) {
							$.messager.progress({title:'请稍候',msg:'正在保存数据......'});
						}
						return v;
					},
					success : function(data) {
						data = $.parseJSON(data);
						if (data.errcode == 0) {
							$.h.fs.btnSupplierRecord();
						}
						// parent.$.h.index.setOperateInfo({
						// 	module:'供应商对账',
						// 	operate:'付款',
						// 	content:data.errmsg,
						// 	icon:data.errcode == 0 ? 'hr-ok' : 'hr-error'
						// }, true);
						$('#win_finance_supplier').window('close');
						$.messager.progress('close');
					}
				});
			}
		},
		/* 期初调整 */
		winBegin : {
			open : function(supplierObj) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 400) / 5;
				$('#win_finance_supplier').window({
					title:'期初调整',
					dataType:'json',
					top:top,
					left:left,
					height:430,
					width:400,
					href: '/web/finance/finance_supplier_begin',
					onLoad: function() {
						history._id = '';
						history._ordersCode = '';
						history._lockVersion = 0;
						if (supplierObj == null) return;
						$('#supplier_id').combogrid('setValue',supplierObj);
						$.h.fs.winBegin.onSelect(0, supplierObj);
					}
				}).window('open');
			},
			/* 保存 */
			save : function() {
				var form = $('#win_finance_supplier_begin_form');
				form.form('submit', {
					url: '/web/finance/saveSupplierBegin',
					queryParams : {
						/* 数据 ID */
						details_id:history._id,
						/* 数据锁版本 */
						lock_version:history._lockVersion,
						orders_code:history._ordersCode
					},
					onSubmit : function() {
						var v = $(this).form('validate')
						if (v) {
							$.messager.progress({title:'请稍候',msg:'正在保存数据......'});
						}
						return v;
					},
					success : function(data) {
						data = $.parseJSON(data);
						if (data.errcode == 0) {
							$.h.fs.btnSupplierRecord();
						}
						// parent.$.h.index.setOperateInfo({
						// 	module:'供应商对账',
						// 	operate:'期初调整',
						// 	content:data.errmsg,
						// 	icon:data.errcode == 0 ? 'hr-ok' : 'hr-error'
						// }, true);
						$('#win_finance_supplier').window('close');
						$.messager.progress('close');
					}
				});
			},
			/* 供应商选择改变事件 */
			onSelect : function(index, sData) {
				history._id = '';
				history._lockVersion = 0;
				history._ordersCode = '';
				$.ajax({
					url: '/web/finance/loadSupplierBeginItem',
					data: {
						supplier_id:sData.supplier_id
					},
					type:'post',
					cache:false,
					dataType:'json',
					beforeSend: function(xhr) {
						$.messager.progress({title:'请稍后',msg:'正在加载数据...'});
					},
					success: function(data) {
						if (data.errcode == 0) {
							// history._id = data.data.row.details_id;
							// history._ordersCode = data.row.orders_code;
							// history._lockVersion = data.row.lock_version;
							$('#account_balance').numberbox('setValue',data.data.supplier_money);
							// $('#begin_money').numberspinner('setValue',data.row.pbalance * -1);
							// $('#create_time').datebox('setValue',$.DT.UnixToDate(data.row.create_time));
							// $('#remark').textbox('setValue',data.row.remark);
						} else {
							history._id = '';
							history._ordersCode = '';
							history._lockVersion = 0;
							$('#account_balance').numberbox('setValue',sData.supplier_money);
							$('#begin_money').numberspinner('reset');
							$('#create_time').datebox('reset');
							$('#remark').textbox('reset');
						}
					},
					complete: function() {
						$.messager.progress('close');
					}
				});
			}
		},
		/* 查询表单加载 */
		leftLoad : function() {
			var d = $.DT.ThisAgoMonth();
			$('#search_begin_date').datebox('setValue',d.beginFormat);
			$('#search_end_date').datebox('setValue',d.endFormat);
		},
		/* 供应商对账查询 */
		btnSupplierRecord:function(e) {
			var form = $('#query_form');
			if(!form.form('validate')) return;

			var params = {};
			form.find('input,select').each(function() {
				if (this.name && $.trim(this.value)) {
					if (this.name == 'search_begin_date' || this.name == 'search_end_date') {
						params[this.name] = $.DT.DateToUnix(this.value);
					} else {
						params[this.name] = this.value
					}
				}
			});
			if ($.isEmptyObject(params)) return;
			$('#record_grid').datagrid({
				url:'/web/finance/querySupplierRecord',
				method:'post',
				queryParams:params,
				onLoadSuccess:function(data) {
					$('#sum_pmoney').numberbox('setValue', data.rows[0].pmoney_sum);
					$('#sum_rmoney').numberbox('setValue', data.rows[0].rmoney_sum);
					$('#sum_pbalance').numberbox('setValue', data.rows[0].pbalance_sum);
				}
			});
		},
		/**
		 * 单元格单击列表事件
		 */
		onOrdersClickCell : function(index, field, value, row) {
			if(field != 'orders_code')return;
			var w = (document.documentElement.clientWidth || document.body.clientWidth) - 280;
			var h = (document.documentElement.clientHeight || document.body.clientHeight) - 160;
			console.log(row.item_type);
			switch (row.item_type) {
				case 9103:/* 采购支出 */
					$('#win_details').window({
						title:'采购单详情',
						dataType:'json',
						top:50,
						left:140,
						height: h,
						width: w,
						href: '/web/purchase/purchase_orders_center_complete',
						onLoad: function() {
							$.ajax({
								url: '/web/purchase/loadOrdersDetails',
								data: {
									orders_code:row.orders_code
								},
								type:'post',
								cache:false,
								dataType:'json',
								beforeSend: function(xhr) {
									$.messager.progress({title:'请稍等',msg:'数据正在处理中...'});
								},
								success: function(data) {
									if (data.errcode == 0) {
										$('#orders_grid_toolbar').html('');
										$('#orders_code_view').text(data.data.orders.orders_code);
										$('#supplier_name').html(data.data.orders.supplier_name);
										$('#warehouse_name').html(data.data.orders.org_name);
										$('#settlement_name').html(data.data.orders.settlement_name);
										$('#orders_rmoney').html(data.data.orders.orders_rmoney);
										$('#orders_date').html($.DT.UnixToDate(data.data.orders.orders_date, 0, true));
										$('#orders_remark').html(data.data.orders.orders_remark);
										$('#orders_other_type').textbox('setValue',data.data.orders.other_name);
										$('#orders_other_money').numberbox('setValue',data.data.orders.other_money);
										$('#orders_erase_money').numberbox('setValue',data.data.orders.erase_money);
										$('#orders_pmoney').numberbox('setValue',data.data.orders.orders_pmoney);
										$('#goods_number').numberbox('setValue',data.data.orders.goods_number);
										$('#orders_grid_details').datagrid('loadData',data.data.orders.details);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							});
						}
					}).window('open');
					break;
				case 9104:/*采购退货*/
					$('#win_details').window({
						title:'采购退货单详情',
						dataType:'json',
						top:50,
						left:140,
						height: h,
						width: w,
						href: '/web/purchase/purchase_reject_center_complete',
						onLoad: function() {
							$.ajax({
								url: '/web/purchase/loadOrdersRejectDetails',
								data: {
									orders_code:row.orders_code
								},
								type:'post',
								cache:false,
								dataType:'json',
								beforeSend: function(xhr) {
									$.messager.progress({title:'请稍等',msg:'数据正在处理中...'});
								},
								success: function(data) {
									if (data.errcode == 0) {
										$('#orders_grid_reject_toolbar').html('');
										$('#orders_code_view').text(data.data.orders.orders_code);
										$('#supplier_name').html(data.data.orders.supplier_name);
										$('#warehouse_name').html(data.data.orders.org_name);
										$('#settlement_name').html(data.data.orders.settlement_name);
										$('#orders_rmoney').html(data.data.orders.orders_rmoney);
										$('#orders_date').html($.DT.UnixToDate(data.data.orders.orders_date, 0, true));
										$('#orders_remark').html(data.data.orders.orders_remark);

										$('#orders_other_type').textbox('setValue',data.data.orders.other_name);
										$('#orders_other_money').numberbox('setValue',data.data.orders.other_money);
										$('#orders_erase_money').numberbox('setValue',data.data.orders.erase_money);
										$('#orders_pmoney').numberbox('setValue',data.data.orders.orders_pmoney);
										$('#goods_number').numberbox('setValue',data.data.orders.goods_number);

										$('#orders_grid_details').datagrid('loadData',data.data.orders.details);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							});
						}
					}).window('open');
					break;
				case 9198:/* 付款 */
					$('#win_finance_supplier').window({
						title:'付款',
						dataType:'json',
						top:((document.documentElement.clientHeight || document.body.clientHeight) - 500) / 5,
						left:((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2,
						height:520,
						width:400,
						href: '/web/finance/finance_supplier_out',
						onLoad: function() {
							$.ajax({
								url:'/web/finance/loadOrdersFinanceOut',
								data: {
									orders_code:row.orders_code
								},
								type:'post',
								cache:false,
								dataType:'json',
								beforeSend: function(xhr) {
									$.messager.progress({title:'请稍等',msg:'数据正在处理中...'});
								},
								success:function(data){
									if (data.errcode == 0){
										history._id = data.data.details_id;
										$("#supplier_id").combogrid('setValue',data.data.supplier_id);
										$("#account_balance").numberbox('setValue',data.data.supplier_money);
										$("#settlement_id").combobox('setValue',data.data.settlement_id);
										$("#account_id").combobox('setValue',data.data.account_id);
										$("#out_money").numberspinner('setValue',data.data.rmoney);
										$("#create_time").datebox('setValue',$.DT.UnixToDate(data.data.orders_date));
										$("#remark").textbox('setValue',data.data.remark);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							})
						},

					}).window('open');
				case 9200:/* 期初调整 */
					console.log(row);
					$('#win_finance_supplier').window({
						title:'期初调整',
						dataType:'json',
						top:((document.documentElement.clientHeight || document.body.clientHeight) - 400) / 5,
						left:((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2,
						height:430,
						width:400,
						href: '/web/finance/finance_supplier_begin',
						onLoad: function(data) {
							$.ajax({
								url : '/web/finance/loadBeginSupplier',
								data: {
									orders_code:row.orders_code,
								},
								type:'post',
								cache:false,
								dataType:'json',
								beforeSend: function(xhr) {
									$.messager.progress({title:'请稍等',msg:'数据正在处理中...'});
								},
								success:function(data){
									if (data.errcode == 0){
										history._id = data.data.details_id;
										history._lockVersion = data.data.lock_version;
										history._ordersCode = data.data.orders_code;
										$("#supplier_id").combogrid('setValue',data.data.supplier_id);
										$("#account_balance").numberbox('setValue',data.data.supplier_money);
										$("#begin_money").numberspinner('setValue',data.data.supplier_balance);
										$("#create_time").datebox('setValue',$.DT.UnixToDate(data.data.orders_date));
										$("#remark").textbox('setValue',data.data.remark);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							})
						}
					}).window('open');
			}
		},
		/* 删除 */
		btnDel : function() {
			var row = $('#record_grid').datagrid('getSelected');
			if (row == null) return;
			if ('9101,9102,9103,9104,9106,9108,9109'.indexOf(row.item_type) >= 0) {
				return;
			}
			$.messager.confirm('警告','您确认想要删除记录吗？',function(isRun) {
				if (isRun) {
					$.ajax({
						url: '/manage/finance/delSupplierRecordItem',
						data: {
							details_id:row.details_id
						},
						type:'post',
						cache:false,
						dataType:'json',
						beforeSend: function(xhr) {
							$.messager.progress({title:'Please waiting',msg:'Loading data...'});
						},
						success: function(data) {
							if (data.errcode == 0) {
								$.h.fs.btnSupplierRecord();
							}
						},
						complete: function() {
							$.messager.progress('close');
						}
					});
				}
			});
		}
	}
})(jQuery);