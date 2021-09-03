/**
 * 采购计划函数
 */
(function($) {
	var history = {
		/* 记录ID */
        _id:'',
		/* 记录锁 */
        _lockVersion:0,
		/* 是否保存 */
        isSave:true,
		/* 单据状态（单据查询中使用） */
		ordersStatus:-1,
		/* 单据编号 */
        ordersCode:undefined,
		/* 商品数量 */
        goodsNumber:undefined,
		/* 商品编号 */
        goodsCode:undefined,
		/* 颜色、尺码列表 */
        csList:[]
    };
    /**
     * 主要的
     */
    $.h.pp = {
        /**
         * 页面加载
         */
        onLeftLoad : function(e) { },
        /**
         * 搜索商品
         */
        searchGoods : function(target) {
			var isValid = $('#plans_form').form('validate');
			if (!isValid) {
				parent.$.h.index.setOperateInfo({
					module:'采购订单',
					operate:'查询商品',
					content:'请完整填写单据信息',
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
						if (!handleGridGoodsRepeat(-1, row, row.goods_code, row.color_id, row.size_id)) {
							$('#plans_grid_details').datagrid('insertRow',{index:0,row:row});
							$.h.pp.ordersDataCacheGrid();
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
		 * 加载采购计划单据列表
		 */
		loadPlansList : function(e) {
			history.ordersStatus = $(this).linkbutton('options').status;
			$('#plans_list').datagrid('reload',{orders_status:history.ordersStatus});
		},
		/**
		 * 查询单据列表
		 */
		searchPlansList : function(e) {
			var params = {};
			$('#list_search_form').find('input,select').each(function() {
				if (this.name && $.trim(this.value)) {
					params[this.name] = this.value
				}
			});
			if ($.isEmptyObject(params)) return;
			params['status'] = history.ordersStatus;
			$('#plans_list').datagrid({queryParams:params,method:'get'});
		},
        /**
         * 采购计划商品列表操作
         */
        mGrid : {
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
										url:$.toUrl('cell_editing', 'getGoodsColorData'),
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
										url:$.toUrl('cell_editing', 'getGoodsSizeData'),
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
					/*设置未保存状态*/
					history.isSave = false;
					switch (name) {
                        case 'goods_number':
							/*商品数量*/
							setGridGoodsNumber(index,row,value);
							$.h.pp.ordersDataCacheGrid();
                            break;
                        case 'goods_price':
							/*商品单价*/
							setGridGoodsPrice(index,row,value);
							$.h.pp.ordersDataCacheGrid();
                            break;
                        case 'goods_discount':
                            /*折扣*/
							setGridGoodsDiscount(index,row,value);
							$.h.pp.ordersDataCacheGrid();
                            break;
                        case 'goods_daprice':
							/*折扣价*/
							setGridGoodsDiscountAfterPrice(index,row,value);
							$.h.pp.ordersDataCacheGrid();
                            break;
						case 'color_id':
							handleGridGoodsRepeat(index,row,row.goods_code,value,row.size_id);
							$.h.pp.ordersDataCacheGrid();
							break;
						case 'size_id':
							handleGridGoodsRepeat(index,row,row.goods_code,row.color_id,value);
							$.h.pp.ordersDataCacheGrid();
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
					$.h.pp.searchGoods($(e.data.target));
				};
			}
        },
		/**
		 * 新开单
		 */
		mNewPlans : function(e) {
			if (!history.isSave) {
                $.messager.confirm('提示', '未保存操作，确定要离开吗？~~~', function(r){
					if (r) { _a(); }
				});
			} else { _a(); }
            function _a() {
                $('#main_layout').layout('panel','center').panel({
					href:$.toUrl('purchase', 'purchase_plan_center'),
					onLoad : initOrdersData
				});
            }
		},
        /**
         * 保存采购订单（草稿）
         */
        mSaveRoughDraft : function(e) {
			mSaveOrders(
				'/web/purchase/savePlanRoughDraft',
				function(data) {
					data = $.parseJSON(data);
					if (data.errcode == 0) {
						window.localStorage.removeItem(history.ordersCode+'_grid');
						window.localStorage.removeItem(history.ordersCode+'_data');
                        /* 单据ID */
                        history._id = data.data.orders.orders_id;
                        /* 版本锁 */
                        history._lockVersion = data.data.orders.lock_version;
                        /* 单据编号 */
                        history.ordersCode = data.data.orders.orders_code;

						$('#orders_code_view').text(data.data.orders.orders_code);
						//$('#plans_grid_details').datagrid('acceptChanges');
						$('#plans_grid_details').datagrid('loadData',data.data.orders.details);
						$('#plans_list').datagrid('reload');
						/*设置为已保存*/
						history.isSave = true;
					}
					parent.$.h.index.setOperateInfo({
						module:'采购订单',
						operate:'草稿',
						content:data.errmsg,
						icon:data.errcode == 0 ? 'hr-ok' : 'hr-error'
					}, true);
					$.messager.progress('close');
				}
			);
        },
		/**
		 * 保存采购订单（提交）
		 */
		mSaveFormally : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(isConfirm) {
				if (isConfirm) {
					mSaveOrders(
						'/web/purchase/savePlanFormally',
						function(data) {
							data = $.parseJSON(data);
							if (data.errcode == 0) {
								window.localStorage.removeItem(history.ordersCode+'_grid');
								window.localStorage.removeItem(history.ordersCode+'_data');
                                $('#plans_list').datagrid('reload');
                                /* 设置为已保存 */
								history.isSave = true;
                                ordersController({
                                    orders:data.data.orders,
                                    details:data.data.orders.details
                                });
                                /* 初始化单据 */
                                initOrdersData();
							}
							parent.$.h.index.setOperateInfo({
								module:'采购订单',
								operate:'提交',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'hr-ok' : 'hr-error'
							}, true);
							$.messager.progress('close');
						}
					);
				}
			});
		},
		/**
		 * 删除单据中的商品
		 */
		mDelGoods : function(e) {
			var target = $('#plans_grid_details');
			var rows = target.datagrid("getChecked");
			var commMoney = $('#goods_number');
			var commNumber = $('#goods_number');
			var _tMoney = parseFloat(commMoney.numberbox('getValue'));
			var _tNumber = parseInt(commNumber.numberbox('getValue'));
			if (rows.length < 1) {
				parent.$.h.index.setOperateInfo({
					module:'采购订单',
					operate:'删除商品',
					content:'请勾选需要删除的行数据~~',
					icon:'hr-warn'
				}, false);
                return;
			}
			for (var i = rows.length - 1; i >= 0; i--) {  
				var index = target.datagrid('getRowIndex',rows[i]);  
				target.datagrid('deleteRow', index);
				_tMoney -= parseFloat(rows[i].goods_tdamoney);
				_tNumber -= parseInt(rows[i].goods_number);
			}
			$.h.pp.ordersDataCacheGrid();
			commMoney.numberbox('setValue', _tMoney);
			commNumber.numberbox('setValue', _tNumber);
			/*设置为未保存*/
			history.isSave = false;
		},
		/**
		 * 删除采购计划
		 */
		mDelPlans : function(e) {
			if (history.ordersCode == "") {
				parent.$.h.index.setOperateInfo({
					module:'采购订单',
					operate:'删除单据',
					content:'销售订单单据编号错误~~',
					icon:'hr-warn'
				}, false);
                return;
            }
            $.messager.confirm('提示信息', '您想要删除该单据吗？', function(isConfirm){
	            if (isConfirm) {
                    $.ajax({
                        url: '/web/purchase/delPlan',
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
                                    href:'/web/purchase/purchase_plan_center',
                                    onLoad : function() { }
                                });
                                $('#plans_list').datagrid('reload');
                                /* 初始化单据 */
                                initOrdersData();
                            }
							parent.$.h.index.setOperateInfo({
								module:'采购订单',
								operate:'删除单据',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'hr-ok' : 'hr-error'
							}, true);
                        },
                        complete: function() { }
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
		 *  页面加载时请求
		 */
		onLoadOrderCode:function () {
			var url = '/web/purchase/loadPlanOrdersCode';
			if (history.ordersStatus < 0 || history.ordersStatus == 7)
			{
				$.get(url,function (data) {
					$("#orders_code_view").text(data.orders_code);

					var storage = JSON.parse(window.localStorage.getItem(data.orders_code+'_data'));
					if (storage){
						$("#supplier_id").combogrid('setValue',storage.supplier_id);
						$("#supplier_id").combogrid('setText',storage.supplier_name);
						$("#warehouse_id").combobox('setValue',storage.warehouse_id);
						$("#warehouse_id").combobox('setText',storage.warehouse_name);
						var orders_date = $.DT.UnixToDate(storage.orders_date, 0, true);
						$("#orders_date").datebox('setValue',orders_date);
						$("#orders_remark").textbox('setValue',storage.orders_remark);
						$("#goods_number").numberbox('setValue',storage.goods_number);
						$("#orders_pmoney").numberbox('setValue',storage.orders_pmoney);
					}
					var storage_grid = JSON.parse(window.localStorage.getItem(data.orders_code+'_grid'));
					if (storage_grid){
						$("#plans_grid_details").datagrid('loadData',storage_grid.data_insert);
					}
					history.ordersStatus = data.orders_status;
				});
			}

		},
		/**
		 * 缓存当前页面数据
		 */
		ordersDataCache :function(){
			if (history.ordersStatus == 7){
				//获取当前页面单据编号
				var orders_code = $("#orders_code_view").html();
				//供应商
				var supplier_id = $("#supplier_id").combogrid('getValue');
				var supplier_name = $("#supplier_id").combogrid('getText');
				//仓库
				var warehouse_id = $("#warehouse_id").combobox('getValue');
				var warehouse_name = $("#warehouse_id").combobox('getText');
				//日期
				var orders_date = $("#orders_date").datebox('getValue');
				//备注
				var orders_remark = $("#orders_remark").textbox('getValue');
				//合计数量
				var goods_number = $("#goods_number").numberbox('getValue');
				//合计金额
				var orders_pmoney = $("#orders_pmoney").numberbox('getValue');
				var array = {
					supplier_id:supplier_id,
					supplier_name:supplier_name,
					warehouse_id:warehouse_id,
					warehouse_name:warehouse_name,
					orders_date:orders_date,
					orders_remark:orders_remark,
					goods_number:goods_number,
					orders_pmoney:orders_pmoney
				};
				window.localStorage.setItem(orders_code+'_data',JSON.stringify(array));
			}
		},
		/**
		 * 缓存datagrid数据
		 */
		ordersDataCacheGrid:function (){
			if (history.ordersStatus == 7){
				//获取单据编号
				var orders_code = $("#orders_code_view").html();
				var grid = $("#plans_grid_details").datagrid('getRows');
				var array = {
					data_insert: grid
				};
				window.localStorage.setItem(orders_code+'_grid',JSON.stringify(array));
			}
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
		modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    };
		/**
		 * 保存单据函数
		 *
		 * @param url地址
		 * @param fun
		 * @returns {boolean}
		 */
	function mSaveOrders(url, fun) {
		var orders_code = $("#orders_code_view").html();
		history.ordersCode = orders_code;
		var grid = $('#plans_grid_details');
		grid.datagrid('options').endEditing.call(grid);
		/*验证商品表中是否存在数据*/
		if (grid.datagrid('getData').total < 1) {
			parent.$.h.index.setOperateInfo({
				module:'采购订单',
				operate:'保存单据',
				content:'商品列表中没有任何数据~~',
				icon:'hr-warn'
			}, false);
			return false;
		}
		var form = $('#plans_form');
		var goods_number = $('#goods_number').numberbox('getValue');
		var orders_pmoney = $('#orders_pmoney').numberbox('getValue');
		//临时单据
		if (history.ordersStatus == 7){
			//临时单据只有添加
			var rows = grid.datagrid('getRows');
			if (rows.length > 0){
				rowsInsert = $.extend(true, [], rows);
				rowsInsert.forEach(function(row,index) {
					delete rowsInsert[index].goods_serial;
					delete rowsInsert[index].goods_name;
					delete rowsInsert[index].goods_barcode;
					delete rowsInsert[index].color_name;
					delete rowsInsert[index].size_name;
					delete rowsInsert[index].goods_pprice;
				});
			}
			rowsDelete = [];
			rowsUpdate = [];
		}else{
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
				delete rowsInsert[index].goods_pprice;
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
				delete rowsUpdate[index].goods_pprice;
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
				/*验证表单所有对象*/
				isValid = form.form('validate');
				if (!isValid) {
					parent.$.h.index.setOperateInfo({
						module:'采购订单',
						operate:'保存单据',
						content:'数据验证失败~~',
						icon:'hr-warn'
					}, false);
					return false;
				}
				$.messager.progress({title:'保存数据',msg:'正在保存销售单据......'});
				return true;
			},
			success : fun
		});
	};

	/**
     * 初始化单据数据
     */
    function initOrdersData() {
       history = {
			_id:'',
			_lockVersion:0,
			isSave:true,
			ordersCode:undefined,
			goodsNumber:undefined,
			goodsCode:undefined,
			csList:[]
		}
		$.h.pp.onLoadOrderCode();
    };

	/**
	 * 双击
	 */
	function onOrdersDblClickRow(index,row) {
		$.ajax({
			url: '/web/purchase/loadPlanDetails',
			data: {
				orders_code:row.orders_code
			},
			type:'post',
			cache:false,
			dataType:'json',
			beforeSend: function(xhr) {
                /* 初始化单据数据 */
                initOrdersData();
				$.messager.progress({title:'请稍等',msg:'正在处理数据中...'});
			},
			success: function(data) {
				if (data.errcode == 0) {
					data = data.data;
					switch (data.orders.orders_status) {
                        /*草稿*/
						case 0:
                            /* 单据ID */
                            history._id = data.orders.orders_id;
                            /* 版本锁 */
                            history._lockVersion = data.orders.lock_version;
                            /* 单据编号 */
                            history.ordersCode = data.orders.orders_code;
							draftController(data);
							break;
                        /*已转换为采购单*/
						case 9:
							ordersController(data);
							break;
					}
				}
			},
			complete: function() {
				$.messager.progress('close');
			}
		});
	};
    /**
     * 草稿页面
     */
    function draftController(data) {
        $('#main_layout').layout('panel','center').panel({
            href:$.toUrl('purchase', 'purchase_plan_center'),
            onLoad : function() {
                data.orders.orders_date = $.DT.UnixToDate(data.orders.orders_date, 0, false);
                var form = $('#plans_form');
                form.form('load', data.orders);
				$('#orders_code_view').text(data.orders.orders_code);
                $('#supplier_id').combogrid('setValue',data.orders.supplier_id);
                $('#supplier_id').combogrid('setText',data.orders.supplier_name);
				$('#warehouse_id').combobox('setValue',data.orders.warehouse_id);
                $('#warehouse_id').combobox('setText',data.orders.warehouse_name);
				$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.orders.goods_number);
				$('#orders_remark').textbox('setValue', data.orders.orders_remark);
                $('#plans_grid_details').datagrid('loadData',data.orders.details);
            }
        });
    };
    /**
     * 已转换采购单页面
     */
    function ordersController(data) {
        $('#main_layout').layout('panel','center').panel({
            href:$.toUrl('purchase', 'purchase_plan_center_complete'),
            onLoad : function() {
                $('#orders_code_view').text(data.orders.orders_code);
                $('#supplier_name').html(data.orders.supplier_name);
				$('#warehouse_name').html(data.orders.org_name);
                $('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
				$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.orders.goods_number);
				$('#orders_remark').html(data.orders.orders_remark);
                $('#plans_grid_details').datagrid('loadData',data.orders.details);
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
        var rowData = {
            /*金额 = 数量 * 单价*/
            goods_tmoney:parseInt(value) * parseFloat(row.goods_price),
            /*折后金额 = 数量 * 折后价*/
            goods_tdamoney:parseInt(value) * parseFloat(row.goods_daprice)
        };
        $('#plans_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
        $('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*总数量 = 原始总数量 + 商品数量*/
		
        $('#goods_number').numberbox('setValue', _tNumber + parseInt(value));
		/*设置为未保存*/
		history.isSave = false;
		/*设置记录的商品数量值为空*/
		history.goodsNumber = undefined;
    };
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
        $('#plans_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
		$('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*设置为未保存*/
		history.isSave = false;
    };

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
        $('#plans_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
		$('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*设置为未保存*/
		history.isSave = false;
    };

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
        $('#plans_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
		$('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*设置为未保存*/
		history.isSave = false;
    };

	/**
     * 处理商品 Grid 中商品重复
     */
    function handleGridGoodsRepeat(index, rowData, goods_code, color_id, size_id) {
        var rows = $('#plans_grid_details').datagrid('getRows');
		var _exist = false;
		$.each(rows,function(i, r) {
			if (i != index && r.goods_code == goods_code && r.color_id == color_id && r.size_id == size_id) {
				$('#plans_grid_details').datagrid('updateRow', {
					index:i,
					row:{
						goods_number:parseInt(rowData.goods_number) + parseInt(r.goods_number),
						goods_tmoney:(parseInt(rowData.goods_number) + parseInt(r.goods_number)) * parseFloat(rowData.goods_price),
						goods_tdamoney:(parseInt(rowData.goods_number) + parseInt(r.goods_number)) * parseFloat(rowData.goods_daprice)
					}
				});
				if (index >= 0) {
					$('#plans_grid_details').datagrid('deleteRow',index);
				}
				
				/*设置为未保存*/
				history.isSave = false;
				_exist = true;
				return false;
			}
		});
		return _exist;
    };

})(jQuery);