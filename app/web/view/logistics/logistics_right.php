<div class="easyui-panel" style="padding:10px;"
     data-options="
		fit:true,
		border:false
	">
		<form id="logistics_base_from" method="post" >
			<input type="hidden" id="member_base_id" name="id" />
            <div class="form-clo2">
                    <div class="name w120">快递单类型:</div>
                    <div class="ctl w120">
                        <select id="logistics_type" name="logistics_type" class="easyui-combobox" style="width:100%;"
                                data-options="url:'/web/Logistics/loadType',
                                editable:false,
                                required:true,
                                method:'get',
                                valueField:'id',
                                textField:'name',
                                panelHeight:'auto'
                    "></select>
                    </div>
					<div class="name w120">名称:</div>
					<div class="ctl w120">
						<input id="logistics_name" name="logistics_name" class="easyui-textbox" style="width:100%;" data-options="required:true"/>
					</div>
            </div>
            <div class="form-clo2">
                    <div class="name w120">快递单模板:</div>
                    <div class="ctl w120">
                        <select id="logistics_type" name="logistics_type" class="easyui-combobox" style="width:100%;"
                                data-options="url:'/web/Logistics/loadModel',
                                editable:false,
                                required:true,
                                method:'get',
                                valueField:'id',
                                textField:'name',
                                panelHeight:'auto'
                    "></select>
                    </div>
                    <div class="name w120">网址:</div>
                    <div class="ctl w120">
                        <input id="logistics_Url" name="logistics_Url" class="easyui-textbox" style="width:100%;" data-options=""/>
                    </div>
            </div>
            <div class="form-clo2">
                <div class="name w120">快递公司:</div>
                <div class="ctl w120">
                    <select id="logistics_company" name="logistics_company" class="easyui-combobox" style="width:100%;"
                            data-options="url:'/web/Logistics/loadCompany',
                            editable:false,
                            required:true,
                            method:'get',
                            valueField:'id',
                            textField:'name',
                            panelHeight:'auto'
                "></select>
                </div>
                <div class="name"></div>
                <div class="ctl"></div>
            </div>
            <div class="form-clo2">
					<div class="name w120">运费说明:</div>
                    <div class="ctl w120" style="width: 100%">
                        <input id="shipping_instructions" name="shipping_ instructions" class="easyui-textbox" style="width:100%;height: 200px" data-options="multiline:true"/>
                    </div>
            </div>
            <div id="logistics_edit_footer" style="height:30px;padding:5px;text-align:right;">
                <a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$.h.member.submitAccount();" style="width:180px;">新增物流</a>
            </div>
		</form>
</div>
