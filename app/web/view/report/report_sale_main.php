<div id="main_layout" class="easyui-layout" data-options="fit:true">
		<div data-options="region:'east',split:true,hideCollapsedContent:false,title:'查询条件',collapsed:false,dataType:'json',href:'/web/report/report_sale_east'" style="width:320px;"></div>
		<div data-options="region:'center',border:false,onLoad:_.r.sale.load,dataType:'json',href:'/web/report/report_sale_center'" style="overflow:hidden;"></div>
</div>
