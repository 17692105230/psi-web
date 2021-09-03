/**
 * 结算账户管理
 */
(function($) {
    var history = {
		_id : '',
        _lockVersion : 0
	};

    $.h.settlement = {
        onSave : function(e) {
            var form = $('#settlement_form');
            var target = $(this);
            var settlement_status = $('#settlement_status').switchbutton('options').checked ? 1 : 0;
            form.form('submit', {    
                url : '/manage/settlement/saveSettlement',
                queryParams : {
                    settlement_id:history._id,
                    lock_version:history._lockVersion
                },
                onSubmit : function() {
					isValid = form.form('validate');
					if (!isValid) {
						parent.$.h.index.setOperateInfo({
							module:'结算账户',
							operate:'保存信息',
							content:'数据验证失败~~',
							icon:'hjtr-warn'
						}, false);
						return false;
					}
                    $.messager.progress({title:'请稍等',msg:'提交数据中...'});
                },    
                success : function(data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $.h.settlement.onRefresh();
                        $.h.settlement.onReset();
                    }
                    $.messager.progress('close');
                    parent.$.h.index.setOperateInfo({
						module:'结算账户',
						operate:'保存信息',
						content:data.errmsg,
						icon:data.errcode == 0 ? 'icon-ok' : 'hjtr-error'
					}, true);
                }
            });
        },
        onDblClickRow : function(rowIndex, rowData) {
            var settlementId = rowData ? rowData.settlement_id : rowIndex;

            $.ajax({
				url: '/manage/settlement/loadsettlementInfo',
				type:'get',
				cache:false,
				dataType:'json',
				data: {settlement_id:settlementId},
				beforeSend: function(xhr) {
					$.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
				},
				success: function(data) {
                    //data = $.parseJSON(data);
                    /* 加载表单数据 */
					$('#settlement_form').form('load', data.settlement);
                    
                    history._id = data.settlement.settlement_id;
                    history._lockVersion = data.settlement.lock_version;
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
                        url: '/manage/settlement/delsettlement',
                        type:'get',
                        cache:false,
                        dataType:'json',
                        data:{
                            settlement_id:id
                        },
                        beforeSend: function(xhr) {
                            $.messager.progress({title:'请稍等',msg:'提交数据中...'});
                        },
                        success: function(data) {
                            if (data.errcode == 0) {
                                $.h.settlement.onRefresh();
                                $.h.settlement.onReset();
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
            $('#settlement_grid').datagrid('reload');
        },
        onReset : function() {
            history._id = '';
            history._lockVersion = 0;

            $('#settlement_form').form('reset');
            $('#settlement_sort').numberspinner('textbox').val(100);
        },
        delFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" onclick="$.h.settlement.onDelete(\''+row.settlement_id+'\');" class="datagrid-row-del" style="display:inline-block;width:16px;height:16px;"></a>';
        },
        modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" onclick="$.h.settlement.onDblClickRow(\''+row.settlement_id+'\');" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        }
    };
})(jQuery);