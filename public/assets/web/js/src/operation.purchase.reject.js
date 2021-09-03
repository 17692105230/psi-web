/**
 * 采购退货单函数
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
		orders:undefined,
        csList:[]
	};
    /**
     * 主要的
     */
    $.h.pr = {
        /**
         * 页面加载
         */
        onLoad : function(e) { },
        /**
         * 搜索商品
         */
        searchGoods : function(target) {
			var isValid = $('#orders_form').form('validate');
			if (!isValid) {
				parent.$.h.index.setOperateInfo({
					module:'采购退货单',
					operate:'查询商品',
					content:'请完整填写单据信息',
					icon:'hr-warn'
				}, false);
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
						if (!handleGridGoodsRepeat('#orders_grid_details', -1, row)) {
							$('#orders_grid_details').datagrid('insertRow',{index:0,row:row});
							$.h.pr.ordersDataCacheGrid();
						}
					});
					var _tMoney = $('#orders_pmoney').numberbox('getValue');
					var _tNumber = $('#goods_number').numberbox('getValue');
					var _money = (isNaN(parseFloat(_tMoney)) ? 0 : parseFloat(_tMoney)) + (isNaN(parseFloat(data.total_dmoney)) ? 0 : parseFloat(data.total_dmoney));
					var _number = (isNaN(parseInt(_tNumber)) ? 0 : parseInt(_tNumber)) + (isNaN(parseInt(data.total_number)) ? 0 : parseInt(data.total_number));
					$('#orders_pmoney').numberbox('setValue', _money);
					$('#goods_number').numberbox('setValue', _number);
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
		 * 查询单据列表
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
		 * 采购退货单，当页面加载时请求接口，加载数据
		 */
		onLoadOrderCodeReject : function(){
			var url = '/web/purchase/loadOrdersCodeReject';
			if (history.ordersStatus < 0 || history.ordersStatus == 7){
				$.get(url,function (data) {
					$("#orders_code_view").text(data.orders_code);
					var storage = JSON.parse(window.localStorage.getItem(data.orders_code+'_data'));
					if (storage){
						$("#supplier_id").combogrid('setValue',storage.supplier_id);
						$("#supplier_id").combogrid('setText',storage.supplier_name);
						$("#warehouse_id").combobox('setValue',storage.warehouse_id);
						$("#warehouse_id").combobox('setText',storage.warehouse_name);
						$("#settlement_id").combobox('setValue',storage.settlement_id);
						$("#settlement_id").combobox('setText',storage.settlement_name);
						var orders_date = $.DT.UnixToDate(storage.orders_date, 0, true);
						$("#return_orders_date").datebox('setValue',orders_date);
						$("#orders_rmoney").numberspinner('setValue',storage.orders_rmoney);
						$("#orders_remark").textbox('setValue',storage.reject_remark);
						$("#goods_number").numberbox('setValue',storage.goods_number);
						$("#orders_pmoney").numberbox('setValue',storage.orders_pmoney);
					}
					var storage_grid = JSON.parse(window.localStorage.getItem(data.orders_code+'_grid'));
					if (storage_grid){
						$("#orders_grid_details").datagrid('loadData',storage_grid.data_insert);
					}
					history.ordersStatus = data.reject_status;
				});
			}
		},
		/**
		 * 缓存当前页面数据
		 */
		onLoadDataCache:function(){
			if (history.ordersStatus == 7) {
				//单据编号
				var orders_code = $("#orders_code_view").html();
				//供应商
				var supplier_id = $("#supplier_id").combogrid('getValue');
				var supplier_name = $('#supplier_id').combogrid('getText');
				//仓库
				var warehouse_id = $("#warehouse_id").combobox('getValue');
				var warehouse_name = $("#warehouse_id").combobox('getText');
				//结算账户
				var settlement_id = $("#settlement_id").combobox('getValue');
				var settlement_name = $("#settlement_id").combobox('getText');
				//日期
				var orders_date = $("#return_orders_date").datebox('getValue');
				//实付金额
				var orders_rmoney = $("#orders_rmoney").numberspinner('getValue');
				//备注
				var reject_remark = $("#orders_remark").textbox('getValue');
				//合计数量
				var goods_number = $("#goods_number").numberbox('getValue');
				//合计金额
				var orders_pmoney = $("#orders_pmoney").numberbox('getValue');
				var array = {
					supplier_id:supplier_id,
					supplier_name:supplier_name,
					warehouse_id:warehouse_id,
					warehouse_name:warehouse_name,
					settlement_id:settlement_id,
					settlement_name:settlement_name,
					orders_date:orders_date,
					orders_rmoney:orders_rmoney,
					reject_remark:reject_remark,
					goods_number:goods_number,
					orders_pmoney:orders_pmoney
				};
				window.localStorage.setItem(orders_code+'_data',JSON.stringify(array));
			}
		},
		/**
		 * 缓存datagrid数据
		 */
		ordersDataCacheGrid : function(){
			if (history.ordersStatus == 7) {
				var orders_code = $("#orders_code_view").html();
				var grid = $("#orders_grid_details").datagrid('getRows');
				var array = {
					data_insert: grid
				};
				window.localStorage.setItem(orders_code+'_grid',JSON.stringify(array));
			}
		},
        /**
         * 采购退货单商品列表操作
         */
        mGrid : {
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
			 * 点击单元格事件
			 */
			onClickCell : function(rowIndex, field, value, row) {
				var opts = $(this).datagrid('options');
				if (opts.endEditing.call(this)) {
					for(var i = 0, n = opts.editFields.length; i < n; i++) {
						if (field == opts.editFields[i]) {
                            $(this).datagrid('editCell', {index:rowIndex,field:field});
                            opts.editIndex = rowIndex;
							opts.editFieldIndex = i;
                            /* var row = opts.finder.getRow(this, rowIndex); */
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
									mPrivateMenu.menu('appendItem', {value:row.goods_pprice, text: '采购价:' + $.formatMoney(row.goods_pprice,'￥'),iconCls:'hjtr-money'});
									break;
                            }
                            break;
						}
					}
				}
			},
			/**
			 * 完成编辑一行时触发
			 */
			onAfterEdit : function(index, row, changes) {
				if (!$.isEmptyObject(changes)) {
                    var arr = new Array();
                    var name,value;
                    for(key in changes) {
                        name = key;
                        value = changes[key];
                    }
                    switch (name) {
                        case 'goods_number':/*数量*/
                            setGridGoodsNumber(index, row, value);
                            $.h.pr.ordersDataCacheGrid();
                            break;
                        case 'goods_price':/*单价*/
                            setGridGoodsPrice(index, row, value);
							$.h.pr.ordersDataCacheGrid();
                            break;
						case 'goods_discount':/*折扣*/
                            setGridGoodsDiscount(index, row, value);
							$.h.pr.ordersDataCacheGrid();
                            break;
                        case 'goods_daprice':/*折扣价*/
                            setGridGoodsDiscountAfterPrice(index, row, value);
							$.h.pr.ordersDataCacheGrid();
                            break;
						case 'color_id':
							row.color_id = value;
							handleGridGoodsRepeat('#orders_grid_details', index, row);
							$.h.pr.ordersDataCacheGrid();
							break;
						case 'size_id':
							row.size_id = value;
							handleGridGoodsRepeat('#orders_grid_details', index, row);
							$.h.pr.ordersDataCacheGrid();
							break;
                    }
                }
			},
			/**
			 * 在载入请求数据数据之前触发，如果返回false可终止载入数据操作。
			 */
			onBeforeLoad : function(param) {
                $(this).datagrid('options').editFields = ['color_id','size_id','goods_number','goods_price','goods_discount','goods_daprice'];
				$(this).datagrid('bindKeyEvent');

				/* Enter 商品查询事件 */
				$('#search_keyword').combogrid('options').keyHandler.enter = function(e) {
					$.h.pr.searchGoods($(e.data.target));
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
               // href:'/manage/purchase/purchase_reject_center',
                $('#main_layout').layout('panel','center').panel({
					href:$.toUrl('purchase', 'purchase_reject_center'),
					onLoad : initOrdersData
				});
            }
		},
		/**
		 * 复制为新单
		 */
		mCopyToNewOrders : function(e) {
			/* 初始化单据 */
			initOrdersData();
			var data = history.orders;
			/* 移除单据编号 */
			delete data.data.orders.orders_code;
			/* 移除单据ID */
			delete data.data.orders.orders_id;
			/* 移除创建时间 */
			delete data.data.orders.create_time;
			/* 移除更新时间 */
			delete data.data.orders.update_time;
			/* 更新单据日期 */
			data.data.orders.orders_date = $.DT.DateToUnix(new Date().Format("yyyy-MM-dd"), 0, true);
			/* 删除制单员信息 */
			// delete data.data.orders.user_id;
			// delete data.data.orders.user_name;
			/* 删除状态 */
			delete data.data.orders.orders_status;
			/* 删除版本号信息 */
			delete data.data.orders.lock_version;
			/* 删除商品多余字段 */
			data.data.orders.details.forEach(function(row,index) {
                delete data.data.orders.details[index].create_time;
				delete data.data.orders.details[index].update_time;
                delete data.data.orders.details[index].details_id;
                // delete data.details[index].goods_status;
                delete data.data.orders.details[index].lock_version;
                delete data.data.orders.details[index].orders_code;
            });

			data.href = $.toUrl('purchase','purchase_reject_center');
			/* 色码、尺码字典数据 */
			history.csList = data.list;
			copyControllerCaoGao(data);
		},
        /**
         * 保存采购退货单（草稿）
         */
        mSaveRoughDraft : function(e) {
			mSaveOrders(
				'/web/purchase/saveRejectOrderRoughDraft?reject_status=0',
				function(data) {
					if (data.errcode == 0) {
						window.localStorage.removeItem(history.ordersCode+'_grid');
						window.localStorage.removeItem(history.ordersCode+'_data');
                        /* 单据ID */
                        history._id = data.data.orders.orders_id;
                        /* 版本锁 */
                        history._lockVersion = data.data.orders.lock_version;
                        /* 单据编号 */
                        history.ordersCode = data.data.orders.orders_code;
						/* 合计数量 */
						$('#goods_number').numberbox('setValue', data.data.orders.goods_number);
						/* 合计金额 */
						$('#orders_pmoney').numberbox('setValue', data.data.orders.orders_pmoney);

						$('#orders_code_view').text(data.data.orders.orders_code);
						// $('#orders_grid_details').datagrid('acceptChanges');
                        $('#orders_grid_details').datagrid('loadData',data.data.orders.details);
						$('#orders_list').datagrid('reload');
						/*设置为已保存*/
						history.isSave = true;
					}
                    parent.$.h.index.setOperateInfo({
                        module:'采购退货单',
                        operate:'草稿',
                        content:data.errmsg,
                        icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
                    }, true);
					$.messager.progress('close');
				},'草稿'
			);
        },
		/**
		 * 保存采购退货单（完成）
		 */
		mSaveFormally : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(isConfirm) {
				if (isConfirm) {
					mSaveOrders(
						'/web/purchase/saveRejectOrderRoughDraft?reject_status=9',
						function(data) {
							if (data.errcode == 0) {
								window.localStorage.removeItem(history.ordersCode+'_grid');
								window.localStorage.removeItem(history.ordersCode+'_data');
								$('#orders_list').datagrid('reload');
								/* 初始化单据 */
								initOrdersData();
								/* 单据ID */
								history._id = data.data.orders.orders_id;
								/* 版本锁 */
								history._lockVersion = data.data.orders.lock_version;
								/* 单据编号 */
								history.ordersCode = data.data.orders.orders_code;
								/*设置为已保存*/
								history.isSave = true;
								data.data.href = '/web/purchase/purchase_reject_center_complete';
								ordersController(data.data);
							}
						},'提交'
					);
				}
			});
		},
		/**
		 * 保存采购退货单（撤销）
		 */
		mSaveRevoke : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(_isRun) {
				if (_isRun) {
					$.ajax({
                        url:'/web/purchase/purchaseRejectRevoke',
						data: {
							orders_code:history.ordersCode,
							lock_version:history._lockVersion
						},
						type:'post',
						cache:false,
						dataType:'json',
						beforeSend: function(xhr) {
							$.messager.progress({title:'请稍等',msg:'正在提交数据...'});
						},
						success: function(data) {
							$('#orders_list').datagrid('reload');
							if (data.errcode == 0) {
								data.href = '/web/purchase/purchase_reject_center_revoke';
								revokeController(data);
							}
							parent.$.h.index.setOperateInfo({
								module:'采购退货单',
								operate:'撤销',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
							}, true);
						},
						complete: function() {
							$.messager.progress('close');
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
			var ordersMoney = $('#orders_pmoney');
			var ordersNumber = $('#goods_number');
			var _tMoney = parseFloat(ordersMoney.numberbox('getValue'));
			var _tNumber = parseInt(ordersNumber.numberbox('getValue'));
			if (rows.length < 1) {
				parent.$.h.index.setOperateInfo({
					module:'采购退货单',
					operate:'删除商品',
					content:'请勾选需要删除的行数据~~',
					icon:'hjtr-warn'
				}, false);
                return;
			}
			for (var i = rows.length - 1; i >= 0; i--) {  
				var index = target.datagrid('getRowIndex',rows[i]);  
				target.datagrid('deleteRow', index);
				_tMoney -= parseFloat(rows[i].goods_tdamoney);
				_tNumber -= parseInt(rows[i].goods_number);
			}
			$.h.pr.ordersDataCacheGrid();
			ordersMoney.numberbox('setValue', _tMoney);
			ordersNumber.numberbox('setValue', _tNumber);
			/*设置为未保存*/
			history.isSave = false;
		},
		/**
		 * 删除采购退货单
		 */
		mDelOrders : function(e) {
            if (!history.ordersCode) {
				parent.$.h.index.setOperateInfo({
					module:'采购退货单',
					operate:'删除单据',
					content:'采购单单据编号错误~~',
					icon:'hjtr-warn'
				}, false);
                return;
            }
            $.messager.confirm('提示信息', '您想要删除该单据吗？', function(isAjax){
	            if (isAjax) {
                    $.ajax({
						url: '/web/purchase/delRejectOrders',
                        data: {
                            orders_code : history.ordersCode
                        },
                        type:'post',
                        cache:false,
                        dataType:'json',
                        beforeSend: function(xhr) {
                            $.messager.progress({title:'Please waiting',msg:'Submit data...'});
                        },
                        success: function(data) {
                            if (data.errcode == 0) {
                                var controller = $('#main_layout').layout('panel','center');
                                controller.panel({
                                    href: $.toUrl('purchase', 'purchase_reject_center'),
                                    onLoad : initOrdersData
                                });
                                $('#orders_list').datagrid('reload');
                            }
							parent.$.h.index.setOperateInfo({
								module:'采购退货单',
								operate:'删除单据',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
							}, true);
                        },
                        complete: function() {
	                            $.messager.progress('close');
                        }
                    });
                }
            });
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
		 * 实际金额金额改变事件
		 */
		onGoodsMoneyChange : function(newValue, oldValue) {
			$('#orders_rmoney').numberbox('setValue',newValue);
			$.h.pr.onLoadDataCache();
		},
        /**
		 * 采购退货单完成页面
		 */
		complete : {
			onBeforeLoad : function(param) {
				var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
				_a.css('background','url(/assets/web/img/complete.png) no-repeat center center');
			}
		},
		/**
		 * 采购退货单撤销页面
		 */
		reject : {
			onBeforeLoad : function(param) {
				var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
				_a.css('background','url(/assets/web/img/reject.png) no-repeat center center');
			}
		},
		modifyFormatter : function(value, row, index) {
			return row.orders_status == 1 ?
				'<a href="javascript:void(0);" class="hjtr-ico-view" style="display:inline-block;width:16px;height:16px;"></a>' :
				'<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    }
	/**
	 * 保存单据函数
     * @param url 地址
     * @param fun 回调函数
	 */
	function mSaveOrders(url, _fun, txt) {
		var grid = $('#orders_grid_details');
		var orders_code = $("#orders_code_view").html();
		history.ordersCode = orders_code;
		grid.datagrid('options').endEditing.call(grid);
		/*验证商品表中是否存在数据*/
		if (grid.datagrid('getData').total < 1) {
			parent.$.h.index.setOperateInfo({
				module:'采购退货单',
				operate:txt,
				content:'商品列表中没有任何数据~~',
				icon:'hjtr-warn'
			}, false);
			return false;
		}
		var form = $('#orders_form');
		var goods_number = $('#goods_number').numberbox('getValue');
		var orders_pmoney = $('#orders_pmoney').numberbox('getValue');

		if (history.ordersStatus == 7 || history.ordersStatus < 0) {
			var rows = grid.datagrid('getRows');
			if (rows.length > 0){
				rowsInsert = $.extend(true,[],rows);
				rowsInsert.forEach(function (value,index) {
					delete rowsInsert[index].goods_serial;
					delete rowsInsert[index].goods_name;
					delete rowsInsert[index].goods_barcode;
					delete rowsInsert[index].color_name;
					delete rowsInsert[index].size_name;
					delete rowsInsert[index].goods_pprice;
				})
			}
			rowsDelete = [];
			rowsUpdate = [];
		}else{
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
				delete rowsInsert[index].goods_pprice;
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
				delete rowsUpdate[index].goods_pprice;
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
				delete rowsDelete[index].goods_pprice;
            });
        }
		}
		form.form('submit', {
			url: url,
			queryParams:{
				/* 合计数量 */
				goods_number:goods_number,
				/* 合计金额 */
				orders_pmoney:orders_pmoney,
                /* 版本锁 */
                lock_version:history._lockVersion,
                /* 单据编号 */
                orders_code:history.ordersCode,
				/* 新增商品数据 */
				data_insert:JSON.stringify(rowsInsert),
				/* 更新商品数据 */
				data_update:JSON.stringify(rowsUpdate),
				/* 删除商品数据 */
				data_delete:JSON.stringify(rowsDelete)
			},
			onSubmit : function() {
                /* 验证表单所有对象 */
                isValid = $('#orders_form').form('validate');

                if (!isValid) {
					parent.$.h.index.setOperateInfo({
						module:'采购退货单',
						operate:txt,
						content:'数据验证失败~~',
						icon:'hjtr-warn'
					}, false);
                    return false;
                }
				$.messager.progress({title:'请稍候',msg:'正在保存销售单据......'});
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
						/* 因为已经更新数据，所以需要重新获取版本锁 */
                        history._lockVersion = data.orders.lock_version;
						/* 弹出库存不足的商品信息 */
                        $.h.window.winCheckGoods.open(function() {
                            /* 加载数据 */
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
					module:'采购退货单',
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
        history.ordersStatus = -1;
		$.h.pr.onLoadOrderCodeReject();
    }

	/**
	 * 双击
	 */
	function onOrdersDblClickRow(index,row) {
		$.ajax({
			url: '/web/purchase/loadOrdersRejectDetails',
			data: {
				orders_code:row.orders_code
			},
			type:'post',
			cache:false,
			dataType:'json',
			beforeSend: function(xhr) {
				$.messager.progress({title:'请稍等',msg:'正在处理数据中...'});
			},
			success: function(data) {
				if (data.errcode == 0) {
					console.log(data);
					/* 单据ID */
					history._id = data.data.orders.orders_id;
					/* 版本锁 */
					history._lockVersion = data.data.orders.lock_version;
					/* 单据编号 */
					history.ordersCode = data.data.orders.orders_code;
					history.ordersStatus = data.data.orders.reject_status;
					switch (data.data.orders.reject_status) {
						case 0:/* 草稿 */
							data.data.href = $.toUrl('purchase', 'purchase_reject_center');
							draftController(data.data);
							break;
						case 9:/*完成*/
							/* 备份数据 (复制为新单据使用) */
							history.orders = data;
							data.data.href = $.toUrl('purchase', 'purchase_reject_center_complete');
							ordersController(data.data);
							break;
						case 1:/*撤销*/
							/* 备份数据 (复制为新单据使用) */
							history.orders = data.data;
							data.data.href = $.toUrl('purchase', 'purchase_reject_center_revoke');
							revokeController(data.data);
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
     * 草稿页面
     */
    function draftController(data) {
        $('#main_layout').layout('panel','center').panel({
            href:data.href,
            onLoad : function() {
				data.orders.orders_date = $.DT.UnixToDate(data.orders.orders_date, 0, false);
				data.orders.settlement_id == 0 && delete data.orders.settlement_id;
                $('#orders_form').form('load', data.orders);
                $('#supplier_id').combogrid('setText',data.orders.supplier_name);
                $('#settlement_id').combobox('setText',data.orders.settlement_name);
				$('#orders_code_view').text(data.orders.orders_code);
				$('#orders_pmoney').numberbox('initValue',data.orders.orders_pmoney).numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('initValue',data.orders.goods_number).numberbox('setValue',data.orders.goods_number);
				$('#orders_grid_details').datagrid('loadData',data.orders.details);
            }
        });
    }
    /**
     * 完成页面
     */
    function ordersController(data) {
        var controller = $('#main_layout').layout('panel','center');
        controller.panel({
            href:data.href,
            onLoad : function() {
				$('#orders_code_view').text(data.orders.orders_code);
				$('#supplier_name').html(data.orders.supplier_name);
				$('#warehouse_name').html(data.orders.org_name);
				$('#settlement_name').html(data.orders.settlement_name);
				$('#orders_rmoney').html(data.orders.orders_rmoney);
				$('#orders_remark').html(data.orders.reject_remark);
				$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.orders.goods_number);
                $('#orders_grid_details').datagrid('loadData',data.orders.details);
			}
        });
    }
    /**
     * 撤销页面
     */
    function revokeController(data) {
        var controller = $('#main_layout').layout('panel','center');
        controller.panel({
            href:data.href,
            onLoad : function() {
                $('#orders_code_view').text(data.data.orders.orders_code);
				$('#supplier_name').html(data.data.orders.supplier_name);
				$('#warehouse_name').html(data.data.orders.warehouse_name);
				$('#settlement_name').html(data.data.orders.settlement_name);
				$('#orders_rmoney').html(data.data.orders.orders_rmoney);
				$('#orders_remark').html(data.data.orders.reject_remark);
				$('#orders_pmoney').numberbox('setValue',data.data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.data.orders.goods_number);
                $('#orders_grid_details').datagrid('loadData',data.data.orders.details);
            }
        });
    }



	/**
	 * 复制页面
	 */
	function copyController(data) {
		$('#main_layout').layout('panel','center').panel({
            href:data.href,
            onLoad : function() {
				data.orders.orders_date = $.DT.UnixToDate(data.orders.orders_date, 0, true);
				data.orders.settlement_id == 0 && delete data.orders.settlement_id;
                var form = $('#orders_form');
                form.form('load', data.orders);
				$('#orders_code_view').text(data.orders.orders_code);
				$('#supplier_id').combogrid('setValue',data.orders.supplier_id);
                $('#supplier_id').combogrid('setText',data.orders.supplier_name);
				$('#warehouse_id').combobox('setValue',data.orders.warehouse_id);
                $('#warehouse_id').combobox('setText',data.orders.org_name);
				$('#settlement_id').combobox('setValue',data.orders.settlement_id);
				$('#settlement_id').combobox('setText',data.orders.settlement_name);
				$('#orders_pmoney').numberbox('initValue',data.orders.orders_pmoney).numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('initValue',data.orders.goods_number).numberbox('setValue',data.orders.goods_number);
				data.orders.details.forEach(function(rowData,rowIndex) {
					$('#orders_grid_details').datagrid('insertRow',{
						index: rowIndex,
						row: rowData
					});
				});
                //$('#orders_grid_details').datagrid('loadData',data.details);
            }
        });
	};

	/**
	 *  复制为新单，直接生成为草稿
	 * @param data
	 */
	function copyControllerCaoGao(data){
		// console.log(data);
		$("#orders_form").form('submit',{
			url : '/web/purchase/saveRejectOrderRoughDraft?reject_status=0',
			queryParams:{
				/* 供应商 */
				supplier_id:data.data.orders.supplier_id,
				/* 结算账户 */
				settlement_id:data.data.orders.settlement_id,
				/* 仓库ID */
				warehouse_id:data.data.orders.warehouse_id,
				/* 合计数量 */
				goods_number:data.data.orders.goods_number,
				/* 合计金额 */
				orders_pmoney:data.data.orders.orders_pmoney,
				/* 版本锁 */
				lock_version:history._lockVersion,
				/* 备注 */
				reject_remark:data.data.orders.reject_remark,
				/* 实付金额 */
				orders_rmoney: data.data.orders.orders_rmoney,
				/*新增商品数据*/
				data_insert:JSON.stringify(data.data.orders.details),
				/*新增商品数据*/
				data_update:JSON.stringify([]),
				/*新增商品数据*/
				data_delete:JSON.stringify([]),
				/* 订单日期 */
				orders_date:data.data.orders.orders_date
			},
			onSubmit :function () {
				$.messager.progress({title:'请稍候',msg:'正在保存单据......'});
				return true;
			},
			success:function (res) {
				res = JSON.parse(res);
				if (res.errcode == 0){
					$.messager.progress('close');
					history.ordersStatus = res.data.orders_status;
					res.data.href = data.href;
					copyController(res.data);
				}
			}
		});
	};
	/**
     * 处理商品 Grid 中商品数量变化方法
     */
    function setGridGoodsNumber(rowIndex, row, value) {
        /*合计金额*/
        var _tMoney = $('#orders_pmoney').numberbox('getValue');
		/*合计数量*/
        var _tNumber = $('#goods_number').numberbox('getValue');
        /*合计金额 -= 折后金额*/
        _tMoney -= parseFloat(row.goods_tdamoney);
		/*合计数量 -= 商品数量*/
        _tNumber -= parseInt(history.goodsNumber);
        /*更新 Grid 记录*/
		var rowData = $.h.c.onBuilderGridFromNumber(row.goods_price, row.goods_daprice, value);
        
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
        $('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*总数量 = 原始总数量 + 商品数量*/
        $('#goods_number').numberbox('setValue', _tNumber + parseInt(value));
		
        /*设置为未保存*/
		history.isSave = false;
		/*设置记录的商品数量值为空*/
		history.goodsNumber = undefined;
    }
	/**
     * 处理商品 Grid 中商品单价变化方法
     */
    function setGridGoodsPrice(rowIndex, row, value) {
        /*合计金额*/
        var _tMoney = $('#orders_pmoney').numberbox('getValue');
        /*合计金额 -= 折后金额*/
        _tMoney -= parseFloat(row.goods_tdamoney);
        /*更新 Grid 记录*/
        var rowData = {
			/*折后价 = 单价 × （折扣 ÷ 100） */
			goods_daprice:parseFloat(value) * (parseInt(row.goods_discount) / 100),
			/*金额 = 单价 × 数量 */
			goods_tmoney:parseFloat(value) * parseInt(row.goods_number),
			/*折后金额 = 单价 × （折扣 ÷ 100） × 数量*/
			goods_tdamoney:parseFloat(value) * (parseInt(row.goods_discount) / 100) * parseInt(row.goods_number)
        };
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
		$('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*设置为未保存*/
		history.isSave = false;
    }
	/**
     * 处理商品 Grid 中商品折扣变化方法
     */
    function setGridGoodsDiscount(rowIndex, row, value) {
        /*合计金额*/
        var _tMoney = $('#orders_pmoney').numberbox('getValue');
        /*合计金额 -= 折后金额*/
        _tMoney -= parseFloat(row.goods_tdamoney);
        /*更新 Grid 记录*/
        var rowData = {
			/*折扣价 = 单价 × （折扣 ÷ 100）*/
			goods_daprice:parseFloat(row.goods_price) * (parseInt(value) / 100),
			/*折后金额 = 单价 × （折扣 ÷ 100） × 数量*/
            goods_tdamoney:parseFloat(row.goods_price) * (parseInt(value) / 100) * parseInt(row.goods_number)
        };
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
		$('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*设置为未保存*/
		history.isSave = false;
    }
	/**
     * 处理商品 Grid 中商品折扣价变化方法
     */
    function setGridGoodsDiscountAfterPrice(rowIndex, row, value) {
        /*合计金额*/
        var _tMoney = $('#orders_pmoney').numberbox('getValue');
        /*合计金额 -= 折后金额*/
        _tMoney -= parseFloat(row.goods_tdamoney);
        /*更新 Grid 记录*/
        var rowData = {
			/*折扣 = 折后价 ÷ （单价 × 100）*/
			goods_discount:parseInt(parseFloat(value) / parseFloat(row.goods_price) * 100),
			/*折后金额 = 折后价 × 数量*/
            goods_tdamoney:parseFloat(value) * parseInt(row.goods_number)
        };
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
		$('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
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
			if (i != index && r.goods_code == rowData.goods_code && r.color_id == rowData.color_id && r.size_id == rowData.size_id) {
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