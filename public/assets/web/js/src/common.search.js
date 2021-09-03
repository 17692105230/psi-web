/**
 * 通用函数
 */
(function($){
	var history = {
        _isQueryMode : true,
        _goodsCode : undefined
    };

    var optionsGoods = {
        /*操作对象*/
		target:undefined,
		/*是否显示对话框*/
		isWindow:true,
        /*仓库ID*/
		warehouseId:0,
        /*默认折扣*/
		goodsDiscount:100,
        /*回调函数*/
		afterFun:function(data) {}
    };

	$.h.s = {
		goods : {
			mQueryMode : function(checked) {
				history._isQueryMode = checked;
			},
			/**
			 * 搜索商品
			 */
			onSearchGoods : mSearchGoods
		}
	};
    

	/* 查询商品 */
    function mSearchGoods(opt) {
        /* 迭代出默认配置 */
        $.each(optionsGoods, function(key, value){
            opt[key] = opt[key] == undefined ? value : opt[key];
        });
		opt.ordersType = opt.target.combogrid('options').ordersType || '';
		if (!opt.ordersType) {
			alert('请配置单据类型~~');
            return;
		}
		var target = opt.target;
        if (target == undefined) {
            alert('配置错误~~');
            return;
        }

        /* 设置商品货号为空 */
        history._goodsCode = undefined;
        if (history._isQueryMode) {
            if (target.combogrid('panel').panel('options').closed) {
                var keyword = target.combogrid('getText');
                target.combogrid('grid').datagrid('loadData',{total:0,rows:[]});
                target.combogrid('grid').datagrid('options').url = target.combogrid('options').searchUrl;
                target.combogrid('grid').datagrid('reload',{keyword:keyword});
                target.combogrid('showPanel');
                return;
            } else {
                target.combogrid('hidePanel');
            }
            var row = target.combogrid('grid').datagrid('getSelected');
            if (!row) { return; }
            history._goodsCode = row['goods_code'];

            target.combogrid('grid').datagrid('unselectAll');
            target.combogrid('grid').datagrid('loadData',{total:0,rows:[]});
        } else {
            history._goodsCode = $.trim(target.combogrid('getText'));
        }
		if (!opt.isWindow) {
			if (opt.afterFun) { opt.afterFun(history._goodsCode); }
			return;
		}
        if (history._goodsCode) {
            $.ajax({
                url: '/web/purchase/build',
                data: {
                    goods_code : history._goodsCode,
					warehouse_id:opt.warehouseId,
					orders_type:opt.ordersType
                },
                type:'post',
                cache:false,
                dataType:'json',
                beforeSend: function(xhr) {
					if (target.combogrid('options').icons.length > 0) {
						$('#search_keyword').next().find('.icon-clear').attr('class','textbox-icon kbi-icon-loading');
					}
                },
                success: function(data) {
                    if (data.errcode != 0) {
                        $.h.c.warnMessager(data.errmsg);
                        return;
                    }
                    $.h.window.winColorSize.open(function() {
                    	//alert('open');
                        var id_StockNumberGrid = '#stock_number_grid';
                        var id_CurrentNumberGrid = '#current_number_grid';
                        var id_CurrentColorSizeSubmit = '#current_color_size_submit';
						var id_GoodsReject = '#goods_reject';
						
						/* 显示退货按钮 */
						opt.ordersType == 'sro' ? $(id_GoodsReject).parent().show() : $(id_GoodsReject).parent().hide();
                        
						opt.data = data;

                        /*设置标题*/
                        $.h.window.winColorSize.setTitle(data.title + ' - ' + history._goodsCode);
                        $(id_CurrentNumberGrid).datagrid({
                            columns:data.columns,
                            data:data.rows
                        });

                        $(id_CurrentNumberGrid).datagrid('options').editFields = data.fields;
						var event = {
							event32 : function() {
								if ($('#layout_color_size').layout('panel','north').panel('options').closed) {
									$('#layout_color_size').layout('expand','north');
								} else {
									$('#layout_color_size').layout('collapse','north');
								}
							}
						};
                        $(id_CurrentNumberGrid).datagrid('bindKeyEvent', event);
                        $(id_CurrentColorSizeSubmit).linkbutton({
                            onClick : function() {
								$(id_CurrentNumberGrid).datagrid('options').endEditing.call($(id_CurrentNumberGrid));
								mBuildGoodsInputNumber(opt);
								$.h.window.winColorSize.close();
							}
                        });
                        $(id_StockNumberGrid).datagrid({
                            columns:data.stock,
                            data:data.rows
                        });
                        $(id_CurrentNumberGrid).datagrid('getPanel').panel('panel').attr('tabindex', 1).focus();
                    }, function() {
                        $('#search_keyword').combogrid('setText', '');
                        $('#search_keyword').combogrid('textbox').focus();
                    });
                },
                complete: function() {
					if (target.combogrid('options').icons.length > 0) {
						$('#search_keyword').next().find('.kbi-icon-loading').attr('class','textbox-icon icon-clear');
					}
                }
            });
        }
    }

	/* 构造录入数据 */
	function mBuildGoodsInputNumber(opt) {
		var goods = opt.data.goods;
		
		var dataRows = [];
		/*合计折后金额*/
		var totalDMoney = 0;
		/*合计金额*/
		var totalMoney = 0;
		/*合计数量*/
		var totalNumber = 0;
		/*构造详细数据*/
		var _a;
		opt.data.rows.forEach(function(row, rowindex) {
			for(var item in row) {
				if (item.indexOf('code_') != -1) {
					_a = item.replace('code_', '');
					if (row['value_'+_a] != 0) {
						var buildRow = {
							'goods_id':goods.goods_id,
							'goods_status':goods.goods_status,
							'goods_name':goods.goods_name,
							'goods_code':goods.goods_code,
							'goods_barcode':goods.goods_barcode,
							'color_name':row.color_name,
							'color_id':row.color_id,
							'size_name':row['name_'+_a],
							'size_id':row['code_'+_a],
							'goods_number':row['value_'+_a]
						};
						switch(opt.ordersType) {
							case 'pp':/* 采购计划 */
							case 'po':/* 采购单 */
							case 'pr':/* 采购退货单 */
								buildRow['goods_price'] = goods.goods_pprice;
								buildRow['goods_pprice'] = goods.goods_pprice;
								break;
							case 'sp':/* 销售计划 */
							case 'so':/* 销售单 */
							case 'sra':/* 销售退货申请 */
							case 'sro':/* 销售退货单 */
								/* 单据中的单价（可以改变的价格） */
								buildRow['goods_price'] = goods.goods_wprice;
								/* 单据中的采购价（不会改变的价格） */
								buildRow['goods_wprice'] = goods.goods_wprice;
								break;
							case 'si':/*盘点*/
								break;
							case 'st':/*调拨*/
								break;
						}
						switch(opt.ordersType) {
							case 'sp':
							case 'sra':
                                /* 金额 */
                                buildRow['goods_tmoney'] = goods['goods_wprice'] * row['value_'+_a];
								totalMoney += buildRow.goods_tmoney;
								break;
							case 'pp':
							case 'po':
							case 'pr':
								/* 折扣 */
								buildRow['goods_discount'] = opt.goodsDiscount;
								/* 折后价 */
								buildRow['goods_daprice'] = goods['goods_pprice'] * (opt.goodsDiscount / 100);
								/* 金额 */
								buildRow['goods_tmoney'] = goods['goods_pprice'] * row['value_'+_a];
								/* 折后合计金额 */
								buildRow['goods_tdamoney'] = (goods['goods_pprice'] * row['value_'+_a]) * (opt.goodsDiscount / 100);
								/* 统计合计金额 */
								totalMoney += buildRow['goods_tmoney'];
								/* 统计合计折后金额 */
								totalDMoney += buildRow['goods_tdamoney'];
								break;
							case 'so':
							case 'sro':/* 销售退货单 */
								/* 折扣 */
								buildRow['goods_discount'] = opt.goodsDiscount;
								/* 折后价 */
								buildRow['goods_daprice'] = goods['goods_wprice'] * (opt.goodsDiscount / 100);
								/* 金额 */
								buildRow['goods_tmoney'] = goods['goods_wprice'] * row['value_'+_a];
								/* 折后合计金额 */
								buildRow['goods_tdamoney'] = (goods['goods_wprice'] * row['value_'+_a]) * (opt.goodsDiscount / 100);
								/* 统计合计金额 */
								totalMoney += buildRow['goods_tmoney'];
								/* 统计合计折后金额 */
								totalDMoney += buildRow['goods_tdamoney'];
								break;
							case 'si':/*盘点*/
								buildRow['goods_anumber'] = row['stock_'+_a];
								break;
							case 'st':/*调拨*/
								var arr = row['stock_'+_a].split('|');
								buildRow['out_warehouse_number'] = $.trim(arr[0]);
								buildRow['in_warehouse_number'] = $.trim(arr[1]);
								break;
						}
						totalNumber += parseInt(buildRow['goods_number']);

						dataRows.push(buildRow);
					}
				}
			}
		});
		var resMessage = {
			'errcode':0,
			'errmsg':'构造数据成功~~',
			'total_money':totalMoney,
			'total_dmoney':totalDMoney,
			'total_number':totalNumber,
			'rows':dataRows
		};

		/*1、销售，2、退货*/
		if (opt.ordersType == 'sro') {
			resMessage.isReject = $('#goods_reject').switchbutton('options').checked ? 2 : 1;
		}
		if (opt.afterFun) { opt.afterFun(resMessage); }
		
	}

    /* 提交录入数据 
    function mSubmitGoodsInputNumber(opt) {
		opt.ordersType = opt.target.combogrid('options').ordersType || '';

		var target = $('#current_number_grid');
        var rows = target.datagrid('getRows');
		

		var rejectOrSale = $('#goods_reject').switchbutton('options').checked;
		
        $.ajax({
            url: '/manage/common/buildGoodsInputNumber',
            data: {
                goods_code:history._goodsCode,
                orders_type: opt.ordersType,
				warehouse_id:opt.warehouseId,
				goods_discount: opt.goodsDiscount,
                data:JSON.stringify(rows)
            },
            type:'post',
            cache:false,
            dataType:'json',
            beforeSend: function(xhr) {
                $.messager.progress({title:'请稍等',msg:'正在提交数据...'});
            },
            success: function(data) {
                //1、销售，2、退货
                if (opt.ordersType == 'sro') {
                    data.isReject = rejectOrSale ? 2 : 1;
                }
                if (opt.afterFun) { opt.afterFun(data, opt.cs); }
                $.h.window.winColorSize.close();
            },
            complete: function() {
                $.messager.progress('close');
            }
        });
    }
	*/
})(jQuery);