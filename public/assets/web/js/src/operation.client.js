/**
 * 客户管理函数
 */
(function($) {
    var history = {
		_id : '',
        _lockVersion : 0
	}

    /**
     * 主要的
     */
    $.h.client = {
        /**
         * 页面加载
         */
        load : function(e) { },
        /**
         * 新增客户事件
         */
        onAddNew : function() {
            history._id = '';
			history._lockVersion = 0;
            $('#client_tabs').tabs('select',0);
            $('#client_from').form('reset');
            $('#client_discount').numberspinner('textbox').val(100);
        },
        /**
         * 保存客户基本信息
         */
        onSave : function() {
            $('#client_from').form('submit', {
                url:'/web/client/saveclient',
                novalidate:true,
                queryParams:{
                    client_id:history._id,
                    lock_version:history._lockVersion
                },
                onSubmit: function(param) {
                    $.messager.progress({title:'请稍等',msg:'提交数据中...'});
                },
                success:function(data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $.h.client.onAddNew();
                        $('#client_grid').datagrid('reload');
                    }
                    $.messager.progress('close');
                    $.h.c.warnMessager(data.errmsg);
                }
            });
        },
        onDelete : function(id) {
            $.messager.confirm('警告', '您确认要删除数据吗？该操作不可恢复~~', function(isRen) {
                if (isRen) {
                    $.ajax({
                        url: '/web/client/delClient',
                        type:'get',
                        cache:false,
                        dataType:'json',
                        data:{
                            client_id:id
                        },
                        beforeSend: function(xhr) {
                            $.messager.progress({title:'请稍等',msg:'提交数据中...'});
                        },
                        success: function(data) {
                            if (data.errcode == 0) {
                                $.h.client.onAddNew();
                                $.h.client.onRefresh();
                            }
                        },
                        complete: function() {
                            $.messager.progress('close');
                        }
                    });
                }
            });
        },
        /**
         * 会员列表行单击事件
         */
        onDblClickRow : function(rowIndex, rowData) {
            $.ajax({
                url: '/web/client/loadData',
                type:'get',
                cache:false,
                dataType:'json',
                data:{
                    client_id:rowData.client_id
                },
                beforeSend: function(xhr) {
                    $.messager.progress({title:'请稍等',msg:'提交数据中...'});
                },
                success: function(data) {
                    if (data.errcode == 0) {
                        history._id = data.data.client_id;
			            history._lockVersion = data.data.lock_version;
                        $('#client_from').form('load',data.data);
                        $('#client_discount').numberspinner('textbox').val(data.data.client_discount);
                    }
                },
                complete: function() {
                    $.messager.progress('close');
                }
            });
        },
		/**
		 * 单元格单击单据列表事件
		 */
		onClickCell : function(index, field, value, row) {
			if (field == 'client_id1') {
				$.h.client.onDblClickRow(index,row);
			} else if (field == 'client_id2') {
				$.h.client.onDelete(row.client_id);
			}
		},
        onRefresh : function() {
            $('#client_grid').datagrid('reload');
        },
        delFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-del" style="display:inline-block;width:16px;height:16px;"></a>';
        },
        modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    }
})(jQuery);