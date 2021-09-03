/**
 * 采购单函数
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
        csList:[],
		//临时单据
		temporary_orders_code:'',
	};
    /**
     * 主要的
     */
    $.h.po = {
        /**
         * 页面加载
         */
        onLeftLoad : function(e) { },
        /**
         * 搜索商品
         */
        searchGoods : function(target) {
			var isValid = $('#orders_form').form('validate');
			if (!isValid) {
				parent.$.h.index.setOperateInfo({
					module:'采购单',
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
						if (!handleGridGoodsRepeat('#orders_grid_details', -1, row)) {
							$('#orders_grid_details').datagrid('insertRow',{index:0,row:row});
							$.h.po.ordersDataCacheGrid();
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
			$('#orders_list').datagrid('reload',{orders_status:history.ordersStatus});
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
		 * 打开文件上传窗口
		 */

		/**
		 * 文件上传
		 */
		fileUpload:function () {
			var orders_code = $('#orders_code_view').html();
			let title = '订单号【'+ orders_code + '】';
			let $win = $("#upload_fj_po").window({
				title:title,
			});
			$win.window('open');
		},
		/**
		 * 查看文件
		 */
		seeFile:function(){
			let orders_code = $("#orders_code_view").html();
			let title = '订单号【'+ orders_code + '】';
			let $win = $("#see_fj_po_file").window({
				title:title
			});
			$win.window('open');
		},
		/**
		 * 判断是图片还是文件
		 */
		assistExtensionIf:function(value,rowData,rowIndex){
			let arr = rowData.assist_extension;
			let attr = ["png","jpg","gif","jpeg"];
			// let last_str = arr[arr.length-1];
			if (attr.indexOf(arr)>-1){
				//是图片预览
				let btn_str = '<a href="javascript:void(0);" title=\'预览\' class="createSelectUpBtn easyui-linkbutton" data-options="iconCls:\'icon-search\'" onclick="$.h.po.openYuLan(\''+rowData.assist_url+'\')"></a>';
				return btn_str;
			}
			//是文件下载
			let btn_str = '<a href="' + rowData.assist_url + '" title=\'下载文件\' download="' + rowData.assist_name + '" class="createSelectUpBtn easyui-linkbutton" data-options="iconCls:\'icon-print\'"></a>';
			return btn_str;
		},
		/**
		 * 预览图片
		 */
		openYuLan:function(url){
			$("#yulan_img").attr('src',url);
			$("#yulan_box").window("open");
		},
		/**
		 * 删除单据文件
		 */
		delFileOrders:function(value,row,index){
			let btn_str = '<a href="javascript:$.h.po.beginDel('+row.assist_id+',\''+row.assist_url+'\')" title="删除" class="createSelectUpBtn easyui-linkbutton" data-options="iconCls:\'icon-no\'" ></a>';
			return btn_str;
		},
		/**
		 * datagrid刷新
		 */
		fileDatagrid:function(){
			$("#file_datagrid").datagrid("reload");
		},
		/**
		 *  删除
		 */
		beginDel:function(assist_id,assist_url){
			let url = "/web/purchase/delOneFile?id="+assist_id+"&assist_url="+assist_url;
			$.messager.confirm("删除文件","是否要删除文件？",function (r) {
				if (r){
					$.get(url,function (data) {
						if (data.errcode == 0){
							// parent.$.h.index.setOperateInfo({
							// 	module:'采购单',
							// 	operate:'删除文件',
							// 	content:data.errmsg,
							// 	icon:data.errcode == 0 ? 'icon-ok' : 'hr-error'
							// }, true);
							//刷新datagrid
							$("#file_datagrid").datagrid("reload");
						}else{
							parent.$.h.index.setOperateInfo({
								module:'采购单',
								operate:'删除文件',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hr-error'
							}, true);
						}
					});
				}
			})

		},
		/**
		 * 当页面加载的时候请求接口，加载数据
		 */
		onLoadOrdersCode:function () {
			var url = '/web/purchase/loadOrdersCode';
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
						$("#orders_date").datebox('setValue',orders_date);
						$("#orders_rmoney").numberspinner('setValue',storage.orders_rmoney);
						$("#orders_remark").textbox('setValue',storage.orders_remark);
						$("#orders_other_type").combobox('setValue',storage.orders_other_type_id);
						$("#orders_other_type").combobox('setText',storage.orders_other_type_name);
						$("#orders_other_money").numberbox('setValue',storage.orders_other_money);
						$("#orders_erase_money").numberbox('setValue',storage.orders_erase_money);
						$("#goods_number").numberbox('setValue',storage.goods_number);
						$("#orders_pmoney").numberbox('setValue',storage.orders_pmoney);
					}
					var storage_grid = JSON.parse(window.localStorage.getItem(data.orders_code+'_grid'));
					if (storage_grid){
						$("#orders_grid_details").datagrid('loadData',storage_grid.data_insert);
					}
					history.ordersStatus = data.orders_status;
				});
			}
		},

		/**
		 * 缓存当前页面数据
		 */
		ordersDataCache:function(){
			if (history.ordersStatus == 7){
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
			var orders_date = $("#orders_date").datebox('getValue');
			//实付金额
			var orders_rmoney = $("#orders_rmoney").numberspinner('getValue');
			//备注
			var orders_remark = $("#orders_remark").textbox('getValue');
			//其他费用
			var orders_other_type_id = $("#orders_other_type").combobox('getValue');
			var orders_other_type_name = $("#orders_other_type").combobox('getText');
			//金额
			var orders_other_money = $("#orders_other_money").numberbox('getValue');
			//抹零
			var orders_erase_money = $("#orders_erase_money").numberbox('getValue');
			//合计数量
			var goods_number = $("#goods_number").numberbox('getValue');
			//合计金额
			var orders_pmoney = $("#orders_pmoney").numberbox('getValue');
			var array = {
				supplier_id:supplier_id,
				supplier_name:supplier_name,
				warehouse_id:warehouse_id,
				warehouse_name:warehouse_name,
				orders_other_money:orders_other_money,
				orders_erase_money:orders_erase_money,
				goods_number:goods_number,
				settlement_id:settlement_id,
				settlement_name:settlement_name,
				orders_date:orders_date,
				orders_rmoney:orders_rmoney,
				orders_remark:orders_remark,
				orders_other_type_id:orders_other_type_id,
				orders_other_type_name:orders_other_type_name,
				orders_pmoney:orders_pmoney,
			};
			window.localStorage.setItem(orders_code+'_data',JSON.stringify(array));
			}
		},
		//缓存datagrid数据
		ordersDataCacheGrid : function(){
			if (history.ordersStatus == 7){
			//单据编号
			var orders_code = $("#orders_code_view").html();
			var grid = $("#orders_grid_details").datagrid('getRows');
			var array = {
				data_insert: grid
			};
			window.localStorage.setItem(orders_code+'_grid',JSON.stringify(array));
			}
		},

        /**
         * 采购单商品列表操作
         */
        mGrid : {
			/**
			 * 点击单元格事件
			 */
			onClickCell : function(rowIndex, field, value,row) {
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
									history._goodsNumber = value;
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
									// $(ed.target).combobox({
									// 	url:$.toUrl('cell_editing', 'getGoodsColorData'),
									// 	queryParams:{
									// 		goods_code:row.goods_code
									// 	},
									// 	onLoadSuccess : function() {
									// 		var arr = {'colors':$(this).combobox('getData')};
									// 		$(this).combobox('select', row.color_id);
									// 	}
									// });
									break;
								// case 'goods_name':
								// 	var ed = $(this).datagrid('getEditor', {index:rowIndex,field:'goods_name'});
								// 	$(ed.target).combobox('setValue', row.goods_id).combobox('setText', row.goods_name);
								// 	$(ed.target).combobox({
								// 		url:$.toUrl('purchase', 'getGoodsData'),
								// 		queryParams:{
								// 			goods_code:row.goods_code
								// 		},
								// 		onLoadSuccess : function() {
								// 			var arr = {'goods':$(this).combobox('getData')};
								// 			$(this).combobox('select', row.goods_id);
								// 		}
								// 	});
								// 	break;
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
								// case 'goods_price':
								// 	var mPrivateMenu = $('#mPrivateMenu');
								// 	mPrivateMenu.html('');
								// 	mPrivateMenu.menu('appendItem', {value:row.goods_pprice, text: '采购价:' + $.formatMoney(row.goods_pprice,'￥'),iconCls:'hjtr-money'});
								// 	break;
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
				/*商品*/
				var edGoods = $(this).datagrid('getEditor', {index:index,field:'goods_name'});
				if (edGoods && edGoods.type == 'combobox' && $.trim($(edGoods.target).combobox('getText')) != '') {
					row.goods_name = $(edGoods.target).combobox('getText');
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
                    switch (name) {
                        case 'goods_number':/*数量*/
                            setGridGoodsNumber(index, row, value);
							$.h.po.ordersDataCacheGrid();
                            break;
                        case 'goods_price':/*单价*/
                            setGridGoodsPrice(index, row, value);
							$.h.po.ordersDataCacheGrid();
                            break;
                        case 'goods_discount':/*折扣*/
                            setGridGoodsDiscount(index, row, value);
							$.h.po.ordersDataCacheGrid();
                            break;
                        case 'goods_daprice':/*折扣价*/
                            setGridGoodsDiscountAfterPrice(index, row, value);
							$.h.po.ordersDataCacheGrid();
                            break;
						case 'color_id':
							row.color_id = value;
							handleGridGoodsRepeat('#orders_grid_details', index, row);
							$.h.po.ordersDataCacheGrid();
							break;
						case 'size_id':
							row.size_id = value;
							handleGridGoodsRepeat('#orders_grid_details', index, row);
							$.h.po.ordersDataCacheGrid();
							break;
                    }

                }
			},
			/**
			 * 在载入请求数据数据之前触发，如果返回false可终止载入数据操作。
			 */
			onBeforeLoad : function(param) {
                $(this).datagrid('options').editFields = ['color_id','size_id','goods_number','goods_price','goods_discount','goods_daprice','goods_name'];
				$(this).datagrid('bindKeyEvent');
				
				/* Enter 商品查询事件 */
				$('#search_keyword').combogrid('options').keyHandler.enter = function(e) {
					$.h.po.searchGoods($(e.data.target));
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
					href:$.toUrl('purchase', 'purchase_order_center'),
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
			console.log(data);
			data.orders.orders_rmoney = data.orders.orders_rmoney;
			/* 移除单据编号 */
			delete data.orders.orders_code;
			/* 移除单据ID */
			delete data.orders.orders_id;
			/* 移除创建时间 */
			delete data.orders.create_time;
			/* 移除更新时间 */
			delete data.orders.update_time;
			/* 更新单据日期 */
			data.orders.orders_date = $.DT.DateToUnix(new Date().Format("yyyy-MM-dd"), 0, true);
			/* 删除制单员信息 */
			// delete data.orders.user_id;
			// delete data.orders.user_name;
			/* 删除状态 */
			delete data.orders.orders_status;
			/* 删除版本号信息 */
			delete data.orders.lock_version;
			/* 删除商品多余字段 */
			data.orders.details.forEach(function(row,index) {
                delete data.orders.details[index].create_time;
				delete data.orders.details[index].update_time;
                delete data.orders.details[index].details_id;
                // delete data.data.orders.details[index].goods_status;
                delete data.orders.details[index].lock_version;
                delete data.orders.details[index].orders_code;
            });
			//data.href = '/manage/purchase/purchase_orders_center';
			data.href = $.toUrl('purchase', 'purchase_order_center');
			/* 色码、尺码字典数据 */
			history.csList = data.list;
			copyControllerCaoGao(data);
		},
        /**
         * 保存采购单据（草稿）
         */
        mSaveRoughDraft : function(e) {
			mSaveOrders(
				'/web/purchase/saveOrdersRoughDraft',
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
						/* 合计数量 */
						$('#goods_number').numberbox('setValue', data.data.orders.goods_number);
						/* 合计金额 */
						$('#orders_pmoney').numberbox('setValue', data.data.orders.orders_pmoney);

						$('#orders_code_view').text(data.data.orders.orders_code);
						//$('#orders_grid_details').datagrid('acceptChanges');
						$('#orders_grid_details').datagrid('loadData',data.data.orders.details);
						$('#orders_list').datagrid('reload');
						/*设置为已保存*/
						history.isSave = true;
					}
					parent.$.h.index.setOperateInfo({
						module:'采购单',
						operate:'草稿',
						content:data.errmsg,
						icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
					}, true);
					$.messager.progress('close');
				}
			);
        },
		/**
		 * 保存采购单据（提交）
		 */
		mSaveFormally : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(_isRun) {
				if (_isRun) {
					mSaveOrders(
						'/web/purchase/saveOrdersFormally',
						function(data) {
							data = $.parseJSON(data);
							window.localStorage.removeItem(history.ordersCode+'_grid');
							window.localStorage.removeItem(history.ordersCode+'_data');
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
								/*设置为已保存*/
								history.isSave = true;
								data.href = $.toUrl('purchase', 'purchase_orders_center_complete');
								ordersController(data.data);
								
							}
							parent.$.h.index.setOperateInfo({
								module:'采购单',
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
		 * 保存采购单据（撤销）
		 */
		mSaveRevoke : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(_isRun) {
				if (_isRun) {
					$.ajax({
                        url:'/web/purchase/saveRevokeOrders',
						data: {
							orders_code:history.ordersCode,
							lock_version:history._lockVersion
						},
						type:'post',
						cache:false,
						dataType:'json',
						beforeSend: function(xhr) {
							$.messager.progress({title:'请稍后',msg:'正在处理数据...'});
						},
						success: function(data) {
							$('#orders_list').datagrid('reload');
							if (data.errcode == 0) {
								data.href = '/web/purchase/purchase_orders_center_revoke';
								revokeController(data);
							}
							parent.$.h.index.setOperateInfo({
								module:'采购单',
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
					module:'采购单',
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
			$.h.po.ordersDataCacheGrid();
			ordersMoney.numberbox('setValue', _tMoney);
			ordersNumber.numberbox('setValue', _tNumber);
			/*设置为未保存*/
			history.isSave = false;
		},
		/**
		 * 删除采购单
		 */
		mDelOrders : function(e) {
            if ( !history.ordersCode) {
				parent.$.h.index.setOperateInfo({
					module:'采购单',
					operate:'删除单据',
					content:'采购单单据编号错误~~',
					icon:'hr-error'
				}, true);
                return;
            }
            $.messager.confirm('提示信息', '您想要删除该单据吗？', function(isAjax){
	            if (isAjax) {
                    $.ajax({
						url: '/web/purchase/delOrders',
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

									href: $.toUrl('purchase', 'purchase_order_center'),
                                    onLoad : initOrdersData
                                });
                                $('#orders_list').datagrid('reload');
                            }
							parent.$.h.index.setOperateInfo({
								module:'采购单',
								operate:'删除单据',
								content:data.errmsg,
								icon:data.errcode == 0 ? 'icon-ok' : 'hr-error'
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
		 * 其他费用金额改变事件
		 */
		onOtherMoneyChange : function(newValue, oldValue) {
			var gm = $('#orders_pmoney').numberbox('getValue');
			gm = gm ? gm : 0;
			newValue = newValue ? newValue : 0;
			oldValue = oldValue ? oldValue : 0;
			var v = (parseFloat(gm) - parseFloat(oldValue)) + parseFloat(newValue);
			$('#orders_pmoney').numberbox('setValue', v);
		},
		/**
		 * 抹零金额改变事件
		 */
		onEraseMoneyChange : function(newValue, oldValue) {
			var gm = $('#orders_pmoney').numberbox('getValue');
			gm = gm ? gm : 0;
			newValue = newValue ? newValue : 0;
			oldValue = oldValue ? oldValue : 0;
			var v = (parseFloat(gm) + parseFloat(oldValue)) - parseFloat(newValue);
			$('#orders_pmoney').numberbox('setValue', v);
		},
		/**
		 * 实际金额金额改变事件
		 */
		onGoodsMoneyChange : function(newValue, oldValue) {
			$('#orders_rmoney').numberbox('setValue',newValue);
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
		modifyFormatter : function(value, row, index) {
			return row.orders_status == 1 ?
				'<a href="javascript:void(0);" class="hjtr-ico-view" style="display:inline-block;width:16px;height:16px;"></a>' :
				'<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    };
	/**
	 * 保存单据函数
     * @param url 地址
     * @param fun 回调函数
	 */
	function mSaveOrders(url, _fun) {
		var orders_code = $("#orders_code_view").html();
		history.ordersCode = orders_code;
		var grid = $('#orders_grid_details');
		grid.datagrid('options').endEditing.call(grid);
		/*验证商品表中是否存在数据*/
		if (grid.datagrid('getRows').length < 1) {
			parent.$.h.index.setOperateInfo({
				module:'采购单',
				operate:'保存单据',
				content:'商品列表中没有任何数据~~',
				icon:'hr-warn'
			}, false);
			return false;
		}
		var form = $('#orders_form');
		/* 其他费用 */
		var other_type = $('#orders_other_type').combobox('getValue');
		/* 金额 */
		var other_money = $('#orders_other_money').numberbox('getValue');
		/* 抹零 */
		var erase_money = $('#orders_erase_money').numberbox('getValue');
		/* 合计数量 */
		var goods_number = $('#goods_number').numberbox('getValue');
		/* 合计金额 */
		var orders_pmoney = $('#orders_pmoney').numberbox('getValue');
		/* 实付金额 */
		var orders_rmoney = $("#orders_rmoney").numberbox('getValue');
		if (parseFloat(orders_pmoney) <= 0 || parseFloat(orders_rmoney) <= 0) {
			parent.$.h.index.setOperateInfo({
				module:'采购单',
				operate:'保存单据',
				content:'单据金额错误~~',
				icon:'hr-warn'
			}, true);
			return;
		}
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
		}else {
			var target = $(this);
			var rowsInsert = grid.datagrid('getChanges', 'inserted');
			if (rowsInsert.length > 0) {
				rowsInsert = $.extend(true, [], rowsInsert);
				rowsInsert.forEach(function (row, index) {
					delete rowsInsert[index].goods_serial;
					delete rowsInsert[index].goods_name;
					delete rowsInsert[index].goods_barcode;
					delete rowsInsert[index].color_name;
					delete rowsInsert[index].size_name;
					delete rowsInsert[index].goods_pprice;
				});
			}
			var rowsUpdate = grid.datagrid('getChanges', 'updated');
			if (rowsUpdate.length > 0) {
				rowsUpdate = $.extend(true, [], rowsUpdate);
				rowsUpdate.forEach(function (row, index) {
					delete rowsUpdate[index].goods_serial;
					delete rowsUpdate[index].goods_name;
					delete rowsUpdate[index].goods_barcode;
					delete rowsUpdate[index].color_name;
					delete rowsUpdate[index].size_name;
					delete rowsUpdate[index].goods_pprice;
				});
			}
			var rowsDelete = grid.datagrid('getChanges', 'deleted');
			if (rowsDelete.length > 0) {
				rowsDelete = $.extend(true, [], rowsDelete);
				rowsDelete.forEach(function (row, index) {
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
			novalidate: true,
			queryParams:{
				/* 其它费用 */
				other_type:other_type,
				/* 金额 */
				other_money:other_money,
				/* 抹零 */
				erase_money:erase_money,
				/* 合计数量 */
				goods_number:goods_number,
				/* 合计金额 */
				orders_pmoney:orders_pmoney,
                /* 版本锁 */
                lock_version:history._lockVersion,
                /* 单据编号 */
                orders_code:orders_code,
				/*新增商品数据*/
				data_insert:JSON.stringify(rowsInsert),
				/*更新商品数据*/
				data_update:JSON.stringify(rowsUpdate),
				/*删除商品数据*/
				data_delete:JSON.stringify(rowsDelete)
			},
			onSubmit : function() {
				 $.messager.progress({title:'请稍候',msg:'正在保存采购单据......'});
				return true;
			},
			success : _fun
		});
	};

	/**
     * 初始化单据数据
     */
    function initOrdersData() {
		history._id = '';
        history._lockVersion = 0;
		history.isSave = true;
		history.ordersCode = undefined;
		history.goodsNumber = undefined;
        history.csList = [];
        history.ordersStatus = -1;
		$.h.po.onLoadOrdersCode();
    };

	/**
	 * 双击
	 */
	function onOrdersDblClickRow(index,row) {
		$.ajax({
			url: '/web/purchase/loadOrdersDetails',
			data: {
				orders_code:row.orders_code
			},
			type:'post',
			cache:false,
			dataType:'json',
			beforeSend: function(xhr) {
				initOrdersData();
				$.messager.progress({title:'请稍等',msg:'加载数据中...'});
			},
			success: function(data) {
				if (data.errcode == 0) {
					/* 单据信息 */
					history.orders = data.data;
					/* 单据ID */
					history._id = data.data.orders.orders_id;
					/* 版本锁 */
					history._lockVersion = data.data.orders.lock_version;
					/* 单据编号 */
					history.ordersCode = data.data.orders.orders_code;
					/* 单据状态 */
					history.ordersStatus = data.data.orders.orders_status;
					switch (data.data.orders.orders_status) {
						case 0:/*草稿*/
							data.data.href = $.toUrl('purchase', 'purchase_order_center');
							draftController(data.data);
							break;
						case 8:/*订单*/
							data.data.href = $.toUrl('purchase', 'purchase_order_center');
							draftController(data.data);
							break;
						case 9:/*完成*/
							/* 备份数据 (复制为新单据使用) */
							history.orders = data.data;
							data.href = $.toUrl('purchase', 'purchase_orders_center_complete');
							ordersController(data);
							break;
						case 1:/*撤销*/
							/* 备份数据 (复制为新单据使用) */
							history.orders = data;
							//data.href = '/manage/purchase/purchase_orders_center_revoke';
							data.href = $.toUrl('purchase', 'purchase_orders_center_revoke');
							revokeController(data);
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
            href:data.href,
            onLoad : function() {
				data.orders.orders_date = $.DT.UnixToDate(data.orders.orders_date, 0, false);
				data.orders.settlement_id == 0 && delete data.orders.settlement_id;
				data.orders.other_type == 0 && (data.orders.other_type = '');
                var form = $('#orders_form');
                form.form('load', data.orders);
				$('#supplier_id').combogrid('setValue',data.orders.supplier_id);
                $('#supplier_id').combogrid('setText',data.orders.supplier_name);
				$('#orders_code_view').text(data.orders.orders_code);
				$('#orders_other_type').combobox('setValue',data.orders.other_type);
				$('#orders_other_money').numberbox('initValue',data.orders.other_money).numberbox('setValue',data.orders.other_money);
				$('#orders_erase_money').numberbox('initValue',data.orders.erase_money).numberbox('setValue',data.orders.erase_money);
				$('#orders_pmoney').numberbox('initValue',data.orders.orders_pmoney).numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('initValue',data.orders.goods_number);
                $('#orders_grid_details').datagrid('loadData',data.orders.details);
            }
        });
    };
    /**
     * 完成页面
     */
    function ordersController(data) {
        let href = data.href;
        data = data.data;
        var controller = $('#main_layout').layout('panel','center');
        controller.panel({
            href:href,
            onLoad : function() {
				$('#orders_code_view').text(data.orders.orders_code);
				$('#supplier_name').html(data.orders.supplier_name);
				$('#warehouse_name').html(data.orders.org_name);
				$('#settlement_name').html(data.orders.settlement_name);
				$('#orders_rmoney').html(data.orders.orders_rmoney);
				$('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
				$('#orders_remark').html(data.orders.orders_remark);
				$('#orders_other_type').textbox('setValue',data.orders.other_name);
				$('#orders_other_money').numberbox('setValue',data.orders.other_money);
				$('#orders_erase_money').numberbox('setValue',data.orders.erase_money);
				$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.orders.goods_number);
                $('#orders_grid_details').datagrid('loadData',data.orders.details);
			}
        });
    };
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
        });
    };
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
				$('#orders_date').datebox('setValue',data.orders.orders_date);
				$('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
				$('#goods_number').numberbox('setValue',data.orders.goods_number);

				data.orders.details.forEach(function(rowData,rowIndex) {
					$('#orders_grid_details').datagrid('insertRow',{
						index: rowIndex,
						row: rowData
					});
				});
            }

        });
	};
	// 复制为新单时，直接让他生成草稿
	function copyControllerCaoGao(data) {
		$('#orders_form').form('submit', {
			url:'/web/purchase/saveOrdersRoughDraft',
			// novalidate: true,
			queryParams:{
				/* 供应商 */
				supplier_id:data.orders.supplier_id,
				/* 结算账户 */
				settlement_id:data.orders.settlement_id,
				/* 仓库ID */
				warehouse_id:data.orders.warehouse_id,
				/* 其它费用 */
				other_type:data.orders.other_type,
				/* 金额 */
				other_money:data.orders.other_money,
				/* 抹零 */
				erase_money:data.orders.erase_money,
				/* 合计数量 */
				goods_number:data.orders.goods_number,
				/* 合计金额 */
				orders_pmoney:data.orders.orders_pmoney,
				/* 版本锁 */
				lock_version:history._lockVersion,
				/* 备注 */
				orders_remark:data.orders.orders_remark,
				/* 实付金额 */
				orders_rmoney: data.orders.orders_rmoney,
				/*新增商品数据*/
				data_insert:JSON.stringify(data.orders.details),
				/*新增商品数据*/
				data_update:JSON.stringify([]),
				/*新增商品数据*/
				data_delete:JSON.stringify([]),
				/* 订单日期 */
				orders_date:data.orders.orders_date
			},
			onSubmit : function() {
				$.messager.progress({title:'请稍候',msg:'正在保存单据......'});
				return true;
			},
			success : function (res) {
				res = JSON.parse(res);
				if (res.errcode == 0){
					$.messager.progress('close');
					history.ordersStatus = res.data.orders_status;
					res.data.href = data.href;
					copyController(res.data)
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
        _tMoney += parseFloat(row.goods_tdamoney);
		/*合计数量 -= 商品数量*/
        _tNumber -= parseInt(history._goodsNumber);
        /*更新 Grid 记录*/
        var rowData = {
            /*金额 = 数量 * 单价*/
            goods_tmoney:parseInt(value) * parseFloat(row.goods_price),
            /*折后金额 = 数量 * 折后价*/
            goods_tdamoney:parseInt(value) * parseFloat(row.goods_daprice)
        };
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
        $('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*总数量 = 原始总数量 + 商品数量*/
        $('#goods_number').numberbox('setValue', _tNumber + parseInt(value));
		
        /*设置为未保存*/
		history.isSave = false;
		/*设置记录的商品数量值为空*/
		history._goodsNumber = undefined;
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
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
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
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
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
        $('#orders_grid_details').datagrid('updateRow', { index:rowIndex, row:rowData });
		/*总金额 = 原始总金额 + 折后金额*/
		$('#orders_pmoney').numberbox('setValue',_tMoney + rowData.goods_tdamoney);
		/*设置为未保存*/
		history.isSave = false;
    };
	
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
	};
})(jQuery);