/**
 * 字典函数
 */
(function($){
	/*供应商*/
	$.h.dict = {
		save : function(e) {
			var form = $('#supplier_form');
			var target = $(this);
			var supplier_status = $('#supplier_status').switchbutton('options').checked ? 1 : 0;
			form.form('submit', {    
				url : '/Manage/Dictionary/SupplierSave',
				queryParams : {
					supplier_status:supplier_status
				},
				onSubmit : function() {
					var isValid = form.form('validate');
					if (isValid){
						target.linkbutton({disabled:true,iconCls:'kbi-icon-loading'});
					}
					return isValid;
				},    
				success : function(data) {
					data = $.parseJSON(data);
					if (data.errcode == 0) {
						$.h.dict.refresh();
						$.h.dict.reset();
					}
					target.linkbutton({disabled:false,iconCls:'icon-save'});
					$.h.c.warnMessager(data.errmsg);
				}
			});
		},
		delete : function() {
			var row = $('#win_base_color_grid').treegrid('getSelected');
			if (row) {
				$.post(
					'/Manage/Dictionary/ColorDel',
					{id:row.id},
					function(data) {
						if (data.errcode == 0) {
							$.h.window.winBaseColor.refresh();
						}
						$.h.c.warnMessager(data.errmsg);
					}
				);
			} else {
				$.h.c.warnMessager('请选择需要操作的行~~');
			}
		},
		refresh : function() {
			$('#supplier_grid').datagrid('reload');
		},
		reset : function() {
            $('#supplier_form').form('reset');
			$('#supplier_default_discount').numberspinner('textbox').val(100);
			$('#supplier_sort').numberspinner('textbox').val(0);
        }
	}
})(jQuery);