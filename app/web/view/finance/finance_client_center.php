<div class="easyui-panel" data-options="fit:true">
    <header class="header-hover">
        <div style="float:left;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.fc.winIn.open">收款</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true,onClick:$.h.fc.winBegin.open">期初调整</a>
            <span style="color:#999;font-weight:bold;">|</span>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',onClick:$.h.fc.btnDel,plain:true">删除</a>
            <span style="color:#999;font-weight:bold;">|</span>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-print',plain:true">打印</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true">导出</a>
        </div>
        <div style="float:right;">
            <input id="sum_pmoney" class="easyui-numberbox" data-options="buttonText:'应收金额',width:190,editable:false,value:0,prefix:'￥',precision:2"/>
            <input id="sum_rmoney" class="easyui-numberbox" data-options="buttonText:'实收金额',width:190,editable:false,value:0,prefix:'￥',precision:2"/>
            <input id="sum_pbalance" class="easyui-numberbox" data-options="buttonText:'应收余额',width:190,editable:false,value:0,prefix:'￥',precision:2"/>
        </div>
    </header>
    <table id="record_grid" class="easyui-datagrid" style="width:100%;"
        data-options="
            fitColumns:true,
            rownumbers:true,
            iconCls:'kbi-icon-record',
            singleSelect:true,
            fit:true,
            border:false,
            method:'get',
            remoteSort:false,
            multiSort:true,
            autoRowHeight:false,
            pagination: true,
			pageSize: 20,
			pageList: [20,30,50,100],
            onClickCell:$.h.fc.onOrdersClickCell
        ">
        <thead>
            <tr>
                <th data-options="field:'details_id',checkbox:true"></th>
                <th data-options="
                    field:'details_id1',
                    fixed:true,
                    width:30,
                    align:'center',
                    formatter:function(value,row) {
                        var str;
                        switch(row.item_type) {
                            case 9101:/* 销售收入 */
                            case 9102:/* 销售退货 */
                            case 9103:/* 采购支出 */
                            case 9104:/* 采购退货 */
                            case 9106:/* 零售 */
                            case 9108:/* 优惠金额 */
                            case 9109:/* 充值 */
                                str = '<a href=\'javascript:void(0);\' class=\'hjtr-ico-view\' style=\'display:inline-block;width:16px;height:16px;\'></a>';
                                break;
                            case 9105:/* 期初调整 */
                            case 9199:/* 收款 */
                            case 9198:/* 付款 */
                                str = '<a href=\'javascript:void(0);\' class=\'datagrid-row-modify\' style=\'display:inline-block;width:16px;height:16px;\'></a>';
                                break;
                        }
                        return str;
                    }
                "></th>
                <th data-options="field:'client_name',fixed:true,width:90,align:'center'"><b>客户名称</b></th>
                <th data-options="
                        field:'create_time',
                        fixed:true,
                        width:150,
                        align:'center',
                        formatter: function(value,row,index) {
                            return $.DT.UnixToDate(value,2,true);
                        }"><b>业务时间</b></th>
                <th data-options="
                        field:'orders_code',
                        fixed:true,
                        width:175,
                        align:'center',
                        styler: function(value,row,index){
                            return 'cursor:pointer;color:red;text-decoration:underline;';
                        }"><b>单据编号</b></th>
                <th data-options="field:'account_name',width:100,align:'center'"><b>账目类别</b></th>
                <th data-options="
                        field:'settlement_name',
                        width:100,
                        align:'center',
                        formatter: function(value,row,index) {
                            return value ? value : '--';
                        }"><b>结算账户</b></th>
                <th data-options="field:'user_name',fixed:true,width:90,align:'center'"><b>操作人</b></th>
                <th data-options="field:'pmoney',width:75,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }"><b>应收金额</b></th>
                <th data-options="field:'rmoney',width:75,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }"><b>实收金额</b></th>
                <th data-options="field:'pbalance',width:75,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }"><b>应收余额</b></th>
                <th data-options="field:'settlement_balance',width:100,align:'right',formatter:function(value, row){ return value == 0 ? '--' : $.formatMoney(value,'￥'); }"><b>结算账户余额</b></th>
                <th data-options="field:'client_balance',width:100,align:'right',formatter:function(value, row){ return $.formatMoney(value,'￥'); }"><b>客户账户余额</b></th>
            </tr>
        </thead>
    </table>
</div>