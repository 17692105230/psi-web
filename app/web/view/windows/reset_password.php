<div class="easyui-panel" data-options="footer:'#win_reset_password_footer'" style="width:100%;height:100%;padding:15px 10px;overflow-y:hidden;">
    <form id="win_base_demo2_form" method="post">
    <div style="margin-right:20px;">
		<div class="form-clo1">
			<div class="name">新密码:</div>
			<div class="ctl">
				<div><input class="easyui-passwordbox" style="width:100%" /input></div>
			</div>
		</div>
        <div class="form-clo1">
            <div class="name">重复新密码:</div>
            <div class="ctl">
                <div><input class="easyui-passwordbox"  style="width:100%" /input></div>
            </div>
        </div>
    </div>
    </form>
    <div id="win_reset_password_footer" style="padding:5px;overflow:hidden;">
        <div style="float:right;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winResetPassword.onSave" style="width:100px;">确定</a>
        </div>
    </div>
</div>
	