<div class="easyui-panel" data-options="footer:'#win_base_demo2_form_footer'" style="width:100%;height:100%;padding:15px 10px;overflow-y:hidden;">
    <form id="win_base_demo2_form" method="post">
    <div style="margin-right:20px;">
		<div class="form-clo1">
			<div class="name">账号:</div>
			<div class="ctl">
				<div><input class="easyui-textbox" value="xiaoming123" style="width:100%" /input></div>
			</div>
		</div>
        <div class="form-clo1">
            <div class="name">姓名:</div>
            <div class="ctl">
                <div><input class="easyui-textbox" value="小敏" style="width:100%" /input></div>
            </div>
        </div>
        <div class="form-clo1">
            <div class="name">手机:</div>
            <div class="ctl">
                <div><input class="easyui-textbox" value="13838888888" style="width:100%" /input></div>
            </div>
        </div>
        <div class="form-clo1">
            <div class="name">邮箱:</div>
            <div class="ctl">
                <div><input class="easyui-textbox" value="12345678@qq.com" style="width:100%" /input></div>
            </div>
        </div>
        <div class="form-clo1">
            <div class="name">备注:</div>
            <div class="ctl">
                <div><input class="easyui-textbox" style="width:100%" /input></div>
            </div>
        </div>

    </div>
    </form>
    <div id="win_base_demo2_form_footer" style="padding:5px;overflow:hidden;">
        <div style="float:right;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-reload',onClick:$.h.window.winBaseUserInfo.resetPassword" style="width:100px;">重置密码</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseUserInfo.onSave" style="width:100px;">保存</a>
        </div>
    </div>
</div>
	