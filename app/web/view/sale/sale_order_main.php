<div id="main_layout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'east',split:true,title:'销售单',dataType:'json',href:'/web/sale/sale_order_east',
            onLoad:$.h.so.onLoadOrdersCode"
         style="overflow:hidden;width:30%;-moz-user-select:none;-webkit-user-select:none;"></div>
    <div data-options="region:'center',hideCollapsedContent:false,border:false,dataType:'json',href:'/web/sale/sale_order_center'"></div>
</div>