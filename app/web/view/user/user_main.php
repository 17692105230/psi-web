




    <div id="main_layout" class="easyui-layout" data-options="fit:true">
        <div data-options="region:'west',dataType:'json',split:true,hideCollapsedContent:false,title:'组织机构',href:'/web/user/user_west'" style="width:500px;"></div>
        <div data-options="region:'center',dataType:'json',title:'员工列表',href:'/web/user/user_center'" style="overflow:hidden;"></div>
        <div data-options="region:'east',split:true,dataType:'json',title:'员工信息',hideCollapsedContent:false,href:'/web/user/user_east',footer:'#user_info_form_footer'" style="width:380px;min-width:280px;max-width:460px;overflow:hidden;padding:10px;"></div>
    </div>
    <div id="user_info_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.user.resetUserForm" style="width:120px;">重 置</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.user.saveUserInfo" style="width:120px;">保 存</a>
    </div>
