/**
 * 调拨单函数
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
    $.h.st = {
        /**
         * 主加载
         */
        mLoad : function(e) { },
        /**
         * 搜索客户
         */
        searchCustomerEnter : function() {
			var keyword = $(this).textbox('getText');
			if ($.trim(keyword) == '')  {
				return;
			}
			var grid = $('#client_id');
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
					module:'库存调拨单',
					operate:'查询商品',
					content:'请完整填写单据信息~~',
					icon:'hjtr-warn'
				}, false);
				return;
			}
			var outWarehouseId = $('#out_warehouse_id').combobox('getValue');
			var inWarehouseId = $('#in_warehouse_id').combobox('getValue');
			var warehoust = {
				out:outWarehouseId,
				in:inWarehouseId
			};
			$.h.s.goods.onSearchGoods({
				/*对象*/
				target:target,
				/*仓库ID*/
				warehouseId:JSON.stringify(warehoust),
				/*默认折扣*/
				goodsDiscount:90,
				/*回调函数*/
				afterFun:function(data) {
					/*设置为未保存*/
					history.isSave = false;
					var tagGrid = $('#orders_grid_details');
					data.rows.forEach(function(row) {
						if (!handleGridGoodsRepeat(tagGrid, -1, row)) {
							tagGrid.datagrid('insertRow',{index:0,row:row});
						}
					});
					$.h.window.winColorSize.close();
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
         * 库存调拨单商品列表操作
         */
        mGrid : {
			/**
			 * 单击单元格事件
			 */
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
						case 'goods_number':/*数量*/
                            setGridGoodsNumber(index, row, value);
                            break;
						case 'color_id':
                            row.color_id = value;
							handleGridGoodsRepeat($('#orders_grid_details'), index, row);
							break;
						case 'size_id':
                            row.size_id = value;
							handleGridGoodsRepeat($('#orders_grid_details'), index, row);
							break;
                    }
                }
			},
			/**
			 * 加载之前执行事件
			 */
			onBeforeLoad : function(param) {
                /*可编辑字段*/
                $(this).datagrid('options').editFields = ['color_id','size_id','goods_number'];
                /*绑定 Grid 事件*/
				$(this).datagrid('bindKeyEvent');

				/* Enter 商品查询事件 */
				$('#search_keyword').combogrid('options').keyHandler.enter = function(e) {
					$.h.st.searchGoods($(e.data.target));
				};
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
					href:'/web/store/store_transfer_orders_center',
					onLoad : initOrdersData
				});
            }
		},
        /**
         * 保存库存调拨单（草稿）
         */
        mSaveRoughDraft : function(e) {
            mSaveOrders(
				'/web/store/saveTransferRoughDraft',
				function(data) {
					if (data.errcode == 0) {
                        /* 单据ID */
                        history._id = data.data.orders_id;
                        /* 版本锁 */
                        history._lockVersion = data.data.lock_version;
                        /* 单据编号 */
                        history.ordersCode = data.data.orders_code;

						$('#orders_code_view').text(data.data.orders_code);
						//$('#orders_grid_details').datagrid('acceptChanges');
						$('#orders_grid_details').datagrid('loadData',data.details);
						$('#orders_list').datagrid('reload');
						/*设置为已保存*/
						history.isSave = true;
					}
				},'草稿'
			);
        },
        /**
		 * 保存库存调拨单（提交）
		 */
		mSaveFormally : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(isConfirm) {
				if (isConfirm) {
					mSaveOrders(
						'/web/store/saveTransferFormally',
						function(data) {
							if (data.errcode == 0) {
								$('#orders_list').datagrid('reload');
								/*设置为已保存*/
								history.isSave = true;
								data.href = '/web/store/store_transfer_orders_center_complete';
								ordersController(data);
								/* 初始化单据 */
								initOrdersData();
							}
						},'提交'
					);
				}
			});
        },
		/**
		 * 删除库存调拨单
		 */
		mDelOrders : function(e) {
            if (history.ordersCode == "") {
				parent.$.h.index.setOperateInfo({
					module:'库存调拨单',
					operate:'删除单据',
					content:'库存调拨单单据编号错误~~',
					icon:'hjtr-warn'
				}, false);
                return;
            }
            $.messager.confirm('提示信息', '您想要删除该单据吗？', function(isConfirm){
	            if (isConfirm) {
                    $.ajax({
                        url: '/web/store/delTransferOrders',
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
                                    href:'/web/store/store_transfer_orders_center',
                                    onLoad : function() { }
                                });
                                $('#orders_list').datagrid('reload');
                                /* 初始化单据 */
                                initOrdersData();
                            }
							parent.$.h.index.setOperateInfo({
								module:'库存调拨单',
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
			var goodsNumber = $('#goods_number');
			var _tNumber = parseInt(goodsNumber.numberbox('getValue'));
			if (rows.length < 1) {
				parent.$.h.index.setOperateInfo({
					module:'库存调拨单',
					operate:'删除商品',
					content:'请勾选需要删除的行数据~~',
					icon:'hr-warn'
				}, false);
                return;
			}
			for (var i = rows.length - 1; i >= 0; i--) {
				var index = target.datagrid('getRowIndex',rows[i]);  
				target.datagrid('deleteRow', index);
				_tNumber -= parseInt(rows[i].goods_number);
			}
			goodsNumber.numberbox('setValue', _tNumber);
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
				_a.css('background','url(/static/manage/images/reject.png) no-repeat center center');
			}
		},
		modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    }

	/**
	 * 保存单据函数
     * @param url 地址
     * @param fun 回调函数
	 */
	function mSaveOrders(url, _fun, txt) {
		/* 验证商品表中是否存在数据 */
		if ($('#orders_grid_details').datagrid('getRows').length < 1) {
			parent.$.h.index.setOperateInfo({
				module:'库存调拨单',
				operate:txt,
				content:'商品列表中没有任何数据~~',
				icon:'hr-warn'
			}, false);
			return false;
		}
		/* 调出仓库 == 调入仓库 */
		if ($('#out_warehouse_id').combobox('getValue') == $('#in_warehouse_id').combobox('getValue')) {
			parent.$.h.index.setOperateInfo({
				module:'库存调拨单',
				operate:txt,
				content:'调出仓库与调入仓库不能相同~~',
				icon:'hr-warn'
			}, false);
			return false;
		}
		var form = $('#orders_form');
		var goods_number = $('#goods_number').numberbox('getValue');
		var rowsInsert = $('#orders_grid_details').datagrid('getChanges','inserted');
        if (rowsInsert.length > 0) {
            rowsInsert = $.extend(true, [], rowsInsert);
            rowsInsert.forEach(function(row,index) {
                delete rowsInsert[index].goods_serial;
                delete rowsInsert[index].goods_name;
                delete rowsInsert[index].goods_barcode;
                delete rowsInsert[index].color_name;
                delete rowsInsert[index].size_name;
				delete rowsInsert[index].out_warehouse_number;
				delete rowsInsert[index].in_warehouse_number;
				delete rowsInsert[index].goods_status;
				delete rowsInsert[index].create_time;
				delete rowsInsert[index].update_time;
				delete rowsInsert[index].lock_version;
            });
        }
		var rowsUpdate = $('#orders_grid_details').datagrid('getChanges','updated');
        if (rowsUpdate.length > 0) {
            rowsUpdate = $.extend(true, [], rowsUpdate);
            rowsUpdate.forEach(function(row,index) {
                delete rowsUpdate[index].goods_serial;
                delete rowsUpdate[index].goods_name;
                delete rowsUpdate[index].goods_barcode;
                delete rowsUpdate[index].color_name;
                delete rowsUpdate[index].size_name;
				delete rowsUpdate[index].out_warehouse_number;
				delete rowsUpdate[index].in_warehouse_number;
				delete rowsUpdate[index].goods_status;
				delete rowsUpdate[index].create_time;
				delete rowsUpdate[index].update_time;
				delete rowsUpdate[index].lock_version;
            });
        }
		var rowsDelete = $('#orders_grid_details').datagrid('getChanges','deleted');
        if (rowsDelete.length > 0) {
            rowsDelete = $.extend(true, [], rowsDelete);
            rowsDelete.forEach(function(row,index) {
                delete rowsDelete[index].goods_serial;
                delete rowsDelete[index].goods_name;
                delete rowsDelete[index].goods_barcode;
                delete rowsDelete[index].color_name;
                delete rowsDelete[index].size_name;
				delete rowsDelete[index].out_warehouse_number;
				delete rowsDelete[index].in_warehouse_number;
				delete rowsDelete[index].goods_status;
				delete rowsDelete[index].create_time;
				delete rowsDelete[index].update_time;
				delete rowsDelete[index].lock_version;
            });
        }
		form.form('submit', {
			url: url,
			queryParams:{
				/* 合计数量 */
				goods_number:goods_number,
                /* 版本锁 */
                lock_version:history._lockVersion,
                /* 单据编号 */
                orders_code:history.ordersCode,
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
						module:'库存调拨单',
						operate:txt,
						content:'数据验证失败~~',
						icon:'hr-warn'
					}, false);
                    return false;
                }
			//	$.messager.progress({title:'请稍候',msg:'正在保存库存调拨单......'});
				return true;
			},
			success : function(data) {
                data = $.parseJSON(data);
				var msg = 'hr-ok';
                switch(data.errcode) {
                    case 0:/*正常提交*/
                        _fun(data.data);
                        break;
                    case 2:/*库存不足*/
                        $.h.window.winCheckGoods.open(function() {
                            /*加载数据*/
                            $('#win_base_check_grid').datagrid('loadData',data.data);
                            /*提交按钮事件*/
                            $('#win_base_check_submit').linkbutton({
                                onClick: function() {
                                    $.h.window.winCheckGoods.close();
                                    mSaveOrders(url, _fun);
                                }
                            });
                            $('#win_base_check_direct_submit').linkbutton({
                                onClick: function() {
                                    $.h.window.winCheckGoods.close();
                                    mSaveOrders(url + "No", _fun);
                                }
                            });
                        });
						msg = 'hr-warn';
                        break;
					default:
						msg = 'hr-error';
                }
				parent.$.h.index.setOperateInfo({
					module:'库存调拨单',
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
    }

    /**
	 * 双击单据列表行
	 */
	function onOrdersDblClickRow(index,row) {
		var ordersCode = row ? row.orders_code : index;
		$.ajax({
			url: '/web/store/loadTransferDetails',
			data: {
				orders_code:ordersCode
			},
			type:'post',
			cache:false,
			dataType:'json',
			beforeSend: function(xhr) {
                /* 初始化单据数据 */
                initOrdersData();
				$.messager.progress({title:'Please waiting',msg:'Submit data...'});
			},
			success: function(data) {
				if (data.errcode == 0) {
					var parame = {
						orders:data.data.orders,
						details:data.data.details
					};
					switch (data.data.orders_status) {
						case 0:
						case 8:/*草稿*/
							data.href = '/web/store/store_transfer_orders_center';
                            /* 单据ID */
                            history._id = data.data.orders_id;
                            /* 版本锁 */
                            history._lockVersion = data.data.lock_version;
                            /* 单据编号 */
                            history.ordersCode = data.data.orders_code;
							draftController(data);
							break;
						case 9:/*库存调拨单*/
							data.href = '/web/store/store_transfer_orders_center_complete';
							ordersController(data);
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
                data.data.orders_date = $.DT.UnixToDate(data.data.orders_date, 0, false);
                data.data.warehouse_id == 0 && delete data.data.warehouse_id;
                data.data.settlement_id == 0 && delete data.data.settlement_id;
                data.data.delivery_id == 0 && delete data.data.delivery_id;
				$('#goods_number').numberbox('setValue',data.data.goods_number);
                $('#orders_form').form('load', data.data);
                /*显示单号*/
                $('#orders_code_view').html(data.data.orders_code);
                /*加载 GRID 数据*/
                $('#orders_grid_details').datagrid('loadData', data.data.details);
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
				$('#goods_number').numberbox('setValue',data.data.goods_number);
                $('#orders_code_view').html(data.data.orders_code);
                $('#out_warehouse_name').html(data.data.out_warehouse_name);
				$('#in_warehouse_name').html(data.data.in_warehouse_name);
                $('#orders_date').html($.DT.UnixToDate(data.data.orders_date, 0, true));
				$('#orders_remark').html(data.data.orders_remark);
                $('#orders_grid_details').datagrid('loadData',data.data.details);
            }
        });
    }

	/**
     * 处理商品 Grid 中商品数量变化方法
     */
    function setGridGoodsNumber(rowIndex, row, value) {
		/*合计数量*/
        var _tNumber = $('#goods_number').numberbox('getValue');
		/*合计数量 -= 商品数量*/
        _tNumber -= parseInt(history.goodsNumber);
        /*更新 Grid 记录*/
		var rowData =  {goods_number:value};
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总数量 = 原始总数量 + 商品数量*/
        $('#goods_number').numberbox('setValue', _tNumber + parseInt(value));
        /*设置为未保存*/
		history.isSave = false;
		/*设置记录的商品数量值为空*/
		history.goodsNumber = undefined;
    }
    /**
	 * 处理商品 Grid 中商品重复
	 * @param tagGrid           对象表格
	 * @param index             索引行
	 * @param rowData           行对象
	 * (商品货号、色码、尺码、商品数量、商品单价、商品折后价)
	 */
	function handleGridGoodsRepeat(tagGrid, index, rowData) {
		var rows = tagGrid.datagrid('getRows');
		var _exist = false;
		$.each(rows,function(i, r) {
			if (i != index && r.goods_code == rowData.goods_code && r.color_id == rowData.color_id && r.size_id == rowData.size_id) {
				tagGrid.datagrid('updateRow', {
					index:i,
					row:{
						goods_number:parseInt(rowData.goods_number) + parseInt(r.goods_number)
					}
				});
				if (index >= 0) {
					tagGrid.datagrid('deleteRow',index);
				}
				_exist = true;
				return false;
			}
		});
		return _exist;
	}
})(jQuery);