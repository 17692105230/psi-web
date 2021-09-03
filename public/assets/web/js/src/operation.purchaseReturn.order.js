/**
 * 采购单函数
 */
(function($) {
	/**
	 * 主要的
	 */
	$.h.pr = {
		/**
		 * 数据
		 */
		data: {},
		/**
		 * 页面加载完成事件
		 */
		loaded:function(e) {},
		/**
		 * 页面加载完成事件
		 */
		leftLoaded:function(e) {},
		/**
		 * 搜索商品
		 */
        searchGoods:function(target) {

            $.h.s.goods.onSearchGoods({
                /*对象*/
                target:target,
                /*仓库ID*/
                warehouseId:1,
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
		loadOrderList : function(e) {},
		/**
		 * 查询单据列表
		 */
		searchOrderList : function(e) {},
		/**
		 * 采购单商品列表操作
		 */
		mGrid : {
			/**
			 * 点击单元格事件
			 */
			clickCell : function(rowIndex, field, value,row) {

			},
			/**
			 * 结束编辑事件
			 */
			endEdit : function(index, row) {

			},
			/**
			 * 完成编辑一行时触发
			 */
			afterEdit : function(index, row, changes) {

			},
			/**
			 * 在载入请求数据数据之前触发，如果返回false可终止载入数据操作。
			 */
			beforeLoad : function(param) {
				$(this).datagrid('options').editFields = ['color_id','size_id','goods_name','goods_number','goods_price','goods_discount','goods_daprice','goods_barcode'];
				$(this).datagrid('bindKeyEvent');
			}
		},
		/**
		 * 新开单
		 */
		mNewOrder : function(e) {},
		/**
		 * 复制为新单
		 */
		mCopyToNeworder : function(e) {},
		/**
		 * 保存单据（草稿）
		 */
		mSaveRoughDraft : function(e) {
			mSaveorder('', function(data) {});
		},
		/**
		 * 保存单据（提交）
		 */
		mSaveFormally : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(_isRun) {
				if (_isRun) {
					mSaveorder('', function(data) {});
				}
			});
		},
		/**
		 * 保存单据（撤销）
		 */
		mSaveRevoke : function(e) {
			$.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function(_isRun) {
				if (_isRun) {

				}
			});
		},
		/**
		 * 删除单据中的商品
		 */
		mDelGoods : function(e) {
			alert('删除');
		},
		/**
		 * 删除单据
		 */
		mDelorder : function(e) {
			$.messager.confirm('提示信息', '您想要删除该单据吗？', function(isAjax){
				if (isAjax) {

				}
			});
		},
		/**
		 * 双击单据列表事件
		 */
		dblClickRowOrder : function(index,row) {
			$.messager.confirm('提示','您做的更改可能不会被保存，确定要这样做吗？',function(_isRun) {
				if (_isRun) {

				}
			});
		},
		/**
		 * 单元格单击单据列表事件
		 */
		clickCellOrder : function(index, field, value, row) {},
		/**
		 * 其他费用金额改变事件
		 */
		otherMoneyChange : function(newValue, oldValue) {},
		/**
		 * 抹零金额改变事件
		 */
		eraseMoneyChange : function(newValue, oldValue) {},
		/**
		 * 实际金额金额改变事件
		 */
		goodsMoneyChange : function(newValue, oldValue) {},
		/**
		 * 单据完成页面
		 */
		complete : {
			onBeforeLoad : function(param) {
				var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
				_a.css('background','url(/static/manage/images/complete.png) no-repeat center center');
			}
		},
		/**
		 * 单据撤销页面
		 */
		reject : {
			onBeforeLoad : function(param) {
				var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
				_a.css('background','url(/static/manage/images/reject.png) no-repeat center center');
			}
		},
		/**
		 * 格式化修改按钮
		 */
		modifyFormatter : function(value, row, index) {
			return row.order_status == 1 ?
				'<a href="javascript:void(0);" class="hjtr-ico-view" style="display:inline-block;width:16px;height:16px;"></a>' :
				'<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
		}
	};
	/**
	 * 保存单据函数
	 * @param url 地址
	 * @param fun 回调函数
	 */
	function mSaveorder(url, _fun) {

	};

	/**
	 * 初始化单据数据
	 */
	function initOrderData() {};

	/**
	 * 双击
	 */
	function dblClickRowOrder(index,row) {};
	/**
	 * 草稿页面
	 */
	function draftController(data) {};
	/**
	 * 完成页面
	 */
	function orderController(data) {};
	/**
	 * 撤销页面
	 */
	function revokeController(data) {};
	/**
	 * 复制页面
	 */
	function copyController(data) {};
	/**
	 * 处理商品 Grid 中商品数量变化方法
	 */
	function setGridGoodsNumber(rowIndex, row, value) {

	};
	/**
	 * 处理商品 Grid 中商品单价变化方法
	 */
	function setGridGoodsPrice(rowIndex, row, value) {

	};
	/**
	 * 处理商品 Grid 中商品折扣变化方法
	 */
	function setGridGoodsDiscount(rowIndex, row, value) {

	};
	/**
	 * 处理商品 Grid 中商品折扣价变化方法
	 */
	function setGridGoodsDiscountAfterPrice(rowIndex, row, value) {

	};

	/**
	 * 处理商品 Grid 中商品重复
	 * @param idGrid            对象表格
	 * @param index             索引行
	 * @param rowData           行对象
	 * (商品货号、色码、尺码、商品数量、商品单价、商品折后价)
	 */
	function handleGridGoodsRepeat(idGrid, index, rowData) {

	};
})(jQuery);