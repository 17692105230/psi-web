<div id="main_layout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',
    border:false,
    dataType:'json',
    href:'/web/purchase/purchase_order_center',
    onLoad:function(){
	    $.h.po.onLoadOrdersCode()
    }" style="overflow:hidden;"></div>
    <div data-options="region:'east',split:true,hideCollapsedContent:false,title:'&nbsp;',dataType:'json',href:'/web/purchase/purchase_order_east'" style="width:500px;"></div>

</div>
