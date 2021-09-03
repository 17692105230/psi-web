<div id="supplier_layout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'east',split:true,hideCollapsedContent:false,title:'结算账户编辑',footer:'#supplier_form_footer',dataType:'json',href:'/web/finance/settlement_right'"
         style="width:400px;"></div>
    <div data-options="region:'center',dataType:'json',href:'/web/finance/settlement_center'"
         style="overflow:hidden;"></div>
</div>
<div id="supplier_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-save'" style="width:120px;">重 置</a>
    <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.settlement.onSave" style="width:120px;">保
        存</a>
</div>
