/**
 * 账户流水函数
 */
(function($) {
	var history = {
		_id : '',
		_ordersCode : '',
        _lockVersion : 0
	};
    /**
     * 主要的
     */
    $.h.fa = {
		/* 收入 */
		winIn : {
			/* 打开窗口 */
			open : function(_fun) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 420) / 5;
				$('#win_finance_account').window({
					title:'收入',
					dataType:'json',
					top:top,
					left:left,
					height:425,
					width:400,
					href: '/web/finance/finance_account_in',
					onLoad: _fun || function() {
						history._id = '';
						history._lockVersion = 0;
						history._ordersCode = '';
						$('#create_time').datebox('setValue',(new Date()).Format("yyyy-MM-dd"));
					}
				}).window('open');
			},
			/* 收入保存 */
			save : function() {
				var form = $('#win_finance_account_in_form');
				form.form('submit', {
					url: '/web/finance/saveAccountIn',
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
                        $.messager.progress('close');

						data = $.parseJSON(data);
						// 处理失败
						if (data.errcode == 1) {
                            parent.$.h.index.setOperateInfo({
                                module:'账户流水',
                                operate:'支出',
                                content:data.errmsg,
                                icon:'hr-error'
                            }, true);

                            return '';
						}
                        $.h.fa.btnAccountRecord();
						parent.$.h.index.setOperateInfo({
							module:'账户流水',
							operate:'支出',
							content:data.errmsg,
							icon:'icon-ok' ,
						}, true);
						$('#win_finance_account').window('close');
					}
				});
			}
		},
		/* 支出 */
		winOut : {
			open : function(_fun) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 420) / 5;
				$('#win_finance_account').window({
					title:'支出',
					dataType:'json',
					top:top,
					left:left,
					height:425,
					width:400,
					href: '/web/finance/finance_account_out',
					onLoad: _fun || function() {
						history._id = '';
						history._lockVersion = 0;
						history._ordersCode = '';
						$('#create_time').datebox('setValue',(new Date()).Format("yyyy-MM-dd"));
					}
				}).window('open');
			},
			/* 收入保存 */
			save : function() {
				var form = $('#win_finance_account_out_form');
				form.form('submit', {
					url: '/web/finance/saveAccountOut',
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
                        $.messager.progress('close');
						data = $.parseJSON(data);
                       // $.messager.progress('close');
						if (data.errcode == 0) {
                            parent.$.h.index.setOperateInfo({
                                module:'账户流水',
                                operate:'支出',
                                content:data.errmsg,
                                icon: 'icon-ok'
                            }, true);
                            $('#win_finance_account').window('close');
							$.h.fa.btnAccountRecord();
							return '';
						}
						parent.$.h.index.setOperateInfo({
							module:'账户流水',
							operate:'支出',
							content:data.errmsg,
							icon: 'hr-error'
						}, true);


					}

				});
			}
		},
		/* 账户互转 */
		winLoop : {
			open : function(_fun) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 420) / 5;
				$('#win_finance_account').window({
					title:'账户互转',
					dataType:'json',
					top:top,
					left:left,
					height:425,
					width:400,
					href: '/web/finance/finance_account_loop',
					onLoad: _fun || function() {
						history._id = '';
						history._lockVersion = 0;
						history._ordersCode = '';
						$('#create_time').datebox('setValue',(new Date()).Format("yyyy-MM-dd"));
					}
				}).window('open');
			},
			save : function() {
				var form = $('#win_finance_account_loop_form');
				if ($('#settlement_id_out').combobox('getValue') == $('#settlement_id_in').combobox('getValue')) {
					parent.$.h.index.setOperateInfo({
						module:'账户流水',
						operate:'账户互转',
						content:'转出账户、转入账户不能相同。',
						icon:'hr-warn'
					}, false);
					return;
				}
				
				form.form('submit', {
					url: '/web/finance/saveAccountLoop',
					queryParams : {
						/* 数据 ID */
						details_id:history._id,
						/* 单据编号 */
						orders_code:history._ordersCode,
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
                        $.messager.progress('close');
						data = $.parseJSON(data);
						if (data.errcode == 0) {
                            parent.$.h.index.setOperateInfo({
                                module:'账户流水',
                                operate:'账户互转',
                                content:data.errmsg,
                                icon: 'icon-ok'
                            }, true);
                            $('#win_finance_account').window('close');
							$.h.fa.btnAccountRecord();
							return ;
						}
						parent.$.h.index.setOperateInfo({
							module:'账户流水',
							operate:'账户互转',
							content:data.errmsg,
							icon: 'hr-error'
						}, true);
					}
				});
			}
		},
		/* 期初调整 */
		winBegin : {
			open : function(_fun) {
				var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 400) / 2;
				var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 380) / 5;
				$('#win_finance_account').window({
					title:'期初调整',
					dataType:'json',
					top:top,
					left:left,
					height:400,
					width:400,
					href: '/web/finance/finance_account_begin',
					onLoad: _fun || function() {
						history._id = '';
						history._ordersCode = '';
						history._lockVersion = 0;
						//if (settlementObj == null) return;
						//$('#settlement_id').combobox('setValue',settlementObj);
						//$.h.fa.winBegin.onSelect(0, settlementObj);
					}
				}).window('open')
			},
			save : function() {
				var form = $('#win_finance_account_begin_form');
				form.form('submit', {
					url: '/web/finance/saveAccountBegin',
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
                        $.messager.progress('close');
						data = $.parseJSON(data);
						if (data.errcode == 0) {
                            parent.$.h.index.setOperateInfo({
                                module:'账户流水',
                                operate:'期初调整',
                                content:data.errmsg,
                                icon: 'icon-ok'
                            }, true);
                            $('#win_finance_account').window('close');
							$.h.fa.btnAccountRecord();
						}
						parent.$.h.index.setOperateInfo({
							module:'账户流水',
							operate:'期初调整',
							content:data.errmsg,
							icon: 'hr-error'
						}, true);
					}
				});
			},
			/* 账户选择改变事件 */
			onSelect : function(sData) {

				history._id = '';
				history._lockVersion = 0;
				history._ordersCode = '';
				$.ajax({
					url: '/web/finance/loadAccountBeginItem',
					data: {
						settlement_id:sData.settlement_id
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
							$('#account_balance').numberbox('setValue',data.row.account_balance);
							$('#income').numberspinner('setValue',data.row.income);
							$('#create_time').datebox('setValue',$.DT.UnixToDate(data.row.create_time));
							$('#remark').textbox('setValue',data.row.remark);
						} else {
							history._id = '';
							history._ordersCode = '';
							history._lockVersion = 0;
							$('#account_balance').numberbox('setValue',sData.settlement_money);
							$('#income').numberspinner('reset');
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
			$('#begin_date').datebox('setValue',d.beginFormat);
			$('#end_date').datebox('setValue',d.endFormat);
		},
		/* 库存流水查询 */
		btnAccountRecord:function(e) {
			var form = $('#query_form');
			if(!form.form('validate')) return;

			var params = {};
			form.find('input,select').each(function() {
				if (this.name && $.trim(this.value)) {
					if (this.name == 'begin_date' || this.name == 'end_date') {
						params[this.name] = $.DT.DateToUnix(this.value);
					} else {
						params[this.name] = this.value
					}
				}
			});
			if ($.isEmptyObject(params)) return;

			$('#record_grid').datagrid({
				url:'/web/finance/queryAccountRecord',
				method:'post',
				queryParams:params,
				onLoadSuccess:function(data) {
					$('#sum_income').numberbox('setValue', data.sum[0].income);
					$('#sum_expend').numberbox('setValue', data.sum[0].expend);
					$('#sum_surplus').numberbox('setValue', data.sum[0].surplus);
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
							href: '/web/purchase/purchase_orders_center_complete',
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
					case 9199:/* 收入 */
						$.h.fa.winIn.open(function() {
							$.ajax({
								url: '/web/finance/loadAccountRecordItem',
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
										history._id = data.data.details_id;
										history._lockVersion = data.data.lock_version;
										history._ordersCode = data.data.orders_code;
										$('#settlement_id').combobox('setValue',data.data.settlement_id);
										$('#account_id').combobox('setValue',data.data.account_id);
										$('#income').numberspinner('setValue',data.data.income);
										$('#create_time').datebox('setValue',$.DT.UnixToDate(data.data.order_date));
                                        $('#remark').textbox('setValue',data.data.remark);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							});
						});
						break;
					case 9198:/* 支出 */
						$.h.fa.winOut.open(function() {

							$.ajax({
								url: '/web/finance/loadAccountRecordItem',
								data: {
									details_id:row.details_id
								},
								type:'post',
								cache:false,
								dataType:'json',
								beforeSend: function(xhr) {
									$.messager.progress({title:'请稍等',msg:'数据加载中...'});
								},
								success: function(data) {
									if (data.errcode == 0) {
										history._id = data.data.details_id;
										history._lockVersion = data.data.lock_version;
										history._ordersCode = data.data.orders_code;
										$('#settlement_id').combobox('setValue',data.data.settlement_id);
										$('#account_id').combobox('setValue',data.data.account_id);
										$('#expend').numberspinner('setValue',data.data.expend);
										$('#create_time').datebox('setValue',$.DT.UnixToDate(data.data.order_date));
										$('#remark').textbox('setValue',data.data.remark);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							});
						});
						break;
					case 9107:/* 账户互转 */
						$.h.fa.winLoop.open(function() {

							$.ajax({
								url: '/web/finance/loadAccountLoopItem',
								data: {
									orders_code:row.orders_code
								},
								type:'post',
								cache:false,
								dataType:'json',
								beforeSend: function(xhr) {
									$.messager.progress({title:'请稍等',msg:'数据加载中...'});
								},
								success: function(data) {
									if (data.errcode == 0) {
										history._id = data.data.details_id;
										history._lockVersion = data.data.lock_version;
										history._ordersCode = data.data.orders_code;

										$('#settlement_id_out').combobox('setValue',data.data.settlement_id_out);
										$('#settlement_id_in').combobox('setValue',data.data.settlement_id_in);
										$('#ta_money').numberspinner('setValue',data.data.ta_money);
										$('#create_time').datebox('setValue',$.DT.UnixToDate(data.data.order_date));
										$('#remark').textbox('setValue',data.data.remark);
									}
								},
								complete: function() {
									$.messager.progress('close');
								}
							});
						});
						break;
					case 9105:/* 期初调整 */
						//$.h.fa.winBegin.open({settlement_id:row.settlement_id,settlement_name:row.settlement_name});
						$.h.fa.winBegin.open(function() {

                            $.ajax({
                                url: '/web/finance/loadAccountRecordItem',
                                data: {
                                    details_id:row.details_id
                                },
                                type:'post',
                                cache:false,
                                dataType:'json',
                                beforeSend: function(xhr) {
                                    $.messager.progress({title:'请稍等',msg:'数据加载中...'});
                                },
                                success: function(data) {
                                    if (data.errcode == 0) {
                                        history._id = data.data.details_id;
                                        history._lockVersion = data.data.lock_version;
                                        history._ordersCode = data.data.orders_code;

                                        $('#settlement_id').combobox('setValue',data.data.settlement_id);
                                        $('#income').numberbox('setValue',data.data.income);
                                        $('#create_time').datebox('setValue',$.DT.UnixToDate(data.data.order_date));
                                        $('#remark').textbox('setValue',data.data.remark);
                                    }
                                },
                                complete: function() {
                                    $.messager.progress('close');
                                }
                            });
                        });
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
						url: '/web/finance/delAccountRecordItem',
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
                                parent.$.h.index.setOperateInfo({
                                    module:'账户流水',
                                    operate:'删除',
                                    content:'删除成功',
                                    icon: 'icon-ok'
                                }, true);
								$.h.fa.btnAccountRecord();
								return ;
							}
                            parent.$.h.index.setOperateInfo({
                                module:'账户流水',
                                operate:'删除',
                                content:data.errmsg,
                                icon: 'hr-error'
                            }, true);

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