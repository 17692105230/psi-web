<form id="user_info_form" method="post">
<input type="hidden" id="user_id" name="id"/>
	<div class="form-clo1">
        <div class="name">组织机构:</div>
        <div class="ctl">
			<select id="user_organization_code" name="user_organization_code" class="easyui-combotree" style="width:100%;"
				data-options="
					editable:false,
					lines:true,
					url:'/Manage/Organization/OrgComboTreeLoad',
					required:true,
				"></select>
        </div>
    </div>
    <div class="form-clo1">
        <div class="name">姓名:</div>
        <div class="ctl">
            <input id="user_name" name="user_name" class="easyui-textbox" style="width:100%;" data-options="required:true"/>
        </div>
    </div>
	<div class="form-clo1">
        <div class="name">工号:</div>
        <div class="ctl">
            <input id="user_code" name="user_code" class="easyui-textbox" style="width:100%;" data-options="required:true"/>
        </div>
    </div>
	<div class="form-clo1">
        <div class="name">登录密码:</div>
        <div class="ctl">
            <input id="user_login_password" name="user_login_password" class="easyui-passwordbox" data-options="iconWidth:28,prompt:'请输入密码'" style="width:100%;padding:10px;"/>
        </div>
    </div>
	<div class="form-clo1">
        <div class="name">允许登录:</div>
        <div class="ctl">
            <input id="user_allow_login" class="easyui-switchbutton" data-options="onText:'允许',offText:'禁止',width:80">
        </div>
    </div>
	<div class="form-clo1">
        <div class="name"></div>
        <div class="ctl">
            <input id="user_is_sales" class="easyui-switchbutton" data-options="onText:'是',offText:'否',width:80" >
            是否为营业员
        </div>
    </div>
    <div class="form-clo1">
        <div class="name"></div>
        <div class="ctl">
            <input id="user_allow_view" class="easyui-switchbutton" data-options="onText:'允许',offText:'禁止',width:80">
            允许查看其他门店单据
        </div>
    </div>
    <div class="form-clo1">
        <div class="name">手机号:</div>
        <div class="ctl">
            <input id="user_iphone" name="user_iphone" class="easyui-textbox" style="width:100%;" data-options="required:true"/>
        </div>
    </div>
    <div class="form-clo1">
        <div class="name">身份证:</div>
        <div class="ctl">
            <input id="user_idcode" name="user_idcode" class="easyui-textbox" style="width:100%;"/>
        </div>
    </div>
    <div class="form-clo1">
        <div class="name">入职时间:</div>
        <div class="ctl">
            <input id="user_entry_time" name="user_entry_time" class="easyui-datebox" style="width:100%;" data-options="required:true"/>
        </div>
    </div>

    <div class="form-clo1">
        <div class="name">员工描述:</div>
        <div class="ctl">
            <input id="user_describe" name="user_describe" class="easyui-textbox" data-options="multiline:true" style="width:100%;height:60px;"/>
        </div>
    </div>

    <div class="form-clo1">
        <div class="name">角色:</div>
        <div class="ctl">
            <div id="user_role" class="easyui-datalist" style="width:100%;height: 30px"
                 data-options="
                    checkbox:true,
                    lines:true,
                    singleSelect:false,
                    selectOnCheck:true,
					textField:'role_name',
					valueField:'role_code',
					url:'/manage/User/LoadRoleRows'
                ">
            </div>
        </div>
    </div>
</form>