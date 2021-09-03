/**
 * 通用弹出窗口函数
 */

(function ($) {
    var dictConfig = {
        mWin: '',
        dictType: '',
        mWinHref: '',
        mGrid: '',
        mForm: '',
        dictSort: '#dict_sort',
        colorSort: '#color_sort'
    };

    var history = {
        _id: '',
        _dictType: '',
        _lockVersion: 0
    };
    $.h.window = {
        /*Demo窗口*/
        winBaseDemo: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_base_demo',
                    dictType: 'demo',
                    mWinHref: $.toUrl('windows', 'demo'),
                    mGrid: '#win_base_demo_grid',
                    mForm: '#win_base_demo_form',
                    dictSort: '#dict_sort'
                };
                dictOpen();
            },
            onClose: dictClose,
            onSave: dictSave,
            onDelete: dictDelete,
            onDblClickRow: dictDblClickRow,
            onRefresh: dictRefresh,
            onAddNew: dictAddNew
        },
        /*Demo1窗口*/
        winBaseDemo1: {
            onOpen: function () {
                var obj = $('#win_base_demo1');
                var win = obj.window({
                    href: $.toUrl('windows', 'demo1'),
                    dataType: 'json',
                    onLoad: function () {

                    }
                });
                win.window('open');
            },
            onClose: function () {
                $('#win_base_demo1').window('close');
            },
        },
        /*Demo2窗口*/
        winBaseDemo2: {
            onOpen: function () {
                var obj = $('#win_base_demo2');
                var win = obj.window({
                    href: $.toUrl('windows', 'demo2'),
                    dataType: 'json',
                    onLoad: function () {

                    }
                });
                win.window('open');
            },
            onClose: function () {
                $('#win_base_demo2').window('close');
            },
        },

        /*用户分类窗口*/
        coustomerClassifyWin: {
            onOpen: function () {
                var obj = $('#client_customer_classify_win');
                var win = obj.window({
                    href: $.toUrl('windows', 'customer_classify_list'),
                    dataType: 'json',
                    onLoad: function () {

                    }
                });
                win.window('open');
            },
            onClose: function () {
                $('#client_customer_classify_win').window('close');
            },
            onSave: function(){
                var form = $("#win_customer_classify_form");
                var target = $(this);
                var id = history._id;
                if (id) {
                    var url = '/web/customer/edit';
                } else {
                    var url = '/web/customer/add';
                }
                form.form('submit', {
                    url: url,
                    queryParams: {
                        /* 数据 ID */
                        custom_id: history._id,
                        /* 数据锁版本 */
                        lock_version: history._lockVersion
                    },
                    onSubmit: function (param) {
                        var isValid = $(this).form('validate');
                        if (isValid) {
                            target.linkbutton({disabled: true, iconCls: 'kbi-icon-loading'});
                        }
                        return isValid;
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        target.linkbutton({disabled: false, iconCls: 'icon-save'});
                        if (data.errcode == 0) {

                            $.h.index.setOperateInfo({
                                module: '客户类别',
                                operate: id ? '修改类别' : '添加类别',
                                content: data.errmsg,
                                icon: 'hr-ok'
                            }, true);
                            $.h.window.coustomerClassifyWin.onAddNew();
                            $.h.window.coustomerClassifyWin.onRefresh();
                            return true;
                        }

                        $.h.index.setOperateInfo({
                            module: '客户类别管理',
                            operate: history._id ? '修改类别' : '添加类别',
                            content: data.errmsg,
                            icon: 'hr-error'
                        }, true);
                    }
                });
            },
            onDelete:  function () {
                var row = $('#win_customer_classify_list').datagrid('getSelected');
                if (!row) {
                    parent.$.h.index.setOperateInfo({
                        module: '客户类别管理',
                        operate: '删除客户类别',
                        content: '请选至少择一行',
                        icon: 'hr-warn'
                    }, true);
                    return false;
                }
                $.messager.confirm('确认信息', '确定删除吗', function (r) {
                    if (!r) {
                        return false;
                    }
                    $.post(
                        '/web/customer/del',
                        {
                            classify_id: row.customer_classify_id,
                            lock_version: row.lock_version
                        },
                        function (data) {
                            if (data.errcode == 0) {
                                parent.$.h.index.setOperateInfo({
                                    module: '客户类别管理',
                                    operate: '删除客户类别',
                                    content: data.errmsg,
                                    icon: 'hr-ok'
                                }, true);
                                $.h.window.coustomerClassifyWin.onAddNew();
                                $.h.window.coustomerClassifyWin.onRefresh();
                            } else {
                                parent.$.h.index.setOperateInfo({
                                    module: '客户类别管理',
                                    operate: '删除客户类别',
                                    content: data.errmsg,
                                    icon: 'hr-error'
                                }, true);
                            }
                        });
                })

            },
            onDblClickRow:  function (rowIndex, rowData) {
                let data = {
                    'classify_name': rowData.customer_classify_name,
                    'classify_price': rowData.customer_classify_price,
                    'sort': rowData.sort,
                    'describe_info':rowData.customer_classify_describe
                };
                console.log(rowData);
                history._id = rowData.customer_classify_id;
                history._lockVersion = rowData.lock_version;
                $("#win_customer_classify_form").form('load', data);
            },
            onRefresh: function(){
                $('#win_customer_classify_list').datagrid("reload");
            },
            onAddNew: function () {
                history._id = '';
                history._lockVersion = 0;
                $("#win_customer_classify_form").form('reset');
                $("#classify_sort").numberspinner('textbox').val(100);
            }
        },

        /* 颜色管理窗口 */
        winColorList: {
            onOpen: function () {
                var obj = $('#win_color_list')
                var win = obj.window({
                    href: '/web/windows/color_list',
                    dataType: 'json',
                });
                win.window('open');
            },
            onClose: function () {
                $("#win_color_list").window('close');
            },
            onSave: function () {
                var form = $("#win_base_color_form");
                var target = $(this);
                var id = history._id;
                if (id) {
                    var url = '/web/color/editColor';
                } else {
                    var url = '/web/color/addColor';
                }
                form.form('submit', {
                    url: url,
                    queryParams: {
                        /* 数据 ID */
                        color_id: history._id,
                        /* 数据锁版本 */
                        lock_version: history._lockVersion
                    },
                    onSubmit: function (param) {
                        var isValid = $(this).form('validate');
                        if (isValid) {
                            target.linkbutton({disabled: true, iconCls: 'kbi-icon-loading'});
                        }
                        return isValid;
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        target.linkbutton({disabled: false, iconCls: 'icon-save'});
                        if (data.errcode == 0) {

                            $.h.index.setOperateInfo({
                                module: '颜色管理',
                                operate: id ? '修改颜色' : '添加颜色',
                                content: data.errmsg,
                                icon: 'hr-ok'
                            }, true);
                            $.h.window.winColorList.onAddNew();
                            $.h.window.winColorList.onRefresh();
                           // $('#win_base_color_grid').treegrid('reload');
                            return true;
                        }

                        $.h.index.setOperateInfo({
                            module: '颜色管理',
                            operate: history._id ? '修改颜色' : '添加颜色',
                            content: data.errmsg,
                            icon: 'hr-error'
                        }, true);
                    }
                });
            },
            onDelete: function () {
                var row = $('#win_base_color_grid').treegrid('getSelected');
                if (!row) {
                    parent.$.h.index.setOperateInfo({
                        module: '颜色管理',
                        operate: '删除颜色',
                        content: '请选择一行',
                        icon: 'hr-warn'
                    }, true);
                    return false;
                }
                $.messager.confirm('确认信息', '确定删除吗', function (r) {
                    if (!r) {
                        return false;
                    }
                    $.post(
                        '/web/color/delColor',
                        {
                            color_id: row.id,
                            lock_version: row.lock_version
                        },
                        function (data) {
                            if (data.errcode == 0) {
                                parent.$.h.index.setOperateInfo({
                                    module: '颜色管理',
                                    operate: '删除颜色',
                                    content: data.errmsg,
                                    icon: 'icon-ok'
                                }, true);
                                $.h.window.winColorList.onAddNew();
                                $.h.window.winColorList.onRefresh();
                            } else {
                                parent.$.h.index.setOperateInfo({
                                    module: '颜色管理',
                                    operate: '删除颜色',
                                    content: data.errmsg,
                                    icon: 'hr-error'
                                }, true);
                            }
                        });

                })

            },
            onDblClickRow: function (row) {
                let data = {
                    'color_id': row._parentId,
                    'color_name': row.text,
                    'color_sort': row.color_sort,
                    'color_group':row.color_group
                };
                history._id = row.id;
                history._lockVersion = row.lock_version;
                $("#win_base_color_form").form('load', data);
            },
            onLoadSuccess: function (row, data) {
                var tree = [{
                    'id': 0,
                    'color_group': 0,
                    'text': '顶级分类',
                    'children': data,
                }];
                $('#color_group').combotree('loadData', tree);
                $('#color_group').combotree('setValue', 0);
            },
            onRefresh: function () {
                $('#win_base_color_grid').treegrid('reload');
            },
            onAddNew: function () {
                history._id = '';
                history._lockVersion = 0;
                $("#win_base_color_form").form('reset');
                $('#color_group').combotree('setValue', 0);
                $("#color_sort").numberspinner('textbox').val(100);
            }
        },
        /*size窗口*/
        winBaseSize: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_base_size',
                    dictType: 'size',
                    mWinHref: $.toUrl('windows', 'size_list_center'),
                    mGrid: '#win_base_size_grid',
                    mForm: '#win_base_demo_form',
                    dictSort: '#size_sort'

                };
                dictOpen();
            },
            onClose: dictClose,
            onSave: function () {
                $('#win_base_size_form').form('submit', {
                    url: '/web/Size/updateSize',
                    onSubmit: function (param) {
                    },
                    success: function (res) {
                        res = JSON.parse(res);
                        if (res.errcode == 0) {
                            $.h.index.setOperateInfo({
                                module: '尺寸管理',
                                operate: '尺寸编辑',
                                content: res.errmsg,
                                icon: 'hr-ok'
                            }, true);
                            $('#win_base_size_grid').treegrid('reload');
                            $.h.window.winBaseSize.onAddNew();
                        } else {
                            $.h.index.setOperateInfo({
                                module: '尺寸管理',
                                operate: '尺寸编辑',
                                content: res.errmsg,
                                icon: 'hr-error'
                            }, true);
                        }
                    }
                });
            },
            onDelete: function () {
                let row = $('#win_base_size_grid').treegrid('getSelected');
                if (!row) {
                    parent.$.h.index.setOperateInfo({
                        module: '尺寸管理',
                        operate: '删除尺寸',
                        content: '请先选择一列',
                        icon: 'hr-warn'
                    }, true);
                    return false;
                }
                $.messager.confirm('删除尺寸', '确定删除该尺寸？', function (r) {
                    if (r) {
                            row.size_id = row.id;
                            $.post(
                                '/web/Size/deletSize',
                                row,
                                function (res) {
                                    parent.$.h.index.setOperateInfo({
                                        module: '尺寸管理',
                                        operate: '删除尺寸',
                                        content: res.errmsg,
                                        icon: res.errcode == 0 ? 'hr-ok' : 'hr-error'
                                    }, true);
                                    if (res.errcode == 0) {
                                        $('#win_base_size_grid').treegrid('reload');
                                    }
                                }
                            );
                            return true;
                    }
                })
            },
            onLoadSuccess: function (row,data) {
                // 尺寸目前仅保留2级
                var temp = [];
                for (let i=0; i<data.length;++i){
                    // 深拷贝
                    temp[i]= Object.assign({},data[i]);
                    temp[i].children = [];
                }
                let tree = [{
                    'id': 0,
                    'text': '顶级分类',
                    'children': temp,
                    'size_group': 0,
                }];
                $('#size_parent').combotree('loadData', tree);
                $('#size_parent').combotree('setValue', 0);
            },
            onDblClickRow: function (row) {
                row.size_id = row.id;
                row.size_name = row.text;
                $('#win_base_size_form').form('load', row);
            },
            onRefresh: function () {
                $('#win_base_size_grid').treegrid('reload');
            },
            onAddNew: function () {
                $('#win_base_size_form').form('reset');
                $('#size_parent').combobox('setValue', 0);
                $("#size_sort").numberspinner('textbox').val(100);
            }
        },
        /* 材质管理窗口 */
        winMaterialList: {

            onOpen: function () {
                dictConfig = {
                    mWin: '#win_material_list',
                    dictType: 'material',
                    mWinHref: $.toUrl('windows', 'material_list'),
                    mGrid: '#win_base_material_grid',
                    mForm: '#win_base_material_form',
                    dictSort: '#dict_sort'

                }
                dictOpen();
            },

            onClose: dictClose,
            onSave: dictSave,
            onDelete: dictDelete,
            onDblClickRow: dictDblClickRow,
            onRefresh: dictRefresh,
            onAddNew: dictAddNew

        },
        /*条码管理*/
        winBaseBarcode: {
            onOpen: function () {
                var obj = $('#win_base_barcode');
                var win = obj.window({
                    href: $.toUrl('windows', 'barcode_list_center'),
                    dataType: 'json'
                });
                win.window('open');
                $.get("/web/config/getBarcodeConfig", function (data) {
                    let res_data = data.data;
                    let form_data = [];

                    for (let i = 0; i < res_data.length; i++) {
                        if (res_data[i].config_name == "auto_generate" && res_data[i].config_value == "1") {
                            $("#win_base_barcode_form_auto_generate").switchbutton("check");
                        } else if (res_data[i].config_name == "auto_generate" && res_data[i].config_value == "0") {
                            $("#win_base_barcode_form_auto_generate").switchbutton("uncheck");
                        }
                        if (res_data[i].config_name == "item_type" && res_data[i].config_value == "1") {
                            $("#win_base_barcode_form_item_type").switchbutton("check");
                        } else if (res_data[i].config_name == "item_type" && res_data[i].config_value == "0") {
                            $("#win_base_barcode_form_item_type").switchbutton("uncheck");
                        }
                        form_data[res_data[i].config_name] = res_data[i].config_value;
                    }
                    $("#win_base_barcode_form").form("load", form_data);
                }, "json");
            },
            onSave: function (e) {
                var target = $(this);
                var form = $("#win_base_barcode_form");
                form.form('submit', {
                    url: "/web/config/barcodeConfig",
                    onSubmit: function () {
                        var isValid = $(this).form('validate');
                        if (isValid) {
                            target.linkbutton({disabled: true, iconCls: 'kbi-icon-loading'});
                        }
                        return isValid;
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        target.linkbutton({disabled: false, iconCls: 'icon-save'});
                        if (data.errcode == 0) {
                            $.h.index.setOperateInfo({
                                module: '条码管理',
                                operate: '保存',
                                content: data.errmsg,
                                icon: 'hr-ok'
                            }, true);
                            return;
                        }

                        $.h.index.setOperateInfo({
                            module: '条码管理',
                            operate: '保存',
                            content: data.errmsg,
                            icon: 'hr-warn'
                        }, true);
                    }
                });
            }
        },
        /* 商品分类窗口 */
        winCategoryList: {
            onOpen: function () {
                var obj = $('#win_category_list');
                var win = obj.window({
                    href: '/web/windows/category_list',
                    dataType: 'json',
                });
                win.window('open');
            },
            onClose: function () {
                $('#win_category_list').window('close');
            },
            onSave: function (e) {
                var form = $('#win_base_category_form');
                var target = $(this);
                if (history._id) {
                    var url = '/web/category/edit';
                } else {
                    var url = '/web/category/add';
                }
                form.form('submit', {
                    url: url,
                    queryParams: {
                        /* 数据 ID */
                        category_id: history._id,
                        /* 数据锁版本 */
                        lock_version: history._lockVersion
                    },
                    onSubmit: function () {
                        var isValid = $(this).form('validate');
                        if (isValid) {
                            target.linkbutton({disabled: true, iconCls: 'kbi-icon-loading'});
                        }
                        return isValid;
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        target.linkbutton({disabled: false, iconCls: 'icon-save'});
                        if (data.errcode == 0) {
                            $.h.window.winCategoryList.onAddNew();
                            $.h.window.winCategoryList.onRefresh();
                            $.h.index.setOperateInfo({
                                module: '商品分类',
                                operate: '保存',
                                content: data.errmsg,
                                icon: 'icon-ok'
                            }, true);
                            return;
                        }

                        $.h.index.setOperateInfo({
                            module: '商品分类',
                            operate: '保存',
                            content: data.errmsg,
                            icon: 'hr-warn'
                        }, true);
                    }
                });
            },
            onDelete: function () {
                $.messager.confirm('提示信息', '您想要删除该分类吗？', function (res) {
                    if (res) {
                        var row = $('#win_base_category_grid').treegrid('getSelected');
                        if (row) {
                            $.post(
                                '/web/category/delete',
                                {
                                    category_id: row.id,
                                    lock_version: row.lock_version
                                }
                                ,
                                function (data) {
                                    if (data.errcode == 0) {
                                        parent.$.h.index.setOperateInfo({
                                            module: '商品分类',
                                            operate: '删除信息',
                                            content: '删除成功',
                                            icon: 'icon-ok'
                                        }, true);
                                        $.h.window.winCategoryList.onAddNew();
                                        $.h.window.winCategoryList.onRefresh();
                                    } else {
                                        parent.$.h.index.setOperateInfo({
                                            module: '商品分类',
                                            operate: '删除信息',
                                            content: data.errmsg,
                                            icon: 'hr-warn'
                                        }, true);
                                    }

                                }
                            );
                        } else {
                            parent.$.h.index.setOperateInfo({
                                module: '商品分类',
                                operate: '删除信息',
                                content: '请选择一行',
                                icon: 'hjtr-warn'
                            }, true);
                        }
                    }
                });

            },
            onDblClickRow: function (row) {
                //alert(JSON.stringify(row));
                var data = {
                    'category_pid': row._parentId,
                    'category_name': row.text,
                    'category_sort': row.category_sort
                };
                history._id = row.id;
                history._lockVersion = row.lock_version;
                $('#win_base_category_form').form('load', data);
            },
            onLoadSuccess: function (row, data) {
                var tree = [{
                    'id': 0,
                    'category_pid': 0,
                    'text': '顶级分类',
                    'children': data
                }];
                $('#category_pid').combotree('loadData', tree);
                $('#category_pid').combotree('setValue', 0);
            },
            onRefresh: function () {
                $('#win_base_category_grid').treegrid('reload');
            },
            onAddNew: function () {
                history._id = '';
                history._lockVersion = 0;
                $('#win_base_category_form').form('reset');
                $('#category_sort').numberspinner('textbox').val(100);
            }
        },
        /* 品牌管理 */
        winBranchList: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_brand_list',
                    dictType: 'brand',
                    mWinHref: $.toUrl('windows', 'brand_list'),
                    mGrid: '#win_base_brand_grid',
                    mForm: '#win_base_brand_form',
                    dictSort: '#dict_sort'
                };
                dictOpen();
            },
            onClose: dictClose,
            onSave: dictSave,
            onDelete: dictDelete,
            onDblClickRow: dictDblClickRow,
            onRefresh: dictRefresh,
            onAddNew: dictAddNew

        },
        /*季节管理*/
        winBaseSeason: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_base_season',
                    dictType: 'season',
                    mWinHref: $.toUrl('windows', 'season_list_center'),
                    mGrid: '#win_base_season_grid',
                    mForm: '#win_base_season_form',
                    dictSort: '#barcode_sort'
                };
                dictOpen();
            },
            onClose: dictClose,
            onSave: dictSave,
            onDelete: dictDelete,
            onDblClickRow: dictDblClickRowNew,
            onRefresh: dictRefresh,
            onAddNew: dictAddNew
        },
        /* 单位管理窗口 */

        winUnitList: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_unit_list',
                    dictType: 'unit',
                    mWinHref: $.toUrl('windows', 'unit_list'),
                    mGrid: '#win_base_unit_grid',
                    mForm: '#win_base_unit_form',
                    dictSort: '#dict_sort'
                };

                dictOpen();
            },
            onClose: dictClose,
            onSave: dictSave,
            onDelete: dictDelete,
            onDblClickRow: dictDblClickRow,
            onRefresh: dictRefresh,
            onAddNew: dictAddNew

        },
        /*款式管理*/
        winBaseStyle: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_base_style',
                    dictType: 'style',
                    mWinHref: $.toUrl('windows', 'style_list_center'),
                    mTreeGrid: '#win_base_style_grid',
                    mForm: '#win_base_style_form',
                    dictSort: '#style_sort'
                };
                var obj = $(dictConfig.mWin);
                var win = obj.window({
                    href: dictConfig.mWinHref,
                    dataType: 'json'
                });
                win.window('open');

            },
            onLoadSuccess: function (row, data) {
                var tree = [{
                    'id': 0,
                    'pid': 0,
                    'text': '无上级分类',
                    'children': data
                }];
                $('#style_select').combotree('loadData', tree);
                $('#style_select').combotree('setValue', 0);
            },
            onClose: dictClose,
            onSave: dictSave,
            //onDelete: dictDelete,
            onDelete: dictDelete,

            onDblClickRow: function (row) {
                var data = {
                    'dict_pid': row._parentId,
                    'dict_name': row.text
                };
                history._id = row.id;
                history._lockVersion = row.lock_version;
                $(dictConfig.mForm).form('load', data);
            },
            onRefresh: dictRefresh,
            onAddNew: dictAddNew
        },
        /* 账目类型 */
        winAccountList: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_account_list',
                    dictType: 'demo',
                    mWinHref: $.toUrl('windows', 'account_list'),
                    mGrid: '#win_base_demo_grid',
                    mForm: '#win_base_demo_form',
                    dictSort: '#dict_sort',
                    dict_id: 'color_id',
                };
                dictOpen();
            },
            onClose: dictClose,
            onSave: dictSave,
            onDelete: dictDelete,
            onDblClickRow: dictDblClickRowNew,
            onRefresh: dictRefresh,
            onAddNew: dictAddNew
        },
        /*颜色尺码*/
        winColorSize: {
            open: function (_loadFun, _closeFun) {
                var obj = $('#win_color_size');
                obj.window({
                    dataType: 'json',
                    href: $.toUrl('windows', 'color_size'),
                    onLoad: function () {
                        if (!_loadFun) {
                            alert('请配置保存事件~~');
                        }
                        var tag = $('#current_number_grid').datagrid('options');
                        tag.headerEvents.click = function (e) {
                            var field = $(e.target).closest(".datagrid-cell").parent().attr("field");
                            if (field) {
                                if ($(e.target).closest(".datagrid-cell").parent().attr('style')) {
                                    $(e.target).closest(".datagrid-cell").parent().removeAttr('style');
                                    tag.strColumns = tag.strColumns.replace('{' + field + '}', '');
                                } else {
                                    $(e.target).closest(".datagrid-cell").parent().css({
                                        "color": "#ffffff",
                                        "backgroundColor": "#999999"
                                    });
                                    tag.strColumns += '{' + field + '}';
                                }
                            }
                        };

                        _loadFun();
                    },
                    onClose: _closeFun ? _closeFun : function () {
                        alert('请配置关闭事件~~');
                    }
                }).window('open');
            },
            close: function () {
                $('#win_color_size').window('close');
            },
            setTitle: function (title) {
                $('#win_color_size').window('setTitle', title);
            }
        },
        /*客户分类*/
        winBaseClient: {
            onOpen: function () {
                var obj = $('#win_base_client');
                var win = obj.window({
                    href: '/manage/dict/client',
                    dataType: 'json',
                    onLoad: function () {
                        /* 设置字典类别 */
                        history._dictType = 'client';

                        $('#win_base_client_grid').datagrid({
                            queryParams: {dict_type: history._dictType},
                            url: '/manage/dict/loadList'
                        });
                    }
                });
                win.window('open');
            },
            onClose: function () {
                $('#win_base_client').window('close');
            },
            onSave: function (e) {
                var form = $('#win_base_client_form');
                var target = $(this);
                form.form('submit', {
                    url: '/manage/dict/savedict',
                    queryParams: {
                        /* 数据 ID */
                        dict_id: history._id,
                        /* 数据类型 */
                        dict_type: history._dictType,
                        /* 数据锁版本 */
                        lock_version: history._lockVersion
                    },
                    onSubmit: function () {
                        target.linkbutton({disabled: true, iconCls: 'kbi-icon-loading'});
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        if (data.errcode == 0) {
                            $.h.window.winBaseClient.onAddNew();
                            $.h.window.winBaseClient.onRefresh();
                        }
                        target.linkbutton({disabled: false, iconCls: 'icon-save'});
                        parent.$.h.index.setOperateInfo({
                            module: '条码',
                            operate: '条码管理',
                            content: data.errmsg,
                            icon: 'hr-warn'
                        }, true);

                    }
                });
            },
            onDelete: function () {
                var row = $('#win_base_client_grid').treegrid('getSelected');
                if (row) {
                    $.post(
                        '/manage/dict/delDict',
                        {dict_id: row.dict_id},
                        function (data) {
                            if (data.errcode == 0) {
                                $.h.window.winBaseClient.onAddNew();
                                $.h.window.winBaseClient.onRefresh();
                            }
                            $.h.c.warnMessager(data.errmsg);
                        }
                    );
                } else {
                    $.h.c.warnMessager('请选择需要操作的行~~');
                }
            },
            onDblClickRow: function (index, row) {
                //alert(JSON.stringify(row));
                var data = {
                    'dict_name': row.dict_name,
                    'dict_sort': row.dict_sort
                };
                history._id = row.dict_id;
                history._lockVersion = row.lock_version;
                $('#win_base_client_form').form('load', data);
            },
            onRefresh: function () {
                $('#win_base_client_grid').datagrid('reload');
            },
            onNew: function () {
                history._id = '';
                history._lockVersion = 0;
                $('#win_base_client_form').form('reset');
                $('#dict_sort').numberspinner('textbox').val(100);
            },
        },
        /*账目类型*/
        winBaseAccountType: {
            onOpen: function () {
                dictConfig = {
                    mWin: '#win_base_account_type',
                    dictType: 'account',
                    mWinHref: $.toUrl('windows', 'account_type'),
                    mGrid: '#win_base_account_grid',
                    mForm: '#win_base_account_form',
                    dictSort: '#dict_sort'
                };
                dictOpen();
            },
            onClose: dictClose,
            onSave: dictSave,
            onDelete: dictDelete,
            onDblClickRow: dictDblClickRow,
            onRefresh: dictRefresh,
            onAddNew: dictAddNew
        },
        /*个人信息*/
        winBaseUserInfo: {
            onOpen: function () {
                var obj = $('#win_base_user_info');
                var win = obj.window({
                    href: $.toUrl('windows', 'user_info'),
                    dataType: 'json',
                    onLoad: function () {

                    }
                });
                win.window('open');
            },
            onClose: function () {
                $('#win_base_user_info').window('close');
            },
            resetPassword: function () {
                // $('#win_base_user_info').window('close');
                $.h.window.winResetPassword.onOpen();
            }
        },
        /*重置密码*/
        winResetPassword: {
            onOpen: function () {
                var obj = $('#win_base_reset_password');
                var win = obj.window({
                    href: $.toUrl('windows', 'reset_password'),
                    dataType: 'json',
                    onLoad: function () {

                    }
                });
                win.window('open');
            },
            onSave: function () {
                $('#win_base_reset_password').window('close');
            },

        },
        /*组织机构*/
        winBaseOrg: {
            onOpen: function () {
                var obj = $('#win_base_organization');
                var win = obj.window({
                    href: $.toUrl('windows', 'org_window'),
                    dataType: 'json',
                    onLoad: function () {

                    }
                });
                win.window('open');
            },
            onClose: function () {
                $('#win_base_organization').window('close');
            },
            onSave: function (e) {
                var form = $('#win_base_org_form');
                var target = $(this);
                var org_status = $('#org_status').switchbutton('options').checked ? 1 : 0;
                form.form('submit', {
                    url: '/web/organization/saveorg',
                    queryParams: {
                        /* 数据 ID */
                        org_id: history._id,
                        org_status: org_status,
                        /* 数据锁版本 */
                        lock_version: history._lockVersion
                    },
                    onSubmit: function () {
                        var isValid = $(this).form('validate');
                        if (isValid) {
                            target.linkbutton({disabled: true, iconCls: 'kbi-icon-loading'});
                        }
                        return isValid;
                    },
                    success: function (data) {
                        data = $.parseJSON(data);
                        if (data.errcode == 0) {
                            $.h.window.winBaseOrg.onAddNew();
                            $.h.window.winBaseOrg.onRefresh();
                        }
                        target.linkbutton({disabled: false, iconCls: 'icon-save'});
                        parent.$.h.index.setOperateInfo({
                            module: '组织机构',
                            operate: '组织机构管理',
                            content: data.errmsg,
                            icon: 'hr-ok'
                        }, true);
                    }
                });
            },
            onDelete: function () {
                var row = $('#win_base_org_grid').treegrid('getSelected');
                if (row) {
                    $.post(
                        '/web/organization/delorg',
                        {org_id: row.id},
                        function (data) {
                            if (data.errcode == 0) {
                                $.h.window.winBaseOrg.onAddNew();
                                $.h.window.winBaseOrg.onRefresh();
                            }
                            parent.$.h.index.setOperateInfo({
                                module: '组织机构',
                                operate: '组织机构管理',
                                content: data.errmsg,
                                icon: 'hr-ok'
                            }, true);
                        }
                    );
                } else {
                    parent.$.h.index.setOperateInfo({
                        module: '组织机构',
                        operate: '组织机构管理',
                        content: data.errmsg,
                        icon: 'hr-warn'
                    }, true);
                }
            },
            onRefresh: function () {
                $('#win_base_org_grid').treegrid('reload');
            },
            onDblClickRow: function (row) {
                //alert(JSON.stringify(row));
                var data = {
                    'org_pid': row.org_pid==0 ?'':row.org_pid,
                    'org_name': row.text,
                    'org_type': row.org_type,
                    'org_head': row.org_head,
                    'org_phone': row.org_phone,
                    'org_sort': row.sort
                };
                history._id = row.id;
                history._lockVersion = row.lock_version;
                $('#win_base_org_form').form('load', data);
                $('#org_status').switchbutton(row.org_status == 1 ? 'check' : 'uncheck');
            },
            onAddNew: function () {
                history._id = '';
                history._lockVersion = 0;
                $('#org_type').combobox('reset');
                $('#win_base_org_form').form('reset');
                $('#org_sort').numberspinner('textbox').val(100);
            }
        },
        SystemAlert: function () {
            $.messager.prompt('系统重置', '请输入登录账户密码', function (r) {
            });
        },
        /*系统参数设置*/
        winBaseSystemParameter: {
            onOpen: function () {
                var obj = $('#win_base_system_parameter');
                var win = obj.window({
                    href: $.toUrl('windows', 'system_parameter'),
                    dataType: 'json',
                    onLoad: function () {

                    }
                });
                win.window('open');
            },
            onClose: function () {
                $('#win_base_system_parameter').window('close');
            },
            resetPassword: function () {
                $.h.window.winResetPassword.onOpen();
            }
        }
    }

    function dictOpen() {
        var obj = $(dictConfig.mWin);
        var win = obj.window({
            href: dictConfig.mWinHref,
            dataType: 'json',
            onLoad: function () {
                /* 设置字典类别 */
                history._dictType = dictConfig.dictType;

                $(dictConfig.mGrid).datagrid({
                    queryParams: {dict_type: history._dictType},
                    url: $.toUrl('dict', 'loadList')
                });

            }
        });
        win.window('open');
    }

    function dictClose() {
        $(dictConfig.mWin).window('close');
    }

    function dictSave(e) {
        var form = $(dictConfig.mForm);
        var target = $(this);
        if (history._id) {
            var url = '/web/dict/edit';
        } else {
            var url = '/web/dict/add';
        }
        form.form('submit', {
            url: url,
            queryParams: {
                /* 数据锁版本 */
                lock_version: history._lockVersion,
                dict_id: history._id,
                dict_type: dictConfig.dictType
            },
            onSubmit: function () {
                target.linkbutton({disabled: true, iconCls: 'kbi-icon-loading'});
            },
            success: function (data) {
                data = $.parseJSON(data);
                if (data.errcode == 0) {
                    dictAddNew();
                    dictRefresh();
                }
                target.linkbutton({disabled: false, iconCls: 'icon-save'});

                parent.$.h.index.setOperateInfo({
                    module: '字典数据',
                    operate: '添加信息',
                    content: data.errcode == 0 ? '操作成功' : data.errmsg,
                    icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                }, true);
            }
        });
    }

    function dictDelete() {
        if (dictConfig.mGrid) {
            var row = $(dictConfig.mGrid).treegrid('getSelected');
        } else if (dictConfig.mTreeGrid) {
            var row = $(dictConfig.mTreeGrid).treegrid('getSelected');
        }
        if (!row) {
            parent.$.h.index.setOperateInfo({
                module: '字典数据',
                operate: '删除字典数据',
                content: '请选择需要操作的行~~',
                icon: 'hr-error'
            }, true);
            return false;
        }
        $.messager.confirm('确认信息', '确定删除该数据吗', function (r) {
            if (!r) {
                return;
            }
            if (row.dict_disabled == 1) {
                parent.$.h.index.setOperateInfo({
                    module: '字典数据',
                    operate: '删除信息',
                    content: '此项不可删除~~',
                    icon: 'hr-warn'
                }, false);
                return;
            }

            if (row) {
                $.post(
                    '/web/dict/delete',
                    {
                        dict_id: row.id ? row.id : row.dict_id,
                        lock_version: row.lock_version,
                    },
                    function (data) {
                        if (data.errcode == 0) {
                            dictAddNew();
                            dictRefresh();
                        }
                        parent.$.h.index.setOperateInfo({
                            module: '字典数据',
                            operate: '删除信息',
                            content: data.errmsg,
                            icon: data.errcode == 0 ? 'icon-ok' : 'hr-error'
                        }, true);
                        parent.$.h.index.setOperateInfo({
                            module: '字典数据',
                            operate: '删除信息',
                            content: data.errcode == 0 ? '操作成功' : data.errmsg,
                            icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                        }, true);
                    }
                );
            }
        })

    }

    function dictDblClickRow(index, row) {
        if (row.dict_disabled == 1) {
            parent.$.h.index.setOperateInfo({
                module: '字典数据',
                operate: '编辑信息',
                content: '此项不可修改~~',
                icon: 'hr-warn'
            }, false);
            return;
        }
        history._id = row.dict_id;
        history._lockVersion = row.lock_version;
        $(dictConfig.mForm).form('load', row);
    }

    // 双击行新增加方法
    function dictDblClickRowNew(index, row) {

        if (row.dict_disabled == 1) {
            parent.$.h.index.setOperateInfo({
                module: '字典数据',
                operate: '编辑信息',
                content: '此项不可修改~~',
                icon: 'hjtr-warn'
            }, false);
            return;
        }
        history._id = row.dict_id;
        history._lockVersion = row.lock_version;
        $(dictConfig.mForm).form('load', row);
    }

    function dictRefresh() {
        if (dictConfig.mGrid) {
            $(dictConfig.mGrid).datagrid('reload');
        } else if (dictConfig.mTreeGrid) {
            $(dictConfig.mTreeGrid).treegrid('reload');
        }
    }

    function dictAddNew() {
        history._id = '';
        history._lockVersion = 0;
        $(dictConfig.mForm).form('reset');
        $(dictConfig.dictSort).numberspinner('setValue', 100);
    }

    function colorDelete() {
        var row = $(dictConfig.mGrid).treegrid('getSelected');
        if (row) {
            $.post(
                '/web/color/delColor',
                {color_id: row.color_id},
                function (data) {
                    if (data.errcode == 0) {
                        dictRefresh();
                        colorAddNew();
                        let url = '/web/color/loadColorGroup';
                        $("#color_group").combobox('reload', url);
                        parent.$.h.index.setOperateInfo({
                            module: '颜色管理',
                            operate: '删除信息',
                            content: data.errmsg,
                            icon: data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
                        }, true);
                    }
                    parent.$.h.index.setOperateInfo({
                        module: '颜色管理',
                        operate: '删除信息',
                        content: data.errmsg,
                        icon: data.errcode == 0 ? 'hr-ok' : 'hr-error'
                    }, true);
                }
            );
        } else {
            $.h.c.warnMessager('请选择需要操作的行~~');
        }
    }

    function colorAddNew() {
        history._id = '';
        history._lockVersion = 0;
        $(dictConfig.mForm).form('reset');
        $(dictConfig.colorSort).numberspinner('setValue', 1);
    }
})(jQuery);