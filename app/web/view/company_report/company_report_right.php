<div class="easyui-panel" style="padding:10px;"
     data-options="
		fit:true,
		border:false
	">
		<form id="logistics_base_from" method="post" >
			<input type="hidden" id="member_base_id" name="id" />
            <div class="form-clo2">
					<div class="name w120">标题:</div>
					<div class="ctl w120" style="width: 100%">
						<input id="logistics_name" name="logistics_name" class="easyui-textbox" style="width:100%;" data-options="required:true"/>
					</div>
            </div>
            <div class="form-clo2">
					<div class="name w120">内容:</div>
                    <div class="ctl w120" style="width: 100%">
                        <input id="shipping_instructions" name="shipping_ instructions" class="easyui-textbox" style="width:100%;height: 200px" data-options="multiline:true"/>
                    </div>
            </div>
            <div id="logistics_edit_footer" style="height:30px;padding:5px;text-align:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">保存</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">发布</a>
            </div>
		</form>
</div>
