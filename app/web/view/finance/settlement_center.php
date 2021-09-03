<table id="settlement_grid" class="easyui-datagrid" style="width:100%;"
    data-options="
		fit:true,
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        border:false,
        method:'get',
        remoteSort:false,
        multiSort:true,
        pagination:true,
		pageSize:30,
		pageList:[30,50,100],
        toolbar:'#settlement_grid_toolbar',
		url:'/manage/settlement/loadGridData',
        onDblClickRow:$.h.settlement.onDblClickRow,
        onLoadSuccess:function(data) {
            $('#settlement_number').numberbox('setValue',data.total);
            $('#enable_money').numberbox('setValue',data.enable_money);
            $('#disable_money').numberbox('setValue',data.disable_money);
        }
    ">
    <thead>
        <tr>
            <th data-options="field:'settlement_id',checkbox:true"></th>
            <th data-options="field:'settlement_name',halign:'center',width:100"><b>账户名称</b></th>
            <th data-options="field:'settlement_code',halign:'center',width:90"><b>帐号</b></th>
            <th data-options="field:'account_holder',halign:'center',width:50"><b>开户人</b></th>
			<th data-options="field:'account_type',align:'center',width:50"><b>账户类型</b></th>
            <th data-options="field:'subjection_store',align:'center',width:70"><b>隶属门店</b></th>
            <th data-options="
                field:'settlement_money',
                align:'center',width:50,
                formatter:function(value, row) {
                    return $.formatMoney(value,'￥');
                }"><b>账户金额</b></th>
            <th data-options="
				field:'settlement_status',
				align:'center',
                fixed:true,
				fixed:true,
				width:60,
				formatter:function(value, row) {
					return value == 1 ? '<font color=green>启用</font>' : '<font color=red>禁用</font>';
				}
			"><b>状态</b></th>
			<th data-options="field:'settlement_sort',fixed:true,width:60,align:'center'"><b>排序</b></th>
            <th data-options="field:'settlement_id1',align:'center',formatter:$.h.settlement.modifyFormatter">修改</th>
            <th data-options="field:'settlement_id2',align:'center',formatter:$.h.settlement.delFormatter">删除</th>
        </tr>
    </thead>
</table>
<div id="settlement_grid_toolbar" style="padding-left:5px;">
    <input id="settlement_number" class="easyui-numberbox" style="text-align:center;" data-options="label:'账户总数',labelWidth:65,width:110,editable:false,value:0" /input>
    <input id="enable_money" class="easyui-numberbox" style="width:230px;" data-options="label:'<b>启用</b>余额汇总',labelWidth:95,editable:false,value:0,precision:2,prefix:'￥'"/input>
    <input id="disable_money" class="easyui-numberbox" style="width:230px;" data-options="label:'<b>停用</b>余额汇总',labelWidth:95,editable:false,value:0,precision:2,prefix:'￥'"/input>
    <span style="float:right;">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true,onClick:$.h.settlement.onRefresh">刷新</a>
    </span>
</div>