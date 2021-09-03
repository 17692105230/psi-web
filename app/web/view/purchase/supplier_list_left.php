<div class="easyui-panel" style="padding:10px;height: 100%"
     data-options="
		fit:true,
		border:false
	">
    <form id="supplier_form" method="post">
        <div class="form-clo2">
                <div class="name w120">供应商:</div>
                <div class="ctl w120">
                    <input id="supplier_name" name="supplier_name" class="easyui-textbox" style="width:100%;" data-options="required:true" /input>
                </div>

                <div class="name w120">负责人:</div>
                <div class="ctl w120">
                    <input id="supplier_director" name="supplier_director" class="easyui-textbox" style="width:100%;" data-options="required:true" /input>
                </div>
        </div>
        <div class="form-clo2">
                <div class="name w120"> 默认折扣:</div>
                <div class="ctl w120">
                    <input id="supplier_discount" name="supplier_discount" class="easyui-numberspinner" style="width:100%;" data-options="min:0,max:100,precision:0,value:100"/input>
                </div>

                <div class="name w120">状态:</div>
                <div class="ctl w120">
                    <input id="supplier_status" name="supplier_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,width:70,height:30" checked/input>
                </div>
        </div>

        <div class="form-panel">
                <div class="form-clo2">
                    <div class="name w120">电话:</div>
                    <div class="ctl w120">
                        <input id="supplier_phone" name="supplier_phone" class="easyui-numberbox" style="width:100%;" /input>
                    </div>
                    <div class="name w120">手机:</div>
                    <div class="ctl w120">
                        <input id="supplier_mphone" name="supplier_mphone" class="easyui-numberbox" style="width:100%;" /input>
                    </div>
                </div>
                <div class="form-clo2">
                    <div class="name w120">邮箱:</div>
                    <div class="ctl w120">
                        <input id="supplier_email" name="supplier_email" class="easyui-textbox" style="width:100%;" /input>
                    </div>
                    <div class="name w120">排序:</div>
                    <div class="ctl w120">
                        <input id="supplier_sort" name="supplier_sort" class="easyui-numberspinner" data-options="required:true,labelPosition:'top',spinAlign:'horizontal',min:0,max:9999999,precision:0,width:100,value:100"/>
                    </div>
                </div>
        </div>
        <div class="form-panel">
                <div class="form-clo2">
                    <div class="name w120">地址:</div>
                    <div class="ctl w120" style="width: 100%">
                        <input id="supplier_address" name="supplier_address" class="easyui-textbox" style="width:100%;"/input>
                    </div>
                </div>
                <div class="form-clo2">
                    <div class="name w120">描述:</div>
                    <div class="ctl w120" style="width: 100%">
                        <input id="supplier_story" name="supplier_story" class="easyui-textbox" style="width:100%;height:80px;padding:4px;" data-options="multiline:true"/input>
                    </div>
                </div>
    </form>
</div>
