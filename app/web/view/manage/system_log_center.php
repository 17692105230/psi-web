<table id="member_grid" class="easyui-datagrid" style="width:100%;" title=""
       data-options="
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        fit:true,
        border:false,
		url:'/web/manage/loadSystemLogGridData',
        method:'get',
        remoteSort:false,
        multiSort:true,
		pagination:true,
		pageSize:20,
		pageList:[20,50,100],
        toolbar:'#user_grid_toolbar',
		onDblClickRow:$.h.member.onDblClickRow
    ">
    <thead>
    <tr>
        <th data-options="field:'id',hidden:true"></th>
        <th data-options="field:'date',width:2,align:'center'"><b>日期</b></th>
        <th data-options="field:'operator',width:2,align:'center'"><b>操作人</b></th>
        <th data-options="field:'operation_type',width:3,align:'center'"><b>操作标识</b></th>
        <th data-options="field:'content',width:7"><b>操作内容</b></th>
        <th data-options="field:'equipment',width:1"><b>设备</b></th>
        <th data-options="field:'ip'"><b>IP</b></th>
    </tr>
    </thead>
</table>
<div id="user_grid_toolbar" style="padding-left:5px;">
    日期从：<input id="orders_date" name="orders_date" class="easyui-datebox"  value="(new Date()).Format('yyyy-MM-dd')" style="width:140px;"/>
    至：<input id="orders_date" name="orders_date" class="easyui-datebox"  value="(new Date()).Format('yyyy-MM-dd')" style="width:140px;"/>
    操作人姓名：<input class="easyui-textbox" style="width:120px;"/>
    操作内容：<input class="easyui-textbox" style="width:160px;"/>
    <a href="javascript:void(0)" title="Ctrl+0" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">搜索</a>
    <span style="float:right;">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-reload',plain:true">刷新</a>
    </span>
</div>