<div id="main_layout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'east',split:true,title:'销售订单',dataType:'json',href:'/web/sale/sale_plan_east',
            onLoad:function(){
	         $.h.sp.onLoadOrdersCode()
            }" style="overflow:hidden;width:30%;-moz-user-select:none;-webkit-user-select:none;"></div>
    <div data-options="region:'center',hideCollapsedContent:false,border:false,dataType:'json',href:'/web/sale/sale_plan_center'"></div>
</div>