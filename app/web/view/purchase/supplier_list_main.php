<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',dataType:'json',href:'/web/purchase/supplier_list_center'" style="overflow:hidden;"></div>
    <div data-options="region:'east',split:true,hideCollapsedContent:false,title:'供应商编辑',footer:'#supplier_form_footer',dataType:'json',href:'/web/purchase/supplier_list_left'" style="width:30%;"></div>
</div>
<div id="supplier_form_footer" style="text-align:right;padding:5px;overflow:hidden;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.supplier.onReset" style="width:120px;">重 置</a>
    <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.supplier.onSave" style="width:120px;">保 存</a>
</div>