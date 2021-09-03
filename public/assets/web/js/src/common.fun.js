/**
 * 通用函数
 */
(function($){
	$.h.c = {
        /**
		 * 色码尺码 GRID 默认事件
		 */
        csw : {
            onClickCell : function(rowIndex, field, value, row) {
				if (field != 'color_name') {
					$(this).datagrid('unselectAll');
				}
				var opts = $(this).datagrid('options');
				$(this).datagrid('editCell', {index:rowIndex,field:field});
				if (opts.endEditing.call(this)) {
					$(this).datagrid('editCell', {index:rowIndex,field:field});
					opts.editIndex = rowIndex;
				}
			},
            onSetGlobalNumber : function() {
                var target = $('#current_number_grid');
                var opts = target.datagrid('options');
                if(opts.endEditing.call(target)) {
                    var num = $('#current_set_global_number').numberspinner('getValue');
                    var rows = target.datagrid('getSelections');
					if (rows.length == 0) {
						rows = target.datagrid('getRows');
					}
                    rows.forEach(function(row) {
                        opts.editFields.forEach(function(e) {
							if (opts.strColumns.indexOf(e) > 0 || $.trim(opts.strColumns) == '') {
								row[e] = num;
							}
                        });
                        target.datagrid('refreshRow',target.datagrid('getRowIndex',row));
                    });
                }
            }
        },
        cc : {
            onClickCellCallback : undefined,
            onAfterEditCallback : undefined,
            /*结束单元格编辑调用*/
            endEditing : function() {
                var opts = $(this).datagrid('options');
				if (opts.editIndex == undefined) { return true; }
				if ($(this).datagrid('validateRow', opts.editIndex)) {
                    $(this).datagrid('endEdit', opts.editIndex);
                    opts.editIndex = undefined;
					return true;
                } else {
					return false;
				}
            },
            /*单击单元格事件*/
			onClickCell : function(rowIndex, field, value, row) {
                if ($.h.c.cc.onClickCellCallback) {
                    var opts = $(this).datagrid('options');
                    if (opts.endEditing.call(this)) {
                        $(this).datagrid('editCell', {index:rowIndex,field:'goods_number'});
                        opts.editIndex = rowIndex;
                        $.h.c.cc.onClickCellCallback(value);
                    }
                } else {
                    alert('请配置单击单元格事件回调函数~~');
                }
            },
            /*编辑完成之后执行事件*/
			onAfterEdit : function(index, row, changes) {
                if ($.h.c.cc.onAfterEditCallback) {
                    if (!$.isEmptyObject(changes)) {
                        var arr = new Array();
                        var name,value;
                        for(key in changes) {
                            name = key;
                            value = changes[key];
                        }
                        index = row.row_index;
                        $.h.c.cc.onAfterEditCallback(index, row, value);
                    }
                } else {
                    alert('请配置编辑完成事件回调函数~~');
                }
            },
            /*加载之前执行事件*/
			onBeforeLoad : function(param) {
                $(this).datagrid('bindKeyEvent');
            }
        },
		/**
		 * 清除 Textbox 内容
		 */
		clearTextbox : function(e) {
			$(e.data.target).textbox('clear');
		},
		/**
		 * 清除 Combobox 内容
		 */
		clearCombobox : function(e) {
			$(e.data.target).combobox('clear');
		},
		/**
		 * 清除 combogrid 内容
		 */
		clearCombogrid : function(e) {
			$(e.data.target).combogrid('clear');
		},
		/**
		 * 清除 combotreegrid 内容
		 */
		clearCombotreegrid : function(e) {
			$(e.data.target).combotreegrid('clear');
		},
		/*提示消息*/
        warnMessager : function(msg) {
            $.messager.show({
                title:'操作通知',
                msg:msg,
                timeout:3000,
                showType:'slide'
            });
        },
		/* 设置 Combobox 属性 Text */
		handleCommboboxMenu : function(item) {
			if ($('#mPrivateMenu').menu('options').tag) {
				$('#mPrivateMenu').menu('options').tag.numberbox('setValue', item.value);
			}
		},
        /**
         * 处理商品 Grid 中商品重复
         * 1、idGrid            对象表格
         * 2、index             索引行
         * 3、rowData           行对象
         *    (商品货号、色码、尺码、商品数量、商品单价、商品折后价)
         */
        handleGridGoodsRepeat : function(idGrid, index, rowData) {
            var rows = $(idGrid).datagrid('getRows');
            var _exist = false;
            $.each(rows,function(i, r) {
                if (i != index && r.goods_code == rowData.goods_code && r.color_id == rowData.color_id && r.size_id == rowData.size_id) {
                    $(idGrid).datagrid('updateRow', {
                        index:i,
                        row:{
                            goods_number:parseInt(rowData.goods_number) + parseInt(r.goods_number),
                            goods_tmoney:(parseInt(rowData.goods_number) + parseInt(r.goods_number)) * parseFloat(rowData.goods_rprice),
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
        },
        /**
         * 构造商品 Grid 中的商品数量
         * 1、unitPrice             单价
         * 2、afterPrice            折后价
         * 3、value                 数量
         */
        onBuilderGridFromNumber : function(unitPrice, afterPrice, value) {
            /*整理 Grid 记录*/
            return {
                /*数量*/
                goods_number:value,
                /*金额 = 数量 * 单价*/
                goods_tmoney:parseInt(value) * parseFloat(unitPrice),
                /*折后金额 = 数量 * 折后价*/
                goods_tdamoney:parseInt(value) * parseFloat(afterPrice)
            };
        },
        /**
         * 构造商品 Grid 中的商品单价
         * 1、discount          折扣
         * 2、number            数量
         * 3、value             单价
         */
        onBuilderGridFromPrice : function(discount, number, value) {
            /*整理 Grid 记录*/
            return {
                /*折后价 = 单价 × （折扣 ÷ 100） */
                goods_daprice:parseFloat(value) * (parseInt(discount) / 100),
                /*金额 = 单价 × 数量 */
                goods_tmoney:parseFloat(value) * parseInt(number),
                /*折后金额 = 单价 × （折扣 ÷ 100） × 数量*/
                goods_tdamoney:parseFloat(value) * (parseInt(discount) / 100) * parseInt(number)
            };
        },
        /**
         * 构造商品 Grid 中的商品折扣（通用）
         * 1、unitPrice         单价
         * 2、number            数量
         * 3、value             折扣
         */
        onBuilderGridFromDiscount : function(unitPrice, number, value) {
            /*整理 Grid 记录*/
            return {
                /*折扣价 = 单价 × （折扣 ÷ 100）*/
                goods_daprice:parseFloat(unitPrice) * (parseInt(value) / 100),
                /*折后金额 = 单价 × （折扣 ÷ 100） × 数量*/
                goods_tdamoney:parseFloat(unitPrice) * (parseInt(value) / 100) * parseInt(number)
            };
        },
        /**
         * 构造商品 Grid 中的商品折扣价（通用）
         * 1、unitPrice          单价
         * 2、number             数量
         * 3、value              折后价
         */
        onBuilderGridFromDiscountAfterPrice : function(unitPrice, number, value) {
            /*整理 Grid 记录*/
            return {
                /*折扣 = 折后价 ÷ （单价 × 100）*/
                goods_discount:parseInt(parseFloat(value) / parseFloat(unitPrice) * 100),
                /*折后金额 = 折后价 × 数量*/
                goods_tdamoney:parseFloat(value) * parseInt(number)
            };
        },
        /**
         * 处理 数据改变时 存储localstorage
         */
        localStorageCache:function (orders_code,data) {
            window.localStorage.setItem(orders_code,JSON.stringify(data));
        }

	};
})(jQuery);