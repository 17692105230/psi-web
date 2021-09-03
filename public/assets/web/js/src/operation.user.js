/**
 * 员工管理函数
 */
(function($) {
    /**
	 * 声明主窗口 ID
	 */
	var id_UserOrganizationGrid = '#user_organization_grid';
	var id_UserInfoForm = '#user_info_form';
    var id_UserGrid = '#user_grid';
    /**
     * 主要的
     */
    $.h.user = {
        /**
         * 页面加载
         */
        pageLoad : function(e) {
            
        },
		/**
         * 保存用户信息
         */
		saveUserInfo : function(e) {
			//var user_organization_code = $('#user_organization_code').combotree('getValue');
			var user_organization_name = $('#user_organization_code').combotree('getText');
			//var user_code = $('#user_code').textbox('getValue');
			//var user_name = $('#user_name').textbox('getValue');
			//var user_login_password = $('#user_login_password').combotree('getValue');
			var user_allow_login = $('#user_allow_login').switchbutton('options').checked ? 1 : 0;
			//var user_store_code = $('#user_store_code').combotree('getValue');
			var user_store_name = $('#user_store_code').combotree('getText');
			var user_allow_view = $('#user_allow_view').switchbutton('options').checked ? 1 : 0;
			var user_is_sales = $('#user_is_sales').switchbutton('options').checked ? 1 : 0;
			//var user_idcode = $('#user_idcode').textbox('getValue');
			//var user_entry_time = $('#user_entry_time').datebox('getValue');
			//var user_describe = $('#user_describe').textbox('getValue');
			var user_role = '';
			var _rows = $('#user_role').datalist('getSelections');
            _rows.forEach(function(row){  
                user_role += (user_role ? ',' : '') + row.role_code;
            });
			//alert(user_allow_login);
            //return;
			$(id_UserInfoForm).form('submit', {
				url:'/Manage/User/UserInfoSave',
				queryParams:{
					user_organization_name:user_organization_name,
					user_allow_login:user_allow_login,
					user_store_name:user_store_name,
					user_allow_view:user_allow_view,
					user_is_sales:user_is_sales,
					user_role:user_role
				},
				onSubmit: function(param) {
					var isValid = $(id_UserInfoForm).form('validate');
					if (isValid) { $.messager.progress(); }
					return isValid;
				},
				success:function(data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $.H.user.reloadUserGrid();
                        $.H.user.resetUserForm();
                    }
					$.messager.progress('close');
                    $.H.C.warnMessager(data.errmsg);
				}
			});  

		},
        onDblClickUserGrid : function(index,row) {
            $(id_UserInfoForm).form('load',row);
            if (row.user_allow_login == 1) {
                $('#user_allow_login').switchbutton('check');
            } else {
                $('#user_allow_login').switchbutton('uncheck');
            }
            if (row.user_allow_view == 1) {
                $('#user_allow_view').switchbutton('check');
            } else {
                $('#user_allow_view').switchbutton('uncheck');
            }
			if (row.user_is_sales == 1) {
                $('#user_is_sales').switchbutton('check');
            } else {
                $('#user_is_sales').switchbutton('uncheck');
            }
            $('#user_role').datalist('unselectAll');
            var rows = $('#user_role').datalist('getRows');
            $.each(rows, function(i, r) {
                if (row.user_role.indexOf(r.role_code) >= 0) {
                    $('#user_role').datalist('selectRow',i);
                }
            });
            $('#user_code').textbox('disable');
        },
        reloadUserGrid : function() {
            $(id_UserGrid).datagrid('reload');
        },
        resetUserForm : function() {
            $(id_UserInfoForm).form('reset');
            $('#user_id').val('');
            $('#user_code').textbox('enable');
            $('#user_role').datalist('unselectAll');
        },
		/**
         * 刷新组织机构
         */
		reloadOrganization : function(e) {
			$(id_UserOrganizationGrid).treegrid('reload');
		},
        test : function() {
            document.getElementById('myiframe').contentDocument.designMode = "on";
        }
    }
})(jQuery);