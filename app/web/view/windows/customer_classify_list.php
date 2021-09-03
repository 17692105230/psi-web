<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'">
        <table id="win_customer_classify_list" class="easyui-datagrid"
               data-options="
                fit: true,
				fitColumns: true,
				rownumbers:true,
				border: false,
				toolbar: '#win_customer_classify_toolbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
				url:$.toUrl('customer', 'loadCustomerClassify'),
				method: 'get',
				pagination: true,
				pageSize: 25,
				showPageList: false,
				displayMsg: '共 {total} 行',
				onDblClickRow:$.h.window.coustomerClassifyWin.onDblClickRow
			">
            <thead>
            <tr>
                <th data-options="field:'customer_classify_id',checkbox:true"></th>
                <th data-options="field:'customer_classify_name',align:'left',width:20"><strong>分类名称</strong></th>
                <th data-options="field:'customer_classify_price',align:'left',width:15"><strong>分类价格</strong></th>
                <th data-options="field:'customer_classify_describe',align:'left',width:20"><strong>描述</strong></th>
            </tr>
            </thead>
        </table>
        <div id="win_customer_classify_toolbar" style="padding-left:2px;">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true,onClick:$.h.window.coustomerClassifyWin.onDelete">删除</a>
            <div style="float:right;">
                <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.window.coustomerClassifyWin.onRefresh">刷新</a>
            </div>
        </div>
    </div>
    <div data-options="
		region:'east',
		split:true,
		collapsible:false,
		tools:[{
            iconCls:'icon-add',
            handler:$.h.window.coustomerClassifyWin.onAddNew
        }],
		footer:'#win_customer_classify_form_footer'" title="客户分类信息" style="width:320px;padding:10px;">
        <form id="win_customer_classify_form" method="post">
            <div class="form-clo1">
                <div class="name w">分类名称:</div>
                <div class="ctl w">
                    <input name="classify_name" class="easyui-textbox" type="text" data-options="required:true" style="width:100%;"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">分类价格:</div>
                <div class="ctl w">
                    <input name="classify_price" class="easyui-textbox" type="text" data-options="required:true" style="width:100%;"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">排序:</div>
                <div class="ctl w">
                    <input id="classify_sort" name="sort" class="easyui-numberspinner" style="width:100px;" value="100" required="required" data-options="min:0,max:9999,editable:true">
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">描述:</div>
                <div class="ctl w">
                    <input  name="describe_info" class="easyui-textbox" type="text" data-options="required:false" style="width:100%;height: 100px"/>
                </div>
            </div>
        </form>
    </div>
    <div id="win_customer_classify_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
        <a class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.window.coustomerClassifyWin.onClose" style="width:100px;">关闭</a>
        <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.coustomerClassifyWin.onSave" style="width:100px;">提交</a>
    </div>
</div>