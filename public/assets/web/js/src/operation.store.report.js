/**
 * 仓库报表函数
 */
(function ($) {
    /**
     * 主要的
     */
    $.h.sr = {
        btnQuery: function (e) {
            var d = {};
            $('#query_form').find('input,select').each(function () {
                if (this.name && $.trim(this.value)) {
                    d[this.name] = this.value
                }
            });

            $('#goods_grid').datagrid({
                queryParams: d,
                method: 'get',
                onLoadSuccess: function (data) {
                }
            });
        },
        btnReset: function (e) {
            $('#query_form').form('reset');
        },
        detailFormatter: function (rowIndex, rowData) {
            return '<div style="padding-left:0px"><table id="ddv-' + rowIndex + '"></table></div>';
        },
        onExpandRow: function (rowIndex, row) {
            $('#ddv-' + rowIndex).datagrid({
                url: $.toUrl('store', 'queryDetailGoods'),
                queryParams: {
                    goods_code: row['goods_code'],
                    warehouse_id: row['warehouse_id']
                },
                fitColumns: true,
                singleSelect: false,
                height: 'auto',
                ctrlSelect: true,
                loadMsg: '正在加载数据...',
                method: 'get',
                columns: [[
                    {field: 'color_name', title: '颜色', width: 100, align: 'center'},
                    {field: 'size_name', title: '尺码', width: 100, align: 'center'},
                    {field: 'stock_number', title: '库存数量', width: 100, align: 'center'},
                ]],
                onResize: function () {
                    $('#goods_grid').datagrid('fixDetailRowHeight', rowIndex);
                },
                onLoadSuccess: function () {
                    setTimeout(function () {
                        $('#goods_grid').datagrid('fixDetailRowHeight', rowIndex);
                    }, 0);
                }
            });
            $('#goods_grid').datagrid('fixDetailRowHeight', rowIndex);
        },
        /**
         * 搜索商品
         */
        searchGoods: function (target) {
            $.h.s.goods.onSearchGoods({
                /*对象*/
                target: target,
                /*是否显示对话框*/
                isWindow: false,
                /*仓库ID*/
                warehouseId: 0,
                /*默认折扣*/
                goodsDiscount: 90,
                /*回调函数*/
                afterFun: function (data) {
                    var colorTag = $('#color_id').combobox({
                        url: '/web/stock_diary/getGoodsColorData',
                        queryParams: {goods_code: data},
                        onBeforeLoad: function (param) {
                            $('#color_id').next().find('.combo-arrow').attr('class', 'textbox-icon kbi-icon-loading');
                        },
                        onLoadSuccess: function () {
                            $('#color_id').next().find('.kbi-icon-loading').attr('class', 'textbox-icon combo-arrow');
                        }
                    });
                    var sizeTag = $('#size_id').combobox({
                        url: '/web/stock_diary/getGoodsSizeData',
                        queryParams: {goods_code: data},
                        onBeforeLoad: function (param) {
                            $('#size_id').next().find('.combo-arrow').attr('class', 'textbox-icon kbi-icon-loading');
                        },
                        onLoadSuccess: function () {
                            $('#size_id').next().find('.kbi-icon-loading').attr('class', 'textbox-icon combo-arrow');
                        }
                    });
                }
            });
        },
        /*加载之前执行事件*/
        onBeforeLoad: function (param) {

        },
        /**
         * 单元格单击单据列表事件
         */
        onClickCell: function (index, field, value, row) {
            if (field != 'orders_code') return;
            var w = (document.documentElement.clientWidth || document.body.clientWidth) - 280;
            var h = (document.documentElement.clientHeight || document.body.clientHeight) - 160;
            var url = {
                采购单: {
                    view:"/web/purchase/purchase_orders_center_complete",
                    loadFunc:function(param) {
                        $("#orders_grid_toolbar").hide();
                        $.post("/web/purchase/loadOrdersDetails",param,function(data){
                            data = data.data;
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
                        },"json");
                    }
                },
                采购单撤销: {
                    view:"/web/purchase/purchase_orders_center_revoke",
                    loadFunc:function(param) {
                        $("#orders_grid_revoke_toolbar").hide();
                        $.post("/web/purchase/loadOrdersDetails", param, function(data){
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
                        }, "json");
                    }
                },
                采购退货单: {
                    view:"/web/purchase/purchase_reject_center_complete",
                    loadFunc:function(param) {
                        $("#orders_grid_reject_toolbar").hide();
                        $.post("/web/purchase/loadOrdersRejectDetails",param, function(data){
                            data = data.data;
                            $('#orders_code_view').text(data.orders.orders_code);
                            $('#supplier_name').html(data.orders.supplier_name);
                            $('#warehouse_name').html(data.orders.org_name);
                            $('#settlement_name').html(data.orders.settlement_name);
                            $('#orders_rmoney').html(data.orders.orders_rmoney);
                            $('#orders_remark').html(data.orders.orders_remark);
                            $('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
                            $('#goods_number').numberbox('setValue',data.orders.goods_number);
                            $('#orders_grid_details').datagrid('loadData',data.orders.details);
                        }, "json");
                    }
                },
                采购退货单撤销: {
                    view:"/web/purchase/purchase_reject_center_revoke",
                    loadFunc:function(param) {
                        $("#orders_grid_reject_revoke_toolbar").hide();
                        $.post("/web/purchase/loadOrdersRejectDetails",param, function(data){
                            data = data.data;
                            $('#orders_code_view').text(data.orders.orders_code);
                            $('#supplier_name').html(data.orders.supplier_name);
                            $('#warehouse_name').html(data.orders.warehouse_name);
                            $('#settlement_name').html(data.orders.settlement_name);
                            $('#orders_rmoney').html(data.orders.orders_rmoney);
                            $('#orders_remark').html(data.orders.orders_remark);
                            $('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
                            $('#goods_number').numberbox('setValue',data.orders.goods_number);
                            $('#orders_grid_details').datagrid('loadData',data.details);
                        }, "json");
                    }
                },
                盘点单: {
                    view:"/web/store/store_inventory_orders_center_complete",
                    loadFunc:function(param) {
                        $.post("/web/store/loadInventoryOrders",param, function(data){
                            $('#orders_code_view').html(data.data.orders.orders_code);
                            $('#warehouse_name').html(data.data.orders.warehouse_name);
                            $('#goods_number').html(data.data.orders.goods_number);
                            $('#goods_plnumber').html(data.data.orders.goods_plnumber);
                            $('#goods_plmoney').html($.formatMoney(data.data.orders.goods_plmoney, '￥'));
                            $('#orders_remark').html(data.data.orders.orders_remark);
                        },"json");
                        $('#orders_grid_details').datagrid({queryParams: param});
                        $('#new_build_orders').hide();
                        $("#orders_grid_details_toolbar").hide();
                    }
                },
                调拨单: {
                    view:"/web/store/store_transfer_orders_center_complete",
                    dataUrl:"/web/store/loadTransferDetails",
                    loadFunc:function(param) {
                        $("#new_build_orders").hide();
                        $.post("/web/store/loadTransferDetails", param, function(data){
                            $('#goods_number').numberbox('setValue',data.data.goods_number);
                            $('#orders_code_view').html(data.data.orders_code);
                            $('#out_warehouse_name').html(data.data.out_warehouse_name);
                            $('#in_warehouse_name').html(data.data.in_warehouse_name);
                            $('#orders_date').html($.DT.UnixToDate(data.data.orders_date, 0, true));
                            $('#orders_remark').html(data.data.orders_remark);
                            $('#orders_grid_details').datagrid('loadData',data.data.details);
                        }, "json");
                    }
                },
                销售单: {
                    view:"/web/sale/sale_order_center_complete",
                    loadFunc:function(param) {
                        $("#orders_grid_toolbar").hide();
                        $.post("/web/SaleOrders/loadOrderDetails",param,function(data){
                            data = {
                                orders: data.data,
                                details: data.data.details
                            };
                            $('#orders_code_view').html(data.orders.orders_code);
                            $('#client_id').html(data.orders.client_name);
                            $('#orders_rmoney').html(data.orders.orders_rmoney)
                            $('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
                            $('.form-clo1 #warehouse_id').html(data.orders.org_name);
                            $('#settlement_id').html(data.orders.settlement_name);
                            $('#delivery_id').html(data.orders.dict_name);
                            $('#salesman_name').html(data.orders.user_name);
                            $('#orders_remark').html(data.orders.orders_remark);

                            $('#orders_other_type').textbox('setValue',data.orders.other_name);
                            $('#orders_other_money').numberbox('setValue',data.orders.other_money);
                            $('#orders_erase_money').numberbox('setValue',data.orders.erase_money);
                            $('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
                            $('#goods_number').numberbox('setValue',data.orders.goods_number);
                            $('#orders_grid_details').datagrid('loadData',data.details);
                        },"json");
                    }
                },
                销售单撤销: {
                    view:"/web/sale/sale_order_center_revoke",
                    dataUrl:"/web/SaleOrders/loadOrderDetails",
                    loadFunc:function(param) {
                        $("#orders_grid_toolbar").hide();
                        $.post("/web/SaleOrders/loadOrderDetails",param,function(data){
                            data = {
                                orders: data.data,
                                details: data.data.details
                            };
                            $('#orders_code_view').html(data.orders.orders_code);
                            $('#client_id').html(data.orders.client_name);
                            $('#orders_rmoney').html(data.orders.orders_rmoney)
                            $('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
                            $('.form-clo1 #warehouse_id').html(data.orders.org_name);
                            $('#settlement_id').html(data.orders.settlement_name);
                            $('#delivery_id').html(data.orders.dict_name);
                            $('#salesman_name').html(data.orders.user_name);
                            $('#orders_remark').html(data.orders.orders_remark);

                            $('#orders_other_type').textbox('setValue',data.orders.other_name);
                            $('#orders_other_money').numberbox('setValue',data.orders.other_money);
                            $('#orders_erase_money').numberbox('setValue',data.orders.erase_money);
                            $('#orders_pmoney').numberbox('setValue',data.orders.orders_pmoney);
                            $('#goods_number').numberbox('setValue',data.orders.goods_number);
                            $('#orders_grid_details').datagrid('loadData',data.details);
                        },"json");
                    }
                },
                销售退货单: {
                    view:"/web/sale/sale_reject_order_center_complete",
                    dataUrl:"/web/sale_reject_order/loadOrderDetails",
                    loadFunc:function(param) {
                        $("#reject_orders_toolbar").hide();
                        $.post("/web/sale_reject_order/loadOrderDetails", param, function(data){
                            data = {
                                orders: data.data,
                                details: data.data.details
                            };
                            $('#client_name').html(data.orders.client_name);
                            $('#orders_rmoney').html(data.orders.orders_rmoney);
                            $('#orders_date').html($.DT.UnixToDate(data.orders.orders_date, 0, true));
                            $('#warehouse_name').html(data.orders.org_name);
                            $('#settlement_name').html(data.orders.settlement_name);
                            $('#delivery_name').html(data.orders.dict_name);
                            $('#orders_remark').html(data.orders.orders_remark);
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

                        }, "json");
                    }
                }
            };
            $('#win_details').window({
                title: '单据详情',
                dataType: 'json',
                top: 50,
                left: 140,
                height: h,
                width: w,
                href: url[row.orders_type].view,
                onLoad: function () {
                    row.orders_status = 9;
                    var param = {
                        orders_code: row.orders_code,
                        status: 9
                    };
                    url[row.orders_type].loadFunc(param)
                }
            }).window('open');
        },
        /* 库存流水查询 */
        btnRecordQuery: function (e) {
            var form = $('#query_form');
            if (!form.form('validate')) return;

            var d = {};
            form.find('input,select').each(function () {
                if (this.name && $.trim(this.value)) {
                    d[this.name] = this.value
                }
            });
            //console.log(d);return;
            form.form('submit', {
                url: '/web/store/queryRecordGoods',
                //queryParams:d,
                onSubmit: function () {
                    /*验证表单所有对象*/
                    parent.$.h.index.setOperateInfo({
                        module: '库存流水',
                        operate: '查询',
                        content: '正在查询数据请稍候......',
                        icon: 'hr-warn'
                    }, false);
                    $.messager.progress({title: '请稍候', msg: '正在查询数据请稍候......'});
                },
                success: function (data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $('#record_grid').datagrid('loadData', data.data);
                    }

                    parent.$.h.index.setOperateInfo({
                        module: '库存流水',
                        operate: '查询',
                        content: data.errmsg,
                        icon: 'hr-ok'
                    }, true);
                    $.messager.progress('close');
                }
            });
        },
        btnRecordReset: function (e) {
            $("#query_form").reset();
        },
        //修改switchBtn状态
        changeSwitchBtn: function (stat) {
            $(this).switchbutton("setValue", stat ? 1 : 0);
        },
    }
})(jQuery);