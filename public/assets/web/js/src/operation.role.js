/**
 * 角色管理函数
 */
(function($) {
    /**
	 * 声明主窗口 ID
	 */
	var id_RoleTree = '#role_treegrid';
    var id_DataTree = '#role_datagrid';

    /**
     * 主要的
     */
    $.h.role = {
        /**
         * 页面加载
         */
        pageLoad : function(e) {
            
        },
		/**
         * 保存角色的菜单、权限节点信息
         */
        submit : function() {
            if (!$('#role_name').textbox('isValid')) {
                $.h.c.warnMessager('角色名称不能为空~~');
                return;
            }
            var id = $('#role_id').val();
            var name = $('#role_name').textbox('getValue');
            var annotate = $('#role_annotate').textbox('getValue');
            var data = $(id_RoleTree).treegrid('getData');
            $.ajax({
                url: '/Manage/Role/NodeSave',
                data: {
                    id : id,
                    name : name,
                    annotate : annotate,
                    data : JSON.stringify(data)
                },
                type:'post',
                cache:false,
                dataType:'json',
                beforeSend: function(xhr) {
                    $.messager.progress();
                },
                success: function(data) {
                    if (data.errcode == 0) {
                        $(id_DataTree).datagrid('reload');
                        $('#role_id').val('');
                        $('#role_name').textbox('setValue','');
                        $('#role_annotate').textbox('setValue','');
                        $(id_RoleTree).treegrid('clearChecked');
                    }
                    $.h.c.warnMessager(data.errmsg);
                },
                complete: function() {
                    $.messager.progress('close');
                }
            });
        },
		/**
         * 新建角色
         */
        newRole : function() {
            $('#role_id').val('');
            $('#role_name').textbox('setValue','');
            $('#role_annotate').textbox('setValue','');
            $(id_RoleTree).treegrid('clearChecked');
            $.h.c.warnMessager('清空数据成功~~');
        },
		/**
         * 双击编辑角色
         */
        onDblClickRow : function(index, row) {
            //alert(JSON.stringify($(id_RoleTree).treegrid('getCheckedNodes')));
            //$(id_RoleTree).treegrid('clearChecked');
            //return;
            var target = $(id_RoleTree);
            $.ajax({
                url: '/Manage/Role/LoadRoleNodeRows',
                data: {id : row.id},
                type:'post',
                cache:false,
                dataType:'json',
                beforeSend: function(xhr) {
                    target.treegrid('loading');
                },
                success: function(data) {
                    if (data.errcode == 0) {
                        $('#role_id').val(data.id);
                        $('#role_name').textbox('setValue',data.role_name);
                        $('#role_annotate').textbox('setValue',data.role_annotate);
                        target.treegrid('clearChecked');
						data.rows.forEach(function(str) {
							if (str) {
								target.treegrid('checkNode',parseInt(str));
							}
						});
                    }
                    $.h.c.warnMessager(data.errmsg);
                },
                complete: function() {
                    target.treegrid('loaded');
                }
            });
        },
		/**
         * 模型控制器方法
         */
		module : {
			save : function() {
				var form = $('#module_form');
                var target = $(this);
				var rows = $('#module_treegrid').treegrid('getSelections');
				var node_code = node_name = '';
				
				if (rows.length) {
					for(var index in rows) {
						if (rows[index].node_category != 0) {
							$.h.c.warnMessager('您选择的<b>['+rows[index].node_code+']</b>不是权限节点~~');
							return;
						}
						node_code += (node_code ? ',' : '') + rows[index].node_code;
						node_name += (node_name ? ',' : '') + rows[index].node_name;
					}
				} else {
					node_code = node_name = '*';
				}
                form.form('submit', {    
                    url : '/Manage/Role/ModuleSave',
                    queryParams : {node_code:node_code,node_name:node_name},
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
                            form.form('reset');
                            $('#module_treegrid').treegrid('unselectAll');
                            $('#module_datagrid').datagrid('reload');
                        }
                        target.linkbutton({disabled:false,iconCls:'icon-save'});
                        $.h.c.warnMessager(data.errmsg);
                    }
                });
			},
			reset : function() {
				$('#module_form').form('reset');
				$('#module_id').val('');
				$('#module_treegrid').treegrid('unselectAll');
			},
			onDblClickRow : function(index, row) {
				$('#module_form').form('load',row);
				if (row.node_code == '*') {
					$('#module_treegrid').treegrid('unselectAll');
					return;
				}
				$('#module_treegrid').treegrid('unselectAll');
				var nodes = row.node_code.split(',');
				for(var index in nodes) {
					$('#module_treegrid').treegrid('select',nodes[index]);
				}
			},
			onSearch : function() {
				
			}
		},
        test : function() {
            alert(JSON.stringify($('#role_form').treegrid('getData')));
        }
    }
})(jQuery);