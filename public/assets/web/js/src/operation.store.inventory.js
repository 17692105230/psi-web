/**
 * 盘点单函数
 */
(function ($) {
    var history = {
        _id: '',
        _lockVersion: 0,
        isSave: true,
        /* 单据状态（单据查询中使用） */
        ordersStatus: -1,
        ordersCode: undefined,
        goodsCode: undefined,
        /* 临时保存商品数量 */
        goods_number: 0,
        csList: []
    }

    var mHandle = false;

    /**
     * 主要的
     */
    $.h.si = {
        /**
         * 加载
         */
        onLoad: function () {
        },
        /**
         * 搜索商品
         */
        searchGoods: function (target) {
            var isValid = $('#orders_form').form('validate');
            if (!isValid) {
                parent.$.h.index.setOperateInfo({
                    module: '盘点单',
                    operate: '查询商品',
                    content: '请完整填写单据信息~~',
                    icon: 'hr-warn'
                }, false);
                return;
            }
            var warehouseId = $('#warehouse_id').combobox('getValue');
            $.h.s.goods.onSearchGoods({
                /*对象*/
                target: target,
                /*仓库ID*/
                warehouseId: warehouseId,
                /*默认折扣*/
                goodsDiscount: 90,
                /*回调函数*/
                afterFun: function (data, cs) {
                    if (data.rows.length < 1) return;

                    var rowsInsert = data.rows;

                    rowsInsert.forEach(function (row, index) {
                        delete rowsInsert[index].goods_serial;
                        delete rowsInsert[index].goods_name;
                        delete rowsInsert[index].goods_barcode;
                        delete rowsInsert[index].color_name;
                        delete rowsInsert[index].size_name;
                    });

                    var rowsInsert = JSON.stringify(data.rows);
                    var childrenCode = $('#children_orders').combobox('getValue');
                    $('#orders_form').form('submit', {
                        url: '/web/store/saveInventoryRow',
                        queryParams: {
                            /* 子单据 */
                            children_code: childrenCode,
                            /* 版本锁 */
                            lock_version: history._lockVersion,
                            /* 单据编号 */
                            orders_code: history.ordersCode,
                            /* 新增商品数据 */
                            rows: rowsInsert
                        },
                        onSubmit: function () {
                            $.messager.progress({title: '保存数据', msg: '正在保存销售单据......'});
                        },
                        success: function (data) {
                            data = $.parseJSON(data);
                            if (data.errcode == 0) {
                                /* 单据ID */
                                history._id = data.data.orders.orders_id;
                                /* 版本锁 */
                                history._lockVersion = data.data.orders.lock_version;
                                /* 单据编号 */
                                history.ordersCode = data.data.orders.orders_code;
                                /* 单据编号 */
                                $('#orders_code_view').text(data.data.orders.orders_code);
                                /* 子单据 */
                                if (data.data.children_orders) {
                                    $('#children_orders').combobox({
                                        data: data.data.children_orders,
                                        onLoadSuccess: function () {
                                            $(this).combobox('select', data.data.children_use);
                                        }
                                    });
                                }
                                var children_code = $('#children_orders').combobox('getValue');

                                $.h.si.mGrid.onSelectChildrenOrders({children_code: children_code});

                                /*设置为已保存*/
                                history.isSave = true;
                            }
                            parent.$.h.index.setOperateInfo({
                                module: '盘点单',
                                operate: '添加商品',
                                content: data.errmsg,
                                icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                            }, true);
                            $.messager.progress('close');
                        }
                    });
                }
            });
        },
        /**
         * 加载单据列表
         */
        loadOrdersList: function (e) {
            history.ordersStatus = $(this).linkbutton('options').status;
            $('#orders_list').datagrid('reload', {status: history.ordersStatus});
        },
        /**
         * 查询单据列表
         */
        searchOrdersList: function (e) {
            var params = {};
            $('#list_search_form').find('input,select').each(function () {
                if (this.name && $.trim(this.value)) {
                    params[this.name] = this.value
                }
            });
            if ($.isEmptyObject(params)) return;
            params['status'] = history.ordersStatus;
            $('#orders_list').datagrid({queryParams: params, method: 'post'});
        },
        /**
         * 盘点单商品列表操作
         */
        mGrid: {
            /**
             * 单击单元格事件
             */
            onClickCell: function (rowIndex, field, value, row) {
                var opts = $(this).datagrid('options');
                if (opts.endEditing.call(this)) {
                    for (var i = 0, n = opts.editFields.length; i < n; i++) {
                        if (field == opts.editFields[i]) {
                            $(this).datagrid('editCell', {index: rowIndex, field: field});
                            opts.editIndex = rowIndex;

                            opts.editFieldIndex = i;
                            //var row = opts.finder.getRow(this, rowIndex);
                            switch (field) {
                                case 'color_id':
                                    var ed = $(this).datagrid('getEditor', {index: rowIndex, field: 'color_id'});
                                    $(ed.target).combobox('setValue', row.color_id).combobox('setText', row.color_name);
                                    var data = history.csList[row.goods_code];
                                    if (data && data.colors) {
                                        $(ed.target).combobox('loadData', data.colors);
                                        return;
                                    }
                                    $(ed.target).combobox({
                                        url: '/web/common/getGoodsColorData',
                                        queryParams: {
                                            goods_code: row.goods_code
                                        },
                                        onLoadSuccess: function () {
                                            var arr = {'colors': $(this).combobox('getData')};
                                            if (data && data.sizes) {
                                                arr = {'sizes': data.sizes, 'colors': $(this).combobox('getData')};
                                            }
                                            history.csList[row.goods_code] = arr;
                                            $(this).combobox('select', row.color_id);
                                        }
                                    });
                                    break;
                                case 'size_id':
                                    var ed = $(this).datagrid('getEditor', {index: rowIndex, field: 'size_id'});
                                    $(ed.target).combobox('setValue', row.size_id).combobox('setText', row.size_name);
                                    var data = history.csList[row.goods_code];
                                    if (data && data.sizes) {
                                        $(ed.target).combobox('loadData', data.sizes);
                                        return;
                                    }
                                    $(ed.target).combobox({
                                        url: '/web/common/getGoodsSizeData',
                                        queryParams: {
                                            goods_code: row.goods_code
                                        },
                                        onLoadSuccess: function () {
                                            var arr = {'sizes': $(this).combobox('getData')};
                                            if (data && data.colors) {
                                                arr = {'sizes': $(this).combobox('getData'), 'colors': data.colors};
                                            }
                                            history.csList[row.goods_code] = arr;
                                            $(this).combobox('select', row.size_id);
                                        }
                                    });
                                    break;
                                case 'goods_number':
                                    history.goods_number = row.goods_number
                                    break;
                            }
                        }
                    }
                }
            },
            /**
             * 结束编辑事件
             */
            onEndEdit: function (index, row) {
                var edColor = $(this).datagrid('getEditor', {index: index, field: 'color_id'});
                if (edColor && edColor.type == "combobox") {
                    if ($.trim($(edColor.target).combobox('getText')) != '') {
                        row.color_name = $(edColor.target).combobox('getText');
                    }
                }
                var edSize = $(this).datagrid('getEditor', {index: index, field: 'size_id'});
                if (edSize && edSize.type == "combobox") {
                    if ($.trim($(edSize.target).combobox('getText')) != '') {
                        row.size_name = $(edSize.target).combobox('getText');
                    }
                }
            },
            /**
             * 编辑完成之后执行事件
             */
            onAfterEdit: function (index, row, changes) {
                if (!$.isEmptyObject(changes)) {
                    var arr = new Array();
                    var name, value;
                    for (key in changes) {
                        name = key;
                        value = changes[key];
                    }
                    switch (name) {
                        case 'goods_number':
                            /*商品数量*/
                            setGridGoodsNumber(index, row, value);
                            break;
                        case 'color_id':
                            row.color_id = value;
                            setGoodsColorSize('#orders_grid_details', 1, index, row, 0);
                            break;
                        case 'size_id':
                            row.size_id = value;
                            setGoodsColorSize('#orders_grid_details', 2, index, row, 0);
                            break;
                    }
                }
            },
            /**
             * 加载之前执行事件
             */
            onBeforeLoad: function (param) {
                $(this).datagrid('options').editFields = ['color_id', 'size_id', 'goods_number'];
                $(this).datagrid('bindKeyEvent');

                /* Enter 商品查询事件 */
                $('#search_keyword').combogrid('options').keyHandler.enter = function (e) {
                    $.h.si.searchGoods($(e.data.target));
                };
            },
            /**
             * 加载完成事件
             */
            onAfterLoad: function (param) {
                $('#orders_grid_details').datagrid('options').url = '/web/store/loadInventoryDetails';
            },
            /**
             * 添加盘点人子单据
             */
            onAddUserChildrenOrders: function (e) {
                if (!history.ordersCode) {
                    return;
                }
                $.messager.confirm('提示', '您确定要新增子单据吗？', function (isRun) {
                    if (isRun) {
                        $.ajax({
                            url: '/web/store/addUserChildrenOrders',
                            data: {
                                orders_code: history.ordersCode
                            },
                            type: 'post',
                            cache: false,
                            dataType: 'json',
                            beforeSend: function (xhr) {
                                $.messager.progress({title: 'Please waiting', msg: 'Submit data...'});
                            },
                            success: function (data) {
                                if (data.errcode == 0) {
                                    $('#children_orders').combobox({
                                        data: data.data.children_orders,
                                        onLoadSuccess: function () {
                                            $(this).combobox('select', data.data.children_use);
                                        }
                                    });
                                    parent.$.h.index.setOperateInfo({
                                        module: '盘点单',
                                        operate: '添加子单据',
                                        content: data.errmsg,
                                        icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                                    }, true);
                                }
                            },
                            complete: function () {
                                $.messager.progress('close');
                                mHandle = false;
                            }
                        });
                    }
                });
            },
            /**
             * 删除盘点人子单据
             */
            onDelUserChildrenOrders: function (e) {
                if (!history.ordersCode) {
                    return;
                }
                var childrenCode = $('#children_orders').combobox('getValue');
                if ($.trim(childrenCode) == '') {
                    return;
                }
                $.messager.confirm('提示', '此操作会删除子单据下的商品，您确定要这么做吗？', function (isRun) {
                    if (isRun) {
                        $.ajax({
                            url: '/web/store/delUserChildrenOrders',
                            data: {
                                orders_code: history.ordersCode,
                                children_code: childrenCode
                            },
                            type: 'post',
                            cache: false,
                            dataType: 'json',
                            beforeSend: function (xhr) {
                                $.messager.progress({title: 'Please waiting', msg: 'Submit data...'});
                            },
                            success: function (data) {
                                if (data.errcode == 0) {
                                    $('#children_orders').combobox({
                                        data: data.data.children_orders,
                                        onLoadSuccess: function () {
                                            $(this).combobox('select', data.data.children_use);
                                        }
                                    });
                                }
                                parent.$.h.index.setOperateInfo({
                                    module: '盘点单',
                                    operate: '删除子单据',
                                    content: data.errmsg,
                                    icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                                }, true);
                            },
                            complete: function () {
                                $.messager.progress('close');
                                mHandle = false;
                            }
                        });
                    }
                });
            },
            /**
             * 选择子单据事件
             */
            onSelectChildrenOrders: function (record) {
                var param = {};
                param.orders_code = history.ordersCode;
                param.children_code = record.children_code;
                if (history.ordersStatus != -1) {
                    param.status = history.ordersStatus;
                }
                $('#orders_grid_details').datagrid({queryParams: param});
                /* 重置表单 */
                $('#btn_grid_query_from').form('reset');
            },
            /**
             * 查询商品信息
             */
            searchDetailsList: function (e) {
                var params = $('#orders_grid_details').datagrid('options').queryParams;
                $('#btn_grid_query_from').find('input,select').each(function () {
                    if (this.name) {
                        if ($.trim(this.value) != '') {
                            params[this.name] = this.value;
                        } else {
                            delete params[this.name];
                        }
                    }
                });
                $('#orders_grid_details').datagrid('reload');
            },
        },
        /**
         * 新开单
         */
        mNewOrders: function (e) {
            if (!history.isSave) {
                $.messager.confirm('提示', '未保存操作，确定要离开吗？~~~', function (r) {
                    if (r) {
                        _a();
                    }
                });
            } else {
                _a();
            }

            function _a() {
                $('#main_layout').layout('panel', 'center').panel({
                    href: '/web/store/store_inventory_orders_center',
                    onLoad: initOrdersData
                });
            }
        },
        /**
         * 改变仓库事件
         */
        mChangeWarehouse: function (newValue, oldValue) {
            if (!newValue || !oldValue) return;
            if (!history.ordersCode) return;
            if (mHandle) return;
            mHandle = true;
            $.messager.confirm('提示', '更改仓库将会造成盘点数据的变化，确定要这样操作吗？', function (isRun) {
                if (isRun) {
                    $.ajax({
                        url: '/manage/store/saveChangeWarehouse',
                        data: {
                            orders_code: history.ordersCode,
                            warehouse_id: newValue
                        },
                        type: 'post',
                        cache: false,
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            $.messager.progress({title: 'Please waiting', msg: 'Submit data...'});
                        },
                        success: function (data) {
                            if (data.errcode == 0) {
                                $('#orders_grid_details').datagrid("reload");
                                parent.$.h.index.setOperateInfo({
                                    module: '盘点单',
                                    operate: '改变仓库',
                                    content: data.errmsg,
                                    icon: data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
                                }, true);
                            }
                        },
                        complete: function () {
                            $.messager.progress('close');
                            mHandle = false;
                        }
                    });
                } else {
                    $('#warehouse_id').combobox('select', oldValue);
                    mHandle = false;
                }
            });
        },
        /**
         * 保存销售订单（草稿）
         */
        mSaveRoughDraft: function (e) {
            mSaveOrders(
                '/web/store/saveInventoryRoughDraft',
                function (data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $('#orders_list').datagrid('reload');
                        /* 版本锁 */
                        history._lockVersion = data.data.lock_version;
                        /* 版本锁 */
                        history.ordersCode = data.data.orders_code;
                        /*设置为已保存*/
                        history.isSave = true;
                    }
                    parent.$.h.index.setOperateInfo({
                        module: '盘点单',
                        operate: '草稿',
                        content: data.errmsg,
                        icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                    }, true);
                    $.messager.progress('close');
                }
            );
        },
        /**
         * 保存销售订单（提交）
         */
        mSaveFormally: function (e) {
            $.messager.confirm('提示', '执行操作后不能回撤，您确定要操作吗？', function (isConfirm) {
                if (isConfirm) {
                    mSaveOrders(
                        '/web/store/saveInventoryFormally',
                        function (data) {
                            data = $.parseJSON(data);
                            if (data.errcode == 0) {
                                $('#orders_list').datagrid('reload');
                                onOrdersDblClickRow(0, {orders_code: data.data.orders_code})
                            }
                            parent.$.h.index.setOperateInfo({
                                module: '盘点单',
                                operate: '提交',
                                content: data.errmsg,
                                icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                            }, true);
                            $.messager.progress('close');
                        }
                    );
                }
            });
        },
        /**
         * 删除盘点单
         */
        mDelOrders: function (e) {
            if (!history.ordersCode) {
                parent.$.h.index.setOperateInfo({
                    module: '采购单',
                    operate: '删除单据',
                    content: '盘点单单据编号错误~~',
                    icon: 'hr-error'
                }, false);
                return;
            }
            $.messager.confirm('提示信息', '您想要删除该单据吗？', function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: '/web/store/delInventory',
                        data: {
                            orders_code: history.ordersCode
                        },
                        type: 'post',
                        cache: false,
                        dataType: 'json',
                        beforeSend: function (xhr) {
                        },
                        success: function (data) {
                            if (data.errcode == 0) {
                                var controller = $('#main_layout').layout('panel', 'center');
                                controller.panel({
                                    href: '/web/store/store_inventory_orders_center',
                                    onLoad: function () {
                                    }
                                });
                                $('#orders_list').datagrid('reload');
                                /* 初始化单据 */
                                initOrdersData();
                            }
                            parent.$.h.index.setOperateInfo({
                                module: '盘点单',
                                operate: '删除单据',
                                content: data.errmsg,
                                icon: data.errcode == 0 ? 'icon-ok' : 'hr-error'
                            }, true);
                        },
                        complete: function () {
                        }
                    });
                }
            });
        },
        /**
         * 删除单据中的商品
         */
        mDelGoods: function (e) {
            $.messager.confirm('确认对话框', '你确定要删除选中的商品信息吗？', function (isRun) {
                if (isRun) {
                    var rows = $('#orders_grid_details').datagrid("getChecked");
                    if (rows.length < 1) {
                        parent.$.h.index.setOperateInfo({
                            module: '盘点单',
                            operate: '删除商品',
                            content: '请勾选需要删除的行数据~~',
                            icon: 'hr-warn'
                        }, false);
                        return;
                    }
                    var ids = '';
                    rows.forEach(function (row, index) {
                        ids += ',' + row.details_id;
                    });
                    $.ajax({
                        url: '/web/store/delGoods',
                        data: {
                            orders_code: history.ordersCode,
                            ids: ids
                        },
                        type: 'post',
                        cache: false,
                        dataType: 'json',
                        beforeSend: function (xhr) {
                            $.messager.progress({title: 'Please waiting', msg: 'Submit data...'});
                        },
                        success: function (data) {
                            if (data.errcode == 0) {
                                $('#orders_grid_details').datagrid("reload");
                            }
                            parent.$.h.index.setOperateInfo({
                                module: '盘点单',
                                operate: '删除商品',
                                content: data.errmsg,
                                icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                            }, true);
                        },
                        complete: function () {
                            $.messager.progress('close');
                        }
                    });
                }
            });
        },
        /**
         * 子单据明细窗口
         */
        winDetails: {
            open: function () {
                var left = ((document.documentElement.clientWidth || document.body.clientWidth) - 1200) / 2;
                var top = ((document.documentElement.clientHeight || document.body.clientHeight) - 660) / 5;
                $('#win_details').window({
                    title: '盘点明细',
                    dataType: 'json',
                    top: top,
                    left: left,
                    height: 660,
                    width: 1200,
                    href: '/web/store/store_inventory_orders_win',
                    onLoad: function () {
                        $.ajax({
                            url: '/web/store/loadInventoryOrders',
                            data: {
                                orders_code: history.ordersCode,
                            },
                            type: 'post',
                            cache: false,
                            dataType: 'json',
                            beforeSend: function (xhr) {
                                $.messager.progress({title: 'Please waiting', msg: 'Submit data...'});
                            },
                            success: function (data) {
                                if (data.errcode == 0) {
                                    /*显示单号*/
                                    $('#children_orders').combobox({
                                        data: data.data.children_orders,
                                        onLoadSuccess: function () {
                                            history.ordersCode=data.data.orders.orders_code;
                                            history.ordersStatus = data.data.orders.orders_status;
                                            $(this).combobox('select', data.data.children_use);
                                        }
                                    });
                                }
                            },
                            complete: function () {
                                $.messager.progress('close');
                            }
                        });
                    }
                }).window('open');
            },
            /**
             * 选择子单据事件
             */
            onSelectChildrenOrders: function (record) {
                var param = {};
                param.orders_code = history.ordersCode;
                param.children_code = record.children_code;
                param.status = history.ordersStatus == -1 ? 1 : history.ordersStatus;
                $('#orders_grid_details_c').datagrid({
                    url: '/web/store/loadInventoryDetails',
                    queryParams: param
                });
                $('#ws_goods').textbox('reset');
                $('#ws_color_id').combobox('reset');
                $('#ws_size_id').combobox('reset');
            },
            /**
             * 查询商品信息
             */
            searchDetailsList: function (e) {
                var params = $('#orders_grid_details_c').datagrid('options').queryParams;

                $('#ws_search_form').find('input,select').each(function () {
                    if (this.name) {
                        if ($.trim(this.value) != '') {
                            params[this.name] = this.value;
                        } else {
                            delete params[this.name];
                        }
                    }
                });
                $('#orders_grid_details_c').datagrid('reload');
            },
        },
        /**
         * 双击单据列表事件
         */
        onOrdersDblClickRow: function (index, row) {
            if (history.isSave) {
                onOrdersDblClickRow(index, row);
            } else {
                $.messager.confirm('提示', '您做的更改可能不会被保存，确定要这样做吗？', function (_isRun) {
                    if (_isRun) {
                        history.isSave = true;
                        onOrdersDblClickRow(index, row);
                    }
                });
            }
        },
        /**
         * 单元格单击单据列表事件
         */
        onOrdersClickCell: function (index, field, value, row) {
            if (field != 'orders_id2') return;
            onOrdersDblClickRow(index, row)
        },
        /**
         * 采购单完成页面
         */
        complete: {
            onBeforeLoad: function (param) {
                var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
                _a.css('background', 'url(/assets/web/img/complete.png) no-repeat center center');
            }
        },
        /**
         * 采购单撤销页面
         */
        reject: {
            onBeforeLoad: function (param) {
                var _a = $(this).datagrid('getPanel').children().children("div.datagrid-view2").children("div.datagrid-body");
                _a.css('background', 'url(/assets/web/img/reject.png) no-repeat center center');
            }
        },
        modifyFormatter: function (value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    }

    /**
     * 保存单据函数
     * 1、地址
     * 2、回调函数
     */
    function mSaveOrders(url, _fun) {
        var childrenCode = $('#children_orders').combobox('getValue');
        /*验证商品表中是否存在数据*/
        if (!$.trim(history.ordersCode) || $.trim(childrenCode) == '') {
            parent.$.h.index.setOperateInfo({
                module: '盘点单',
                operate: '保存单据',
                content: '商品列表中没有任何数据~~',
                icon: 'hr-warn'
            }, false);
            return false;
        }
        var form = $('#orders_form');

        form.form('submit', {
            url: url,
            queryParams: {
                /* 版本锁 */
                lock_version: history._lockVersion,
                /* 单据编号 */
                orders_code: history.ordersCode,
                /* 子单据编号 */
                children_code: childrenCode
            },
            onSubmit: function () {
                /*验证表单所有对象*/
                isValid = form.form('validate');
                if (!isValid) {
                    parent.$.h.index.setOperateInfo({
                        module: '盘点单',
                        operate: '保存单据',
                        content: '数据验证失败~~',
                        icon: 'hr-warn'
                    }, false);
                    return false;
                }
                $.messager.progress({title: '保存数据', msg: '正在保存销售单据......'});
                return true;
            },
            success: _fun
        });
    }

    /**
     * 初始化单据数据
     */
    function initOrdersData() {
        history = {
            _id: '',
            _lockVersion: 0,
            isSave: true,
            /* 单据状态（单据查询中使用） */
            ordersStatus: -1,
            childrenCode: undefined,
            ordersCode: undefined,
            goodsCode: undefined,
            /* 临时保存商品数量 */
            goods_number: 0,
            csList: []
        }
    }

    /**
     * 双击单据列表行
     */
    function onOrdersDblClickRow(index, row) {
        $.ajax({
            url: '/web/store/loadInventoryOrders',
            data: {
                orders_code: row.orders_code
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            beforeSend: function (xhr) {
                /* 初始化单据数据 */
                initOrdersData();
                $.messager.progress({title: 'Please waiting', msg: 'Submit data...'});
            },
            success: function (data) {
                if (data.errcode == 0) {
                    /* 单据ID */
                    history._id = data.data.orders.orders_id;
                    /* 版本锁 */
                    history._lockVersion = data.data.orders.lock_version;
                    /* 单据编号 */
                    history.ordersCode = data.data.orders.orders_code;
                    /* 单据编号 */
                    history.ordersStatus = data.data.orders.orders_status;
                    switch (data.data.orders.orders_status) {
                        case 0:/*申请*/
                        case 1:/*草稿*/
                            draftController(data);
                            break;
                        case 9:/*完成*/
                            ordersController(data);
                            break;
                    }
                }
            },
            complete: function () {
                $.messager.progress('close');
            }
        });
    }

    /**
     * 草稿单据页面
     */
    function draftController(data) {
        if (mHandle) return;
        mHandle = true;
        var controller = $('#main_layout').layout('panel', 'center');
        controller.panel({
            href: '/web/store/store_inventory_orders_center',
            onLoad: function () {
                /*显示单号*/
                $('#orders_code_view').html(data.data.orders.orders_code);
                $('#orders_form').form('load', data.data.orders);
                $('#children_orders').combobox({
                    data: data.data.children_orders,
                    onLoadSuccess: function () {
                        $(this).combobox('select', data.data.children_use);
                    }
                });
                mHandle = false;
            }
        });
    }

    /**
     * 正式单据页面
     */
    function ordersController(data) {
        $('#main_layout').layout('panel', 'center').panel({
            href: '/web/store/store_inventory_orders_center_complete',
            onLoad: function () {
                $('#orders_code_view').html(data.data.orders.orders_code);
                $('#warehouse_name').html(data.data.orders.warehouse_name);
                $('#goods_number').html(data.data.orders.goods_number);
                $('#goods_plnumber').html(data.data.orders.goods_plnumber);
                $('#goods_plmoney').html($.formatMoney(data.data.orders.goods_plmoney, '￥'));
                $('#orders_remark').html(data.data.orders.orders_remark);

                var param = {};
                param.orders_code = data.data.orders.orders_code;
                param.status = 9;

                $('#orders_grid_details').datagrid({queryParams: param});
            }
        });
    }

    /**
     * 处理商品 Grid 中商品数量变化方法
     */
    function setGridGoodsNumber(rowIndex, row, value) {
        var s = {
            details_id: row.details_id,
            orders_code: row.orders_code,
            row_goods_number: row.goods_number
        };
        $.ajax({
            url: '/web/store/saveGoodsNumber',
            data: {
                data: JSON.stringify(s)
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            beforeSend: function (xhr) {
                $('#orders_grid_details').datagrid('loading', 'Please waiting, Submit data...');
                parent.$.h.index.setOperateInfo({
                    module: '盘点单',
                    operate: '修改数量',
                    content: 'Please waiting, Submit data...',
                    icon: 'hr-loading'
                }, false);
            },
            success: function (data) {
                if (data.errcode == 0) {
                    //var childrenCode = $('#children_orders').combobox('getValue');
                    //$.h.si.mGrid.onSelectChildrenOrders({children_code:childrenCode});
                }
                parent.$.h.index.setOperateInfo({
                    module: '盘点单',
                    operate: '修改数量',
                    content: data.errmsg,
                    icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                }, true);
            },
            complete: function () {
                $('#orders_grid_details').datagrid('loaded');
            }
        });
    }

    /**
     * 处理商品 Grid 中商品重复
     * @param idGrid            对象表格
     * @param t                 1 颜色， 2 尺码
     * @param index             索引行
     * @param rowData           行对象
     * @param tag               如果重复是否提交的标记
     */
    function setGoodsColorSize(idGrid, t, index, rowData, tag) {
        var childrenCode = $('#children_orders').combobox('getValue');
        if ($.trim(childrenCode) == '') {
            parent.$.h.index.setOperateInfo({
                module: '盘点单',
                operate: t == 1 ? '修改颜色' : '修改尺码',
                content: '子单据错误~~',
                icon: 'hr-error'
            }, false);
        }
        var data = {
            details_id: rowData.details_id,
            orders_code: rowData.orders_code,
            children_code: childrenCode,
            goods_code: rowData.goods_code,
            color_id: rowData.color_id,
            size_id: rowData.size_id,
            goods_number: rowData.goods_number,
            lock_version: rowData.lock_version,
            t: t,
            tag: tag
        };
        rowData = data;
        $.ajax({
            url: '/web/store/saveGoodsColorSize',
            data: {
                data: JSON.stringify(rowData)
            },
            type: 'post',
            cache: false,
            dataType: 'json',
            beforeSend: function (xhr) {
                $.messager.progress({title: 'Please waiting', msg: 'Submit data...'});
            },
            success: function (data) {
                switch (data.errcode) {
                    case 7:
                        $.messager.confirm('确认对话框', data.errmsg, function (isRun) {
                            if (isRun) {
                                setGoodsColorSize(idGrid, t, index, rowData, 1);
                            } else {
                                $(idGrid).datagrid('rejectChanges');
                            }
                        });
                        break;
                    case 0:
                        if (data.data.repeat) {
                            $.h.si.mGrid.onSelectChildrenOrders({children_code: childrenCode});
                        }
                        parent.$.h.index.setOperateInfo({
                            module: '盘点单',
                            operate: t == 1 ? '修改颜色' : '修改尺码',
                            content: data.errmsg,
                            icon: data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
                        }, true);
                }
            },
            complete: function () {
                $.messager.progress('close');
            }
        });
    }
})(jQuery);