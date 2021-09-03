/**
 * 会员管理函数
 */
(function($) {
    /**
     * 主要的
     */
    $.h.member = {
        /**
         * 页面加载
         */
        onLoad : function(e) {
            
        },
        /**
         * 新增会员事件
         */
        onNewAdd : function() {
            $('#member_tabs').tabs('select',0);
            $('#member_code').textbox('readonly',false);
            $('#member_base_from').form('reset');
            $('#member_account_from').form('reset');

            $('#account_points').textbox('textbox').val(0);
            $('#account_count').textbox('textbox').val(0);
            $('#account_orders_count').textbox('textbox').val(0);
            $('#account_first_time').textbox('textbox').val('1999-01-01');
        },
        /**
         * 保存会员基本信息
         */
        submitBase : function() {
            /*会员类别*/
            var member_category_name = $('#member_category_code').combobox('getText');
            /*接待导购*/
            var receive_clerk_name = $('#receive_clerk_code').combobox('getText');
            /*居住城市*/
            var member_city_name = $('#member_city_code').combobox('getText');
            /*会员状态*/
            var member_status = $('#member_status').switchbutton('options').checked ? 1 : 0;

            $('#member_base_from').form('submit', {
                url:'/Manage/Member/SaveMemberBaseForm',
                novalidate:false,
                queryParams:{
                    member_category_name:member_category_name,
                    receive_clerk_name:receive_clerk_name,
                    member_city_name:member_city_name,
                    member_status:member_status
                },
                onSubmit: function(param) {
                    var v = $(this).form('validate')
                    if (v) {
                        $.messager.progress({title:'Please waiting',msg:'Submit data...'});
                    }
                    return v;
                },
                success:function(data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $('#member_grid').datagrid('reload');
                        $('#member_code').textbox('readonly',false);
                    }
                    $.messager.progress('close');
                    $.h.C.warnMessager(data.errmsg);
                }
            });
        },
        /**
         * 保存会员账户信息
         */
        submitAccount : function() {
            /*会员类别*/
            var member_code = $.trim($('#member_code').textbox('getValue'));
            if (member_code == '') {
                $.h.C.warnMessager('没有找到会员信息~~');
                return;
            }

            $('#member_account_from').form('submit', {
                url:'/Manage/Member/SaveMemberAccountForm',
                novalidate:false,
                queryParams:{
                    member_code:member_code
                },
                onSubmit: function(param) {
                    var v = $(this).form('validate')
                    if (v) {
                        $.messager.progress({title:'Please waiting',msg:'Submit data...'});
                    }
                    return v;
                },
                success:function(data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $('#member_grid').datagrid('reload');
                        $('#member_code').textbox('readonly',false);
                    }
                    $.messager.progress('close');
                    $.h.C.warnMessager(data.errmsg);
                }
            });
        },
        /**
         * 会员列表行单击事件
         */
        onDblClickRow : function(rowIndex, rowData) {
            $('#member_code').textbox('readonly');
            /*$('#member_tabs').tabs('select',0);*/
            $('#member_base_from').form('load',rowData);
            $('#member_account_from').form('load',rowData);
            
        }
    }
})(jQuery);