/**
 * 销售退货申请函数
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
        csList:[]
    }

    /**
     * 主要的
     */
    $.h.sra = {
        /**
         * 加载
         */
        onLoad : function(e) {
			
        },
        /**
         * 搜索客户
         */
        searchClient : function() {
			var grid = $('#client_id');
			var keyword = $('#search_orders_client').textbox('getText');
			grid.combogrid('grid').datagrid('unselectAll');
			grid.combogrid('grid').datagrid('loadData',{total:0,rows:[]});
			grid.combogrid('grid').datagrid('reload',{keyword:keyword});
        },
        /**
         * 搜索商品
         */
        searchGoods : function(target) {
			var isValid = $('#orders_form').form('validate');
			if (!isValid) {
				parent.$.h.index.setOperateInfo({
					module:'销售退货申请',
					operate:'查询商品',
					content:'请完整填写单据信息',
					icon:'hr-warn'
				}, false);
				return;
			}

			var warehouseId = 0;
			if ($('#warehouse_id').length) {
				warehouseId = $('#warehouse_id').combobox('getValue');
			}

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
						}
					});
					var _tMoney = $('#orders_pmoney').numberbox('getValue');
					var _tNumber = $('#goods_number').numberbox('getValue');
					var _money = (isNaN(parseFloat(_tMoney)) ? 0 : parseFloat(_tMoney)) + (isNaN(parseFloat(data.total_money)) ? 0 : parseFloat(data.total_money));
					var _number = (isNaN(parseInt(_tNumber)) ? 0 : parseInt(_tNumber)) + (isNaN(parseInt(data.total_number)) ? 0 : parseInt(data.total_number));
					$('#orders_pmoney').numberbox('setValue', _money);
					$('#goods_number').numberbox('setValue', _number);
					//$.h.window.winColorSize.close();
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
         * 销售退货申请商品列表操作
         */
        mGrid : {
			/*单击单元格事件*/
			onClickCell : function(rowIndex, field, value, row) {
				var opts = $(this).datagrid('options');
				if (opts.endEditing.call(this)) {
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
                            }
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
			/*编辑完成之后执行事件*/
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
			/*加载之前执行事件*/
			onBeforeLoad : function(param) {
                $(this).datagrid('options').editFields = ['color_id','size_id','goods_number'];
				$(this).datagrid('bindKeyEvent');

				/* Enter 商品查询事件 */
				$('#search_keyword').combogrid('options').keyHandler.enter = function(e) {
					$.h.sra.searchGoods($(e.data.target));
				};
				
				/* 客户查询键盘事件 */
				$('#client_id').combogrid('options').keyHandler = null;
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
					href:$.toUrl('sale', 'sale_reject_apply_center'),
					onLoad : initOrdersData
				});
            }
		},
        /**
         * 保存销售退货申请（草稿）
         */
        mSaveRoughDraft : function(e) {
            mSaveOrders(
				'/web/sale_reject_apply/saveRejectApplyDraft',
				function(data) {
					data = $.parseJSON(data);
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
					parent.$.h.index.setOperateInfo({
						module:'销售退货申请',
						operate:'草稿',
						content:data.errmsg,
						icon:data.errcode == 0 ? 'icon-ok' : 'hr-error'
					}, true);
					$.messager.progress('close');
				}
			);
        },
        /**
		 * 保存销售退货申请（提交）
		 */
		mSaveFormally : function(e) {
            $.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(isConfirm) {
				if (isConfirm) {
					mSaveOrders(
						'/web/sale_reject_apply/saveRejectApplyFormally',
						function(data) {
							data = $.parseJSON(data);
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
                                /* 设置为已保存 */
                                history.isSave = true;
                                ordersController({
									orders:data.data.orders,
									details:data.data.orders.details
								});
							};
							parent.$.h.index.setOperateInfo({
								module:'销售退货申请',
								operate:'提交',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hr-error'
							}, true);
							$.messager.progress('close');
						}
					);
				}
			});
        },
		/**
		 * 删除销售退货申请
		 */
		mDelOrders : function(e) {
            if (history.ordersCode == "") {
				parent.$.h.index.setOperateInfo({
					module:'销售退货申请',
					operate:'删除商品',
					content:'销售退货申请单据编号错误~~',
					icon:'hjtr-error'
				}, false);
                return;
            }
            $.messager.confirm('提示信息', '您想要删除该单据吗？', function(isConfirm){
	            if (isConfirm) {
                    $.ajax({
                        url: '/web/sale_reject_apply/delRejectApply',
                        data: {
                            orders_code : history.ordersCode
                        },
                        type:'post',
                        cache:false,
                        dataType:'json',
                        beforeSend: function(xhr) { },
                        success: function(data) {
                            if (data.errcode == 0) {
                                var controller = $('#main_layout').layout('panel','center');
                                controller.panel({
                                    href:'/web/sale/sale_reject_apply_center',
                                    onLoad : function() { }
                                });
                                $('#orders_list').datagrid('reload');
                                /* 初始化单据 */
                                initOrdersData();
                            }
							parent.$.h.index.setOperateInfo({
								module:'销售退货申请',
								operate:'删除单据',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
							}, true);
                        },
                        complete: function() { }
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
			var commMoney = $('#orders_pmoney');
			var commNumber = $('#goods_number');
			var _tMoney = parseFloat(commMoney.numberbox('getValue'));
			var _tNumber = parseInt(commNumber.numberbox('getValue'));
			if (rows.length < 1) {
				parent.$.h.index.setOperateInfo({
					module:'销售退货申请',
					operate:'删除商品',
					content:'请勾选需要删除的行数据~~',
					icon:'hjtr-warn'
				}, false);
                return;
			}
			for (var i = rows.length - 1; i >= 0; i--) {  
				var index = target.datagrid('getRowIndex',rows[i]);  
				target.datagrid('deleteRow', index);
				_tMoney -= parseFloat(rows[i].goods_tmoney);
				_tNumber -= parseInt(rows[i].goods_number);
			}
			commMoney.numberbox('setValue', _tMoney);
			commNumber.numberbox('setValue', _tNumber);
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
		 * 销售退货申请完成页面
		 */
		complete : {
			onBeforeLoad : function(param) {
				var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
				_a.css('background','url(/assets/web/img/complete.png) no-repeat center center');
			}
		},
		modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    }

	/**
	 * 保存单据函数
	 * 1、地址
	 */
	function mSaveOrders(url, fun) {
		var grid = $('#orders_grid_details');
		grid.datagrid('options').endEditing.call(grid);
		/*验证商品表中是否存在数据*/
		if (grid.datagrid('getRows').length < 1) {
			parent.$.h.index.setOperateInfo({
				module:'销售退货申请',
				operate:'保存单据',
				content:'商品列表中没有任何数据~~',
				icon:'hjtr-warn'
			}, false);
			return false;
		}
		var form = $('#orders_form');
		var goods_number = $('#goods_number').numberbox('getValue');
		var orders_pmoney = $('#orders_pmoney').numberbox('getValue');
		var target = $(this);
		var rowsInsert = grid.datagrid('getChanges','inserted');
        if (rowsInsert.length > 0) {
            $.extend(true, rowsInsert, rowsInsert);
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
            $.extend(true, rowsUpdate, rowsUpdate);
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
            $.extend(true, rowsDelete, rowsDelete);
            rowsDelete.forEach(function(row,index) {
                delete rowsDelete[index].goods_serial;
                delete rowsDelete[index].goods_name;
                delete rowsDelete[index].goods_barcode;
                delete rowsDelete[index].color_name;
                delete rowsDelete[index].size_name;
				delete rowsDelete[index].goods_wprice;
            });
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
				/*验证表单所有对象*/
				isValid = form.form('validate');
				if (!isValid) {
					parent.$.h.index.setOperateInfo({
						module:'销售退货申请',
						operate:'保存单据',
						content:'数据验证失败~~',
						icon:'hjtr-warn'
					}, false);
					return false;
				}
				$.messager.progress({title:'保存数据',msg:'正在保存销售单据......'});
				return true;
			},
			success : fun
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
    }

    /**
	 * 双击单据列表行
	 */
	function onOrdersDblClickRow(index,row) {
		var ordersCode = row ? row.orders_code : index;
		$.ajax({
			url: '/web/sale_reject_apply/loadApplyDetails',
			data: {
				orders_code:ordersCode
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
					switch (data.data.orders_status) {
                        /*草稿*/
						case 0:
                            /* 单据ID */
                            history._id = data.data.orders_id;
                            /* 版本锁 */
                            history._lockVersion = data.data.lock_version;
                            /* 单据编号 */
                            history.ordersCode = data.data.orders_code;
							draftController({
								orders:data.data,
								details:data.data.details
							});
							break;
                        /*已转换为销售单*/
						case 9:
							ordersController({
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
            href:$.toUrl('sale', 'sale_reject_apply_center'),
            onLoad : function() {
				data.orders.orders_date = $.DT.UnixToDate(data.orders.orders_date, 0, false);
				$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.orders.goods_number);
                var form = $('#orders_form');
                form.form('load', data.orders);
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
            href:$.toUrl('sale', 'sale_reject_apply_center_complete'),
            onLoad : function() {
                $('#orders_code_view').html(data.orders.orders_code);
                $('#client_name').html(data.orders.client_name);
                $('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
				$('#orders_remark').html(data.orders.orders_remark);
				$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.orders.goods_number);
                $('#orders_grid_details').datagrid('loadData',data.details);
            }
        });
    }

	/**
     * 处理商品 Grid 中商品数量变化方法
     */
    function setGridGoodsNumber(rowIndex, row, value) {
        /*合计金额*/
        var _tMoney = $('#orders_pmoney').numberbox('getValue');
		/*合计数量*/
        var _tNumber = $('#goods_number').numberbox('getValue');
        /*合计金额 -= 折后金额*/
        _tMoney -= parseFloat(row.goods_tmoney);
		/*合计数量 -= 商品数量*/
        _tNumber -= parseInt(history.goodsNumber);
        /*更新 Grid 记录*/
		var rowData = {
			/*金额 = 数量 * 单价*/
			goods_tmoney:parseInt(value) * parseFloat(row.goods_price)
		};
        $('#orders_grid_details').datagrid('updateRow', {index:rowIndex, row:rowData});
		/*总金额 = 原始总金额 + 折后金额*/
        $('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tmoney);
		/*总数量 = 原始总数量 + 商品数量*/
        $('#goods_number').numberbox('setValue', _tNumber + parseInt(value));
		/*设置为未保存*/
		history.isSave = false;
		/*设置记录的商品数量值为空*/
		history.goodsNumber = undefined;
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
						goods_tmoney:(parseInt(rowData.goods_number) + parseInt(r.goods_number)) * parseFloat(rowData.goods_price)
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