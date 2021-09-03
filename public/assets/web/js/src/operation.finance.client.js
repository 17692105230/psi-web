/**
 * 客户对账函数
 */
(function($) {
	var history = {_id : '',_ordersCode : '',_lockVersion : 0};
    /**
     * 主要的
     */
    $.h.fc = {
		/* 收款 */
		winIn : {
			/* 打开 */
			open : function(_fun) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 500) / 5;
				$('#win_finance_client').window({
					title:'收款',
					dataType:'json',
					top:top,
					left:left,
					height:520,
					width:400,
					href: '/web/finance/finance_client_in',
					onLoad:  _fun || function() {
						history._id = '';
						history._lockVersion = 0;
						history._ordersCode = '';
						$('#create_time').datebox('setValue',(new Date()).Format("yyyy-MM-dd"));
					}
				}).window('open');
			},
			/* 保存 */
			save : function() {
				var form = $('#win_finance_client_in_form');
				form.form('submit', {
					url: '/manage/finance/saveClientIn',
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
							$.h.fc.btnClientRecord();
						}
						parent.$.h.index.setOperateInfo({
							module:'客户对账',
							operate:'收款',
							content:data.errmsg,
							icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
						}, true);
						$('#win_finance_client').window('close');
						$.messager.progress('close');
					}
				});
			}
		},
		/* 期初调整 */
		winBegin : {
			/* 打开 */
			open : function(clientObj) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 400) / 5;

				$('#win_finance_client').window({
					title:'期初调整',
					dataType:'json',
					top:top,
					left:left,
					height:420,
					width:400,
					href: '/web/finance/finance_client_begin',
					onLoad: function() {
						history._id = '';
						history._ordersCode = '';
						history._lockVersion = 0;
						if (clientObj == null) return;
						$('#client_id').combogrid('setValue',clientObj);
						$.h.fc.winBegin.onSelect(0, clientObj);
					}
				}).window('open')
			},
			/* 保存 */
			save : function() {
				var form = $('#win_finance_client_begin_form');
				form.form('submit', {
					url: '/manage/finance/saveClientBegin',
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
							$.h.fc.btnClientRecord();
						}
						parent.$.h.index.setOperateInfo({
							module:'客户对账',
							operate:'期初调整',
							content:data.errmsg,
							icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
						}, true);
						$('#win_finance_client').window('close');
						$.messager.progress('close');
					}
				});
			},
			/* 客户选择改变事件 */
			onSelect : function(index, sData) {
				history._id = '';
				history._lockVersion = 0;
				history._ordersCode = '';
				$.ajax({
					url: '/manage/finance/loadClientBeginItem',
					data: {
						client_id:sData.client_id
					},
					type:'post',
					cache:false,
					dataType:'json',
					beforeSend: function(xhr) {
						$.messager.progress({title:'Please waiting',msg:'Loading data...'});
					},
					success: function(data) {
						if (data.errcode == 0) {
							history._id = data.row.details_id;
							history._ordersCode = data.row.orders_code;
							history._lockVersion = data.row.lock_version;
							$('#account_balance').numberbox('setValue',data.row.account_money);
							$('#begin_money').numberspinner('setValue',data.row.pbalance * -1);
							$('#create_time').datebox('setValue',$.DT.UnixToDate(data.row.create_time));
							$('#remark').textbox('setValue',data.row.remark);
						} else {
							history._id = '';
							history._ordersCode = '';
							history._lockVersion = 0;
							$('#account_balance').numberbox('setValue',sData.account_money);
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
		/* 客户对账查询 */
		btnClientRecord:function(e) {
			
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
				url:'/manage/finance/queryClientRecord',
				method:'post',
				queryParams:params,
				onLoadSuccess:function(data) {
					$('#sum_pmoney').numberbox('setValue', data.sum[0].pmoney);
					$('#sum_rmoney').numberbox('setValue', data.sum[0].rmoney);
					$('#sum_pbalance').numberbox('setValue', data.sum[0].pbalance);
				}
			});
		},
		/**
		 * 单元格单击列表事件
		 */
		onOrdersClickCell : function(index, field, value, row) {
			if (field == 'details_id1' || field == 'orders_code') {
				switch(row.item_type) {
					case 9103:/* 采购支出 */
						$('#win_details').window({
							title:'采购单详情',
							dataType:'json',
							top:50,
							left:140,
							height:(document.documentElement.clientHeight || document.body.clientHeight) - 160,
							width:(document.documentElement.clientWidth || document.body.clientWidth) - 280,
							href: '/manage/purchase/purchase_orders_center_complete',
							onLoad: function() {
								$.ajax({
									url: '/manage/purchase/loadOrdersDetails',
									data: {
										orders_code:row.orders_code
									},
									type:'post',
									cache:false,
									dataType:'json',
									beforeSend: function(xhr) {
										$.messager.progress({title:'Please waiting',msg:'Submit data...'});
									},
									success: function(data) {
										if (data.errcode == 0) {
											$('#orders_grid_toolbar').html('');
											$('#orders_code_view').text(data.orders.orders_code);
											$('#supplier_name').html(data.orders.supplier_name);
											$('#warehouse_name').html(data.orders.warehouse_name);
											$('#settlement_name').html(data.orders.settlement_name);
											$('#orders_rmoney').html(data.orders.orders_rmoney);
											$('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
											$('#orders_remark').html(data.orders.orders_remark);

											$('#orders_other_type').textbox('setValue',data.orders.other_name);
											$('#orders_other_money').numberbox('setValue',data.orders.other_money);
											$('#orders_erase_money').numberbox('setValue',data.orders.erase_money);
											$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
											$('#goods_number').numberbox('setValue',data.orders.goods_number);

											$('#orders_grid_details').datagrid('loadData',data.details);
										}
									},
									complete: function() {
										$.messager.progress('close');
									}
								});
							}
						}).window('open');
						break;
					case 9104:/* 采购退货 */
						alert('跳转采购退货单详情');
						break;
					case 9101:/* 销售收入 */
						alert('跳转销售单详情');
						break;
					case 9102:/* 销售退货 */
						alert('跳转销售退货单详情');
						break;
					case 9106:/* 零售 */
						alert('跳转零售单详情');
						break;
					case 9108:/* 优惠金额 */
						alert('跳转优惠金额详情');
						break;
					case 9109:/* 充值 */
						alert('跳转充值金额详情');
						break;
					case 9199:/* 收款 */
						$.h.fc.winIn.open(function() {
							$.ajax({
								url: '/manage/finance/loadClientRecordItem',
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
									console.log(data);
									if (data.errcode == 0) {
										history._id = data.row.details_id;
										history._lockVersion = data.row.lock_version;
										history._ordersCode = data.row.orders_code;
										$('#client_id').combogrid('setValue',{client_id:data.row.client_id,client_name:data.row.client_name});
										$('#account_balance').numberbox('setValue',data.row.account_money);
										$('#settlement_id').combobox('setValue',data.row.settlement_id);
										$('#account_id').combobox('setValue',data.row.account_id);
										$('#in_money').numberspinner('setValue',data.row.rmoney);
										$('#create_time').datebox('setValue',$.DT.UnixToDate(data.row.create_time));
										$('#remark').textbox('setValue',data.row.remark);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							});
						});
						break;
					case 9198:/* 支出 */
						alert('跳转支出');
						break;
					case 9107:/* 账户互转 */
						alert('跳转账户互转');
						break;
					case 9105:/* 期初调整 */
						$.h.fc.winBegin.open({client_id:row.client_id,client_name:row.client_name});
						break;
				}
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
						url: '/manage/finance/delClientRecordItem',
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
								$.h.fc.btnClientRecord();
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