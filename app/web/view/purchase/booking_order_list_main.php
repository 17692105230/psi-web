<div id="main_layout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'east',split:true,title:'采购订单列表',dataType:'json',href:'/web/purchase/purchase_plan_left'" style="overflow:hidden;width:30%;-moz-user-select:none;-webkit-user-select:none;"></div>
    <div id="main_center_layout"  data-options="
    region:'center',
    hideCollapsedContent:false,
    border:false,dataType:'json',
    href:'/web/purchase/purchase_plan_center',
    onLoad:$.h.pp.onLoadOrderCode()
"></div>
</div>
