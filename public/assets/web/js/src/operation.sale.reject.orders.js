/**
 * 销售退货单函数
 */
(function($) {
    var history = {
        _id:'',
        _lockVersion:0,
		isSave:true,
		/* 单据状态（单据查询中使用） */
		ordersStatus:-1,
        ordersCode:undefined,
        goodsNumber:undefined,
        goodsCode:undefined,
		ordersMoney:0,
        csList:[]
	};

    /**
     * 主要的
     */
    $.h.sro = {
        /**
         * 主加载
         */
        mLoad : function(e) {
            
        },
        /**
         * 搜索客户
         */
        searchCustomer : function() {
			
        },
        /**
         * 搜索商品
         */
        searchGoods : function(target) {
			var isValid = $('#orders_form').form('validate');
			if (!isValid) {
				parent.$.h.index.setOperateInfo({
					module:'销售退货单',
					operate:'查询商品',
					content:'请完整填写单据信息~~',
					icon:'hr-warn'
				}, true);
				return;
			}
			var warehouseId = $('#warehouse_id').combobox('getValue');

			$.h.s.goods.onSearchGoods({
				/*对象*/
				target:target,
				/*仓库ID*/
				warehouseId:warehouseId,
				/*默认折扣*/
				goodsDiscount:90,
				/*回调函数*/
				afterFun:function(data) {
					/*设置为未保存*/
					history.isSave = false;
					data.rows.forEach(function(row) {
						row.goods_type = data.isReject;
						if (!handleGridGoodsRepeat('#orders_grid_details', -1, row)) {
							$('#orders_grid_details').datagrid('insertRow',{index:0,row:row});
						}
					});

					var newValue = {number:data.total_number,money:data.total_money,dmoney:data.total_dmoney};
					
					calcTotal(data.isReject, null, newValue);
				}
			});
        },
		/**
		 * 加载单据列表
		 */
		loadOrdersList : function(e) {
			history.ordersStatus = $(this).linkbutton('options').status;
			$('#orders_list').datagrid('reload',{status:history.ordersStatus});
		},
		/**
		 * 查询列表
		 */
		searchOrdersList : function(e) {
			var params = {};
			$('#list_search_form').find('input,select').each(function() {
				if (this.name && $.trim(this.value)) {
					params[this.name] = this.value
				}
			});
			if ($.isEmptyObject(params)) return;
			params['status'] = history.ordersStatus;
			$('#orders_list').datagrid({queryParams:params,method:'post'});
		},
        /**
         * 销售退货单商品列表操作
         */
        mGrid : {
			/**
			 * 单击单元格事件
			 */
			onClickCell : function(rowIndex, field, value, row) {
				var opts = $(this).datagrid('options');
				if (opts.endEditing.call(this)) {
					if (field == 'goods_type') {
						var row = $(this).datagrid('getRows')[rowIndex];
						var oldValue = {number:row.goods_number,money:row.goods_tmoney,dmoney:row.goods_tdamoney};
						calcTotal(row.goods_type, oldValue, null);
						row.goods_type = row.goods_type == 1 ? 2 : 1;
						calcTotal(row.goods_type, null, oldValue);
						$(this).datagrid('updateRow',{
							index:rowIndex,
							update:true,
							row: {
								goods_type:row.goods_type
							}
						});
						$(this).datagrid('refreshRow', rowIndex);
						return;
					}
					for(var i = 0, n = opts.editFields.length; i < n; i++) {
						if (field == opts.editFields[i]) {
							$(this).datagrid('editCell', {index:rowIndex,field:field});
							opts.editIndex = rowIndex;
                            opts.editFieldIndex = i;
                            //var row = opts.finder.getRow(this, rowIndex);
                            switch (field) {
                                case 'goods_number':
									history.goodsNumber = value;
									break;
								case 'color_id':
									var ed = $(this).datagrid('getEditor', {index:rowIndex,field:'color_id'});
									$(ed.target).combobox('setValue', row.color_id).combobox('setText', row.color_name);
									var data = history.csList[row.goods_code];
									if (data && data.colors) {
										$(ed.target).combobox('loadData', data.colors);
										return;
									}
									$(ed.target).combobox({
										url:'/web/cell_editing/getGoodsColorData',
										queryParams:{
											goods_code:row.goods_code
										},
										onLoadSuccess : function() {
											var arr = {'colors':$(this).combobox('getData')};
											if (data && data.sizes) {
												arr = {'sizes':data.sizes,'colors':$(this).combobox('getData')};
											}
											history.csList[row.goods_code] = arr;
											console.log(history.csList);
											$(this).combobox('select', row.color_id);
										}
									});
									break;
								case 'size_id':
									var ed = $(this).datagrid('getEditor', {index:rowIndex,field:'size_id'});
									$(ed.target).combobox('setValue', row.size_id).combobox('setText', row.size_name);
									var data = history.csList[row.goods_code];
									if (data && data.sizes) {
										$(ed.target).combobox('loadData', data.sizes);
										return;
									}
									$(ed.target).combobox({
										url:'/web/cell_editing/getGoodsSizeData',
										queryParams:{
											goods_code:row.goods_code
										},
										onLoadSuccess : function() {
											var arr = {'sizes':$(this).combobox('getData')};
											if (data && data.colors) {
												arr = {'sizes':$(this).combobox('getData'),'colors':data.colors};
											}
											history.csList[row.goods_code] = arr;
											$(this).combobox('select', row.size_id);
										}
									});
									break;
								case 'goods_price':
									var mPrivateMenu = $('#mPrivateMenu');
									mPrivateMenu.html('');
									mPrivateMenu.menu('appendItem', {value:row.goods_wprice, text: '采购价:' + $.formatMoney(row.goods_wprice,'￥'),iconCls:'hjtr-money'});
									break;
                            }
							break;
						}
					}
				}
			},
			/**
			 * 结束编辑事件
			 */
			onEndEdit : function(index, row) {
				var edColor = $(this).datagrid('getEditor', {index:index,field:'color_id'});
				if (edColor && edColor.type == "combobox") {
					if ($.trim($(edColor.target).combobox('getText')) != '') {
						row.color_name = $(edColor.target).combobox('getText');
					}
				}
				var edSize = $(this).datagrid('getEditor', {index:index,field:'size_id'});
				if (edSize && edSize.type == "combobox") {
					if ($.trim($(edSize.target).combobox('getText')) != '') {
						row.size_name = $(edSize.target).combobox('getText');
					}
				}
			},
			/**
			 * 编辑完成之后执行事件
			 */
			onAfterEdit : function(index, row, changes) {
				if (!$.isEmptyObject(changes)) {
                    var arr = new Array();
                    var name,value;
                    for(key in changes) {
                        name = key;
                        value = changes[key];
                    }
                    /*设置未保存状态*/
					history.isSave = false;
                    switch (name) {
                        case 'goods_number':
                            /*商品数量*/
							setGridGoodsNumber(index,row,value);
                            break;
                        case 'goods_price':
                            /*商品单价*/
							setGridGoodsPrice(index,row,value);
                            break;
                        case 'goods_discount':
                            /*折扣*/
							setGridGoodsDiscount(index,row,value);
                            break;
                        case 'goods_daprice':
                            /*折扣价*/
							setGridGoodsDiscountAfterPrice(index,row,value);
                            break;
						case 'color_id':
                            row.color_id = value;
							handleGridGoodsRepeat('#orders_grid_details', index, row);
							break;
						case 'size_id':
                            row.size_id = value;
							handleGridGoodsRepeat('#orders_grid_details', index, row);
							break;
                    }
                }
			},
			/**
			 * 加载之前执行事件
			 */
			onBeforeLoad : function(param) {
                /*可编辑字段*/
                $(this).datagrid('options').editFields = ['color_id','size_id','goods_number','goods_price','goods_discount','goods_daprice'];
                /*绑定 Grid 事件*/
				$(this).datagrid('bindKeyEvent');

				/* Enter 商品查询事件 */
				$('#search_keyword').combogrid('options').keyHandler.enter = function(e) {
					$.h.sro.searchGoods($(e.data.target));
				};
                /**
                 * 检查商品库存窗口 Grid
                 * 单击单元格事件
                 */
                $.h.c.cc.onClickCellCallback = function(value) {
                    history.goodsNumber = value;
                }
                /**
                 * 检查商品库存窗口 Grid
                 * 编辑完成之后执行事件
                 */
                $.h.c.cc.onAfterEditCallback = function(index, row, value) {
                    rows = $('#orders_grid_details').datagrid('getRows');
					rows.forEach(function(item, rowIndex) {
						if (item.goods_code == row.goods_code && item.color_id == row.color_id && item.size_id == row.size_id) {
							/*商品数量*/
							setGridGoodsNumber(rowIndex,item,value);
						}
					});
                }
			}
        },
        /**
		 * 新开单
		 */
		mNewOrders : function(e) {
            if (!history.isSave) {
                $.messager.confirm('提示', '未保存操作，确定要离开吗？~~~', function(r){
					if (r) { _a(); }
				});
			} else { _a(); }
            function _a() {
                $('#main_layout').layout('panel','center').panel({
					href:$.toUrl('sale', 'sale_reject_order_center'),
					onBeforeLoad : initOrdersData,
					onLoad : function() {}
				});
            }
		},
        /**
         * 保存销售退货单（草稿）
         */
        mSaveRoughDraft : function(e) {
            mSaveOrders(
				'/web/sale_reject_order/saveRejectOrdersRoughDraf',
				function(data) {
					if (data.errcode == 0) {
                        /* 单据ID */
                        history._id = data.data.orders.orders_id;
                        /* 版本锁 */
                        history._lockVersion = data.data.orders.lock_version;
                        /* 单据编号 */
                        history.ordersCode = data.data.orders.orders_code;

						$('#orders_code_view').text(data.data.orders.orders_code);
						$('#orders_grid_details').datagrid('acceptChanges');
						$('#orders_list').datagrid('reload');
						/*设置为已保存*/
						history.isSave = true;
					}
				},'草稿'
			);
        },
        /**
		 * 保存销售退货单（提交）
		 */
		mSaveFormally : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(isConfirm) {
				if (isConfirm) {
					mSaveOrders(
						'/web/sale_reject_order/saveRejectOrdersFormally',
						function(data) {
							if (data.errcode == 0) {
                                /* 初始化单据 */
                                initOrdersData();
                                $('#orders_list').datagrid('reload');
                                /* 单据ID */
                                history._id = data.data.orders.orders_id;
                                /* 版本锁 */
                                history._lockVersion = data.data.orders.lock_version;
                                /* 单据编号 */
                                history.ordersCode = data.data.orders.orders_code;

                                href = $.toUrl('sale', 'sale_reject_order_center_complete');
                                ordersController({
                                    href:href,
                                    orders:data.data.orders,
                                    details:data.data.orders.details
                                });
							}
							parent.$.h.index.setOperateInfo({
								module:'销售退货单',
								operate:'提交',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hr-error'
							}, true);
							$.messager.progress('close');
						},'提交');
				}
			});
        },
		/**
		 * 删除销售退货单
		 */
		mDelOrders : function(e) {
            if (history.ordersCode == "") {
				parent.$.h.index.setOperateInfo({
					module:'销售退货单',
					operate:'删除商品',
					content:'销售退货单单据编号错误~~',
					icon:'hjtr-error'
				}, false);
                return;
            }
            $.messager.confirm('提示信息', '您想要删除该单据吗？', function(isConfirm){
	            if (isConfirm) {
                    $.ajax({
                        url: '/web/sale_reject_order/delRejectOrder',
                        data: {
                            orders_code : history.ordersCode
                        },
                        type:'post',
                        cache:false,
                        dataType:'json',
                        beforeSend: function(xhr) { },
                        success: function(data) {
                            if (data.errcode == 0) {
                                $('#main_layout').layout('panel','center').panel({
									href:$.toUrl('sale', 'sale_reject_order_center'),
									onBeforeLoad : initOrdersData,
									onLoad : function() {}
								});
                                $('#orders_list').datagrid('reload');
                            }
							parent.$.h.index.setOperateInfo({
								module:'销售退货单',
								operate:'删除单据',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hr-error'
							}, true);
                        },
                        complete: function() {
							history.ordersCode = undefined;
						}
                    });
                }
            });
		},
		/**
		 * 删除单据中的商品
		 */
		mDelGoods : function(e) {
			var target = $('#orders_grid_details');
			var rows = target.datagrid("getChecked");
			//退货
			var orders_gr_number = $('#orders_gr_number');
			var orders_gr_money = $('#orders_gr_money');
			var orders_grd_money = $('#orders_grd_money');
			var _gr_number = parseInt(orders_gr_number.numberbox('getValue'));
			var _gr_money = parseFloat(orders_gr_money.numberbox('getValue'));
			var _grd_money = parseFloat(orders_grd_money.numberbox('getValue'));
			//销售
            var orders_gs_number = $('#orders_gs_number');
            var orders_gs_money = $('#orders_gs_money');
            var orders_gsd_money = $('#orders_gsd_money');
            var _gs_number = parseInt(orders_gs_number.numberbox('getValue'));
            var _gs_money = parseFloat(orders_gs_money.numberbox('getValue'));
            var _gsd_money = parseFloat(orders_gsd_money.numberbox('getValue'));
			if (rows.length < 1) {
				parent.$.h.index.setOperateInfo({
					module:'销售退货单',
					operate:'删除商品',
					content:'请勾选需要删除的行数据~~',
					icon:'hjtr-warn'
				}, false);
                return;
			}
			for (var i = rows.length - 1; i >= 0; i--) {  
				var index = target.datagrid('getRowIndex',rows[i]);  
				target.datagrid('deleteRow', index);
				if(rows[i].goods_type == 2) {
                    _gr_number -= parseInt(rows[i].goods_number);
                    _gr_money -= parseFloat(rows[i].goods_tmoney);
                    _grd_money -= parseFloat(rows[i].goods_tdamoney)
                }else {
                    _gs_number -= parseInt(rows[i].goods_number);
                    _gs_money -= parseFloat(rows[i].goods_tmoney);
                    _gsd_money -= parseFloat(rows[i].goods_tdamoney)
				}
			}
			//退货赋值
            orders_gr_number.numberbox('setValue', _gr_number);
            orders_gr_money.numberbox('setValue', _gr_money);
            orders_grd_money.numberbox('setValue', _grd_money);
            //销售赋值
            orders_gs_number.numberbox('setValue', _gs_number);
            orders_gs_money.numberbox('setValue', _gs_money);
            orders_gsd_money.numberbox('setValue', _gsd_money);
            //退销相抵-应付金额
			var _rs_money = _gr_money - _gs_money;
			//退销相抵-折后应付金额
			var  _rsd_money = _grd_money - _gsd_money;
			$('#orders_rs_money').html($.formatMoney(_rs_money,'￥'));
			$('#orders_rsd_money').html($.formatMoney(_rsd_money,'￥'));
			$('#orders_pmoney').numberbox('setValue',_rsd_money)
			/*设置为未保存*/
			history.isSave = false;
		},
        /**
		 * 双击单据列表事件
		 */
		onOrdersDblClickRow : function(index,row) {
			if (history.isSave) {
				onOrdersDblClickRow(index,row);
			} else {
				$.messager.confirm('提示','您做的更改可能不会被保存，确定要这样做吗？',function(_isRun) {
					if (_isRun) {
						history.isSave = true;
						onOrdersDblClickRow(index,row);
					}
				});
			}
		},
		/**
		 * 单元格单击单据列表事件
		 */
		onOrdersClickCell : function(index, field, value, row) {
			if (field != 'orders_id2') return;
			onOrdersDblClickRow(index,row);
		},
        /**
		 * 采购单完成页面
		 */
		complete : {
			onBeforeLoad : function(param) {
				var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
				_a.css('background','url(/assets/web/img/complete.png) no-repeat center center');
			}
		},
		/**
		 * 采购单撤销页面
		 */
		reject : {
			onBeforeLoad : function(param) {
				var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
				_a.css('background','url(/assets/web/img/reject.png) no-repeat center center');
			}
		},
		/**
		 * 单据修改按钮
		 */
		modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        },
		/**
		 * 退货/采购，按钮
		 */
		mButton : {
            /*1、销售，2、退货*/
			formatter : function(value,row,index) {
				var txt = '退货', bgColor = 'red';
				if (value == 1) {
					txt = '销售', bgColor = 'green';
				}
				return "<input type='button' value='"+txt+"' style='background-color:"+bgColor+";color:white;padding:0px;width:38px;height:22px;'></a>";
			}
		},
		/**
		 * 抹零
		 */
		mEraseChange : function(newValue, oldValue) {
			/*合计总金额*/
			var erase = parseFloat(newValue);
			$('#orders_pmoney').numberbox('setValue', isNaN(erase) ? history.ordersMoney : history.ordersMoney - erase);
		},
		/**
		 * 实际金额金额改变事件
		 */
		onGoodsMoneyChange : function(newValue, oldValue) {
			$('#orders_rmoney').numberbox('setValue', newValue);
		}
		
		
    }
	/**
	 * 保存单据函数
     * @param url 地址
     * @param fun 回调函数
	 */
	function mSaveOrders(url, _fun, txt) {
		var grid = $('#orders_grid_details');
		grid.datagrid('options').endEditing.call(grid);
		/*验证商品表中是否存在数据*/
		if (grid.datagrid('getRows').length < 1) {
			parent.$.h.index.setOperateInfo({
				module:'销售退货单',
				operate:txt,
				content:'商品列表中没有任何数据~~',
				icon:'hjtr-warn'
			}, false);
			return false;
		}
		var form = $('#orders_form');
		var target = $(this);
		var rowsInsert = grid.datagrid('getChanges','inserted');
        if (rowsInsert.length > 0) {
            rowsInsert = $.extend(true, [], rowsInsert);
            rowsInsert.forEach(function(row,index) {
                delete rowsInsert[index].goods_serial;
                delete rowsInsert[index].goods_name;
                delete rowsInsert[index].goods_barcode;
                delete rowsInsert[index].color_name;
                delete rowsInsert[index].size_name;
				delete rowsInsert[index].goods_wprice;
            });
        }
		var rowsUpdate = grid.datagrid('getChanges','updated');
        if (rowsUpdate.length > 0) {
            rowsUpdate = $.extend(true, [], rowsUpdate);
            rowsUpdate.forEach(function(row,index) {
                delete rowsUpdate[index].goods_serial;
                delete rowsUpdate[index].goods_name;
                delete rowsUpdate[index].goods_barcode;
                delete rowsUpdate[index].color_name;
                delete rowsUpdate[index].size_name;
				delete rowsUpdate[index].goods_wprice;
            });
        }
		var rowsDelete = grid.datagrid('getChanges','deleted');
        if (rowsDelete.length > 0) {
            rowsDelete = $.extend(true, [], rowsDelete);
            rowsDelete.forEach(function(row,index) {
                delete rowsDelete[index].goods_serial;
                delete rowsDelete[index].goods_name;
                delete rowsDelete[index].goods_barcode;
                delete rowsDelete[index].color_name;
                delete rowsDelete[index].size_name;
				delete rowsDelete[index].goods_wprice;
            });
        }
		/* 抹零金额 */
		var orders_emoney = $('#orders_emoney').numberspinner('getValue');
		orders_emoney || (orders_emoney = 0);
		/* 应付金额 */
		var orders_pmoney = $('#orders_pmoney').numberspinner('getValue');
		orders_pmoney || (orders_pmoney = 0);
		form.form('submit', {
			url: url,
			queryParams:{
                /* 版本锁 */
                lock_version:history._lockVersion,
                /* 单据编号 */
                orders_code:history.ordersCode,
				/* 应付金额 */
				orders_emoney:orders_emoney,
				/* 抹零金额 */
				orders_pmoney:orders_pmoney,
				/*新增商品数据*/
				data_insert:JSON.stringify(rowsInsert),
				/*更新商品数据*/
				data_update:JSON.stringify(rowsUpdate),
				/*删除商品数据*/
				data_delete:JSON.stringify(rowsDelete)
			},
			onSubmit : function() {
                /*验证表单所有对象*/
                isValid = $('#orders_form').form('validate');
                if (!isValid) {
					parent.$.h.index.setOperateInfo({
						module:'销售退货单',
						operate:'保存单据',
						content:'数据验证失败~~',
						icon:'hjtr-warn'
					}, false);
                    return false;
                }
				$.messager.progress({title:'请稍候',msg:'正在保存销售退货单据......'});
				return true;
			},
			success : function(data) {
                data = $.parseJSON(data);
				var msg = 'icon-ok';
				switch(data.errcode) {
                    case 0:/* 正常提交 */
                        _fun(data);
                        break;
                    case 2:/* 库存不足 */
						/* 单据ID */
						history._id = data.orders.orders_id;
						/* 版本锁 */
						history._lockVersion = data.orders.lock_version;
						/* 单据编号 */
						history.ordersCode = data.orders.orders_code;
                        $.h.window.winCheckGoods.open(function() {
                            /* 加载数据 */
                            //var returnData = $.extend(true, [], data.rows);
                            $('#win_base_check_grid').datagrid('loadData',data.rows);
                            /* 提交按钮事件 */
                            $('#win_base_check_submit').linkbutton({
                                onClick: function() {
                                    $.h.window.winCheckGoods.close();
                                    mSaveOrders(url, _fun, txt);
                                }
                            });
                            $('#win_base_check_direct_submit').linkbutton({
                                onClick: function() {
                                    $.h.window.winCheckGoods.close();
                                    mSaveOrders(url + "No", _fun, txt);
                                }
                            });
                        });
						msg = 'hjtr-warn';
                        break;
					default:
						msg = 'hjtr-error';
                }
				parent.$.h.index.setOperateInfo({
					module:'销售单',
					operate:txt,
					content:data.errmsg,
					icon:msg
				}, true);
                $.messager.progress('close');
			}
		});
	}

    /**
     * 初始化单据数据
     */
    function initOrdersData() {
        /* 清除 csList */
        history.csList = [];
        history.ordersCode = undefined;
        history._id = '';
        history._lockVersion = 0;
		history.ordersMoney = 0;
		history.isSave = true;
		/* 单据状态（单据查询中使用） */
		history.ordersStatus = -1;
        history.goodsNumber = undefined;
        history.goodsCode = undefined;
		history.ordersMoney = 0;
    }

    /**
	 * 双击单据列表行
	 */
	function onOrdersDblClickRow(index,row) {
		$.ajax({
			url: '/web/sale_reject_order/loadOrderDetails',
			data: {
				orders_code:row.orders_code
			},
			type:'post',
			cache:false,
			dataType:'json',
			beforeSend: function(xhr) {
                /* 初始化单据数据 */
                initOrdersData();
				$.messager.progress({title:'请稍等',msg:'正在加载数据...'});
			},
			success: function(data) {
				if (data.errcode == 0) {
					var parame = {
						orders:data.orders,
						details:data.details
					};
					switch (data.data.orders_status) {
						case 0:
						case 8:/*草稿*/
							href = $.toUrl('sale', 'sale_reject_order_center');
                            /* 单据ID */
                            history._id = data.data.orders_id;
                            /* 版本锁 */
                            history._lockVersion = data.data.lock_version;
                            /* 单据编号 */
                            history.ordersCode = data.data.orders_code;
							draftController({
								href:href,
								orders:data.data,
								details: data.data.details
							});
							break;
						case 9:/*销售退货单*/
							href = $.toUrl('sale', 'sale_reject_order_center_complete');
							ordersController({
                                href:href,
								orders:data.data,
								details:data.data.details
							});
							break;
					}
					
				}
			},
			complete: function() {
				$.messager.progress('close');
			}
		});
	}

    /**
     * 草稿单据页面
     */
    function draftController(data) {
        var controller = $('#main_layout').layout('panel','center');
        controller.panel({
            href:data.href,
            onLoad : function() {
                data.orders.orders_date = $.DT.UnixToDate(data.orders.orders_date, 0, false);
                data.orders.warehouse_id == 0 && delete data.orders.warehouse_id;
                data.orders.settlement_id == 0 && delete data.orders.settlement_id;
                data.orders.delivery_id == 0 && delete data.orders.delivery_id;
				/*设置数据*/
				commonController(data);
                $('#orders_form').form('load', data.orders);
                /*客户编号、名称*/
                $('#client_id').combogrid('setValue',data.orders.client_id);
                $('#client_id').combogrid('setText',data.orders.client_name);
                $('#orders_code_view').text(data.orders.orders_code);
                $('#orders_remark').textbox('setValue', data.orders.orders_remark);
                $('#orders_grid_details').datagrid('loadData',data.details);
				
            }
        });
    }
    /**
     * 正式单据页面
     */
    function ordersController(data) {
        var controller = $('#main_layout').layout('panel','center');
        controller.panel({
            href:data.href,
            onLoad : function() {
				/*设置数据*/
				commonController(data);
                $('#client_name').html(data.orders.client_name);
				$('#orders_rmoney').html(data.orders.orders_rmoney);
                $('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
                $('#warehouse_name').html(data.orders.org_name);
                $('#settlement_name').html(data.orders.settlement_name);
                $('#delivery_name').html(data.orders.dict_name);
				$('#orders_remark').html(data.orders.orders_remark);
				
            }
        });
    }
	function commonController(data) {
		/*显示单号*/
		$('#orders_code_view').html(data.orders.orders_code);
		/*单据合计数量*/
		$('#orders_goods_number').numberbox('setValue',data.orders.orders_goods_number);
		/*单据合计金额*/
		$('#orders_goods_money').numberbox('setValue',data.orders.orders_goods_money);
		/*加载 GRID 数据*/
		$('#orders_grid_details').datagrid('loadData', data.details);
		/*设置单据统计数据*/
		
		history.ordersMoney = data.orders.orders_rsd_money;
		//销售
		var orders_gs_number = 0;
		var orders_gs_money = 0;
		var orders_gsd_money = 0;
		//退货
		var orders_gr_number = 0;
		var orders_gr_money = 0;
		var orders_grd_money = 0;
		data.details.forEach(function (e) {
			//如果是销售详情
			if(e.goods_type == 1){
                orders_gs_number = orders_gs_number + parseFloat(e.goods_number);
                orders_gs_money = orders_gs_money + parseFloat(e.goods_tmoney);
                orders_gsd_money = orders_gsd_money + parseFloat(e.goods_tdamoney);
			}else {
                orders_gr_number = orders_gr_number + parseFloat(e.goods_number);
                orders_gr_money = orders_gr_money + parseFloat(e.goods_tmoney);
                orders_grd_money = orders_grd_money + parseFloat(e.goods_tdamoney);
			}
        })
        var orders_rs_money = orders_gr_money - orders_gs_money;
        var orders_rsd_money = orders_grd_money - orders_gsd_money;
		//销售赋值
        $('#orders_gs_number').numberbox('setValue', orders_gs_number);
        $('#orders_gs_money').numberbox('setValue', orders_gs_money);
        $('#orders_gsd_money').numberbox('setValue', orders_gsd_money);
        //退货赋值
		$('#orders_gr_number').numberbox('setValue', orders_gr_number);
		$('#orders_gr_money').numberbox('setValue', orders_gr_money);
		$('#orders_grd_money').numberbox('setValue', orders_grd_money);
        // 退销相抵-应付金额
		$('#orders_rs_money').html($.formatMoney(orders_rs_money,'￥'));
		//退销相抵-折后应付金额
		$('#orders_rsd_money').html($.formatMoney(orders_rsd_money,'￥'));
		//应付总计
		$('#orders_pmoney').numberbox('initValue', data.orders.orders_pmoney).numberbox('setValue', data.orders.orders_pmoney);
		//抹零
		$('#orders_emoney').numberbox('initValue', data.orders.orders_emoney).numberbox('setValue', data.orders.orders_emoney);
		
	}
	/**
	 * 设置 收/付
	 */
	function setIncomeOrPaymentText(txt) {
		var items = $("span[name='iop_text']");
		items.each(function(index, item) {
			$(index).html(txt);
		});
	}
	/**
	 * 计算退销相抵
	 */
	function calcTotal(isReject, oldValue, newValue) {
		/*销售合计数量、金额、折后金额;*/
		var sNumber = 0, sMoney = 0, sDMoney = 0;

		var tNumber = '#orders_gs_number';
		var tMoney = '#orders_gs_money';
		var tDMoney = '#orders_gsd_money';
		if (isReject == 2) {
			tNumber = '#orders_gr_number';
			tMoney = '#orders_gr_money';
			tDMoney = '#orders_grd_money';
		}
		sNumber = parseInt($(tNumber).numberbox('getValue'));
		sMoney = parseFloat($(tMoney).numberbox('getValue'));
		sDMoney = parseFloat($(tDMoney).numberbox('getValue'));
		if (oldValue) {
			sNumber -= parseInt(oldValue.number);
			sMoney -= parseFloat(oldValue.money);
			sDMoney -= parseFloat(oldValue.dmoney);
		}
		if (newValue) {
			sNumber += parseInt(newValue.number);
			sMoney += parseFloat(newValue.money);
			sDMoney += parseFloat(newValue.dmoney);
		}
		$(tNumber).numberbox('setValue', sNumber);
		$(tMoney).numberbox('setValue', sMoney);
		$(tDMoney).numberbox('setValue', sDMoney);
		
		/*退销相抵-应付/收金额*/
		tNumber = '#orders_gr_number';
		tMoney = '#orders_gr_money';
		tDMoney = '#orders_grd_money';
		if (isReject == 2) {
			tNumber = '#orders_gs_number';
			tMoney = '#orders_gs_money';
			tDMoney = '#orders_gsd_money';
		}
		var rNumber = 0, rMoney = 0, rDMoney = 0;
		rNumber = parseInt($(tNumber).numberbox('getValue'));
		rMoney = parseFloat($(tMoney).numberbox('getValue'));
		rDMoney = parseFloat($(tDMoney).numberbox('getValue'));

		var eNumber = 0, eMoney = 0, eDMoney = 0;
		/* isReject 1、销售，2、退货 */
		if (isReject == 2) {
			eMoney = sMoney - rMoney;
			eDMoney = sDMoney - rDMoney;
		} else {
			eMoney = rMoney - sMoney;
			eDMoney = rDMoney - sDMoney;
		}
		$('#orders_rs_money').html($.formatMoney(eMoney,'￥'));
		$('#orders_rsd_money').html($.formatMoney(eDMoney,'￥'));
		/*记录历史数据*/
		history.ordersMoney = eDMoney;
		/*合计总金额*/
		var erase = parseFloat($('#orders_emoney').numberbox('getValue'));
		isNaN(erase) || (eDMoney -= erase);
		$('#orders_pmoney').numberbox('setValue', eDMoney);
	}

    /**
     * 处理商品 Grid 中商品数量变化方法
     */
    function setGridGoodsNumber(rowIndex, row, value) {
		var oldValue = {number:history.goodsNumber,money:row.goods_tmoney,dmoney:row.goods_tdamoney};
        /*更新 Grid 记录*/
        var rowData = $.h.c.onBuilderGridFromNumber(row.goods_price, row.goods_daprice, value);
        $('#orders_grid_details').datagrid('updateRow', {index:rowIndex, row:rowData});
		/*更新合计数量、金额*/
		var newValue = {number:rowData.goods_number,money:rowData.goods_tmoney,dmoney:rowData.goods_tdamoney};
        calcTotal(row.goods_type, oldValue, newValue);
		/*设置为未保存*/
		history.isSave = false; 
		/*设置记录的商品数量值为空*/
		history.goodsNumber = undefined;
    }
    
    /**
     * 处理商品 Grid 中商品单价变化方法
     */
    function setGridGoodsPrice(rowIndex, row, value) {
        var oldValue = {number:0,money:row.goods_tmoney,dmoney:row.goods_tdamoney};
        /*更新 Grid 记录*/
        var rowData = $.h.c.onBuilderGridFromPrice(row.goods_discount, row.goods_number, value);
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*更新合计数量、金额*/
		var newValue = {number:0,money:rowData.goods_tmoney,dmoney:rowData.goods_tdamoney};
        calcTotal(row.goods_type, oldValue, newValue);
		/*设置为未保存*/
		history.isSave = false;
    }
    
    /**
     * 处理商品 Grid 中商品折扣变化方法
     */
    function setGridGoodsDiscount(rowIndex, row, value) {
        var oldValue = {number:0,money:0,dmoney:row.goods_tdamoney};
        /*更新 Grid 记录*/
        var rowData = $.h.c.onBuilderGridFromDiscount(row.goods_price, row.goods_number, value);
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*更新合计数量、金额*/
		var newValue = {number:0,money:0,dmoney:rowData.goods_tdamoney};
        calcTotal(row.goods_type, oldValue, newValue);
		/*设置为未保存*/
		history.isSave = false;
    }
    
    /**
     * 处理商品 Grid 中商品折扣价变化方法
     */
    function setGridGoodsDiscountAfterPrice(rowIndex, row, value) {
        var oldValue = {number:0,money:0,dmoney:row.goods_tdamoney};
        /*更新 Grid 记录*/
        var rowData = $.h.c.onBuilderGridFromDiscountAfterPrice(row.goods_price, row.goods_number, value);
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*更新合计数量、金额*/
		var newValue = {number:0,money:0,dmoney:rowData.goods_tdamoney};
        calcTotal(row.goods_type, oldValue, newValue);
		/*设置为未保存*/
		history.isSave = false;
    }

    /**
	 * 处理商品 Grid 中商品重复
	 * @param idGrid            对象表格
	 * @param index             索引行
	 * @param rowData           行对象
	 * (商品货号、色码、尺码、商品数量、商品单价、商品折后价)
	 */
	function handleGridGoodsRepeat(idGrid, index, rowData) {
		var rows = $(idGrid).datagrid('getRows');
		var _exist = false;
		$.each(rows,function(i, r) {
			if (i != index && r.goods_code == rowData.goods_code && r.color_id == rowData.color_id && r.size_id == rowData.size_id && r.goods_type == rowData.goods_type) {
				$(idGrid).datagrid('updateRow', {
					index:i,
					row:{
						goods_number:parseInt(rowData.goods_number) + parseInt(r.goods_number),
						goods_tmoney:(parseInt(rowData.goods_number) + parseInt(r.goods_number)) * parseFloat(rowData.goods_price),
						goods_tdamoney:(parseInt(rowData.goods_number) + parseInt(r.goods_number)) * parseFloat(rowData.goods_daprice)
					}
				});
				if (index >= 0) {
					$(idGrid).datagrid('deleteRow',index);
				}
				_exist = true;
				return false;
			}
		});
		return _exist;
	}
})(jQuery);