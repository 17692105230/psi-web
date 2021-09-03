<table id="client_grid" class="easyui-datagrid" style="width:100%;" title=""
       data-options="
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        fit:true,
        border:false,
        url:'/web/client/loadList',
        method:'get',
        remoteSort:false,
        multiSort:true,
		pagination:true,
		pageSize:20,
		pageList:[20,50,100],
        toolbar:'#client_grid_toolbar',
		onDblClickRow:$.h.client.onDblClickRow,
        onClickCell:$.h.client.onClickCell
    ">
    <thead>
    <tr>
        <th data-options="field:'client_id',checkbox:true"></th>
        <th data-options="field:'client_name',width:100"><b>客户名称</b></th>
        <th data-options="field:'client_phone',width:100,align:'left'"><b>客户手机</b></th>
        <th data-options="field:'client_category_name',width:60,align:'center'"><b>客户类别</b></th>
        <th data-options="field:'client_discount',width:60,align:'center'"><b>默认折扣</b></th>
        <th data-options="field:'account_money',width:60,align:'right'"><b>账户金额</b></th>
        <th data-options="field:'account_fmoney',width:60,align:'right'"><b>冻结金额</b></th>
        <th data-options="
                    field:'create_time',
                    width:60,
                    align:'center',
                    formatter:function(value,row,index) {
                        if (value == null){
                            return;
                        }
                        return $.DT.UnixToDate(value);
                    }
                   "><b>建立时间</b></th>
        <th data-options="
                    field:'update_time',
                    width:60,
                    align:'center',
                    formatter:function(value,row,index) {
                        if (value == null){
                            return;
                        }
                        return $.DT.UnixToDate(value);
                    }
                   "><b>创建时间</b></th>

        <th data-options="
					field:'client_status',
					align:'center',
					formatter:function(value, row) {
						return  value == 1 ? '启用' : '<strong>禁用</strong>';
					}
				">&nbsp;<b>状态</b>&nbsp;</th>
        <th data-options="field:'client_id1',align:'center',formatter:$.h.client.modifyFormatter">修改</th>
        <th data-options="field:'client_id2',align:'center',formatter:$.h.client.delFormatter">删除</th>
    </tr>
    </thead>
</table>
<div id="client_grid_toolbar" style="padding-left:5px;">
<!--    <input class="easyui-textbox" style="width:160px;" data-options="label:'帐号',labelWidth:35,prompt:'客户帐号'"/>-->
    <input class="easyui-textbox" style="width:160px;" data-options="label:'姓名',labelWidth:35,prompt:'客户姓名'"/>
    <input id="client_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,width:70" checked>
    <a href="javascript:void(0)" title="Ctrl+0" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">搜索</a>
    <div style="float:right;">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.client.onRefresh">刷新</a>
    </div>
</div>