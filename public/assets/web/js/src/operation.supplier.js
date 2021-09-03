/**
 * 供应商管理
 */
(function($) {
    var history = {
		_id : '',
        _lockVersion : 0
	};

    $.h.supplier = {
        onSave : function(e) {
            var form = $('#supplier_form');
            var target = $(this);
            var supplier_status = $('#supplier_status').switchbutton('options').checked ? 1 : 0;

            if (history._id) {
                var url = '/web/supplier/updateSupper'
            } else {
                var url ='/web/supplier/supplierSave';
            }
            form.form('submit', {    
                url : url,
                queryParams : {
                    supplier_id:history._id,
                    lock_version:history._lockVersion,
                    status:supplier_status
                },
                onSubmit : function() {
                    $.messager.progress({title:'请稍等',msg:'提交数据中...'});
                },    
                success : function(data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $.h.supplier.onRefresh();
                        $.h.supplier.onReset();
                    }
                    $.messager.progress('close');
                    $.h.c.warnMessager(data.errmsg);
                }
            });
        },
        onDblClickRow : function(rowIndex, rowData) {
            var supplierId = rowData ? rowData.supplier_id : rowIndex;

            $.ajax({
				url: '/web/supplier/loadSupperInfo',
				type:'get',
				cache:false,
				dataType:'json',
				data: {supplier_id:supplierId},
				beforeSend: function(xhr) {
					$.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
				},
				success: function(data) {
                    //data = $.parseJSON(data);
                    /* 加载表单数据 */
					$('#supplier_form').form('load', data.data);
                    
                    history._id = data.data.supplier_id;
                    history._lockVersion = data.data.lock_version;
				},
				complete: function() {
					$.messager.progress('close');
				}
			});
        },
        onDelete : function(id) {
            $.messager.confirm('警告', '您确认要删除数据吗？该操作不可恢复~~', function(isRun){
                if (isRun) {
                    $.ajax({
                        url: '/web/supplier/delSupplier',
                        type:'get',
                        cache:false,
                        dataType:'json',
                        data:{
                            supplier_id:id
                        },
                        beforeSend: function(xhr) {
                            $.messager.progress({title:'请稍等',msg:'提交数据中...'});
                        },
                        success: function(data) {
                            if (data.errcode == 0) {
                                $.h.supplier.onRefresh();
                                $.h.supplier.onReset();
                            }
                        },
                        complete: function() {
                            $.messager.progress('close');
                        }
                    });
                }
            });
            
        },
        onRefresh : function() {
            $('#supplier_grid').datagrid('reload');
        },
        onReset : function() {
            history._id = '';
            history._lockVersion = 0;

            $('#supplier_form').form('reset');
            $('#supplier_discount').numberspinner('textbox').val(100);
            $('#supplier_sort').numberspinner('textbox').val(100);
        },
        delFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" onclick="$.h.supplier.onDelete(\''+row.supplier_id+'\');" class="datagrid-row-del" style="display:inline-block;width:16px;height:16px;"></a>';
        },
        modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" onclick="$.h.supplier.onDblClickRow(\''+row.supplier_id+'\');" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        },
        onQuery : function() {
            var condition = {};
            if ($(this).attr('query') == '1') {
                var supplier_name_query = $.trim($('#supplier_name_query').textbox('getValue'));
                var supplier_director_query = $.trim($('#supplier_director_query').textbox('getValue'));
                var supplier_status_query = $.trim($('#supplier_status_query').combobox('getValue'));
                condition.supplier_name = supplier_name_query;
                condition.supplier_director = supplier_director_query;
                condition.supplier_status = supplier_status_query;
            }

            $('#supplier_grid').datagrid('load', condition);
        }
    };
})(jQuery);