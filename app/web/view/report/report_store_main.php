<div id="main_layout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'east',split:true,hideCollapsedContent:false,title:'查询条件',collapsed:false,dataType:'json',href:'/web/report/report_store_east'"
         style="width:320px;"></div>
    <div data-options="region:'center',onLoad:_.r.store.load,border:false,dataType:'json',href:'/web/report/report_store_center'"
         style="overflow:hidden;"></div>
</div>
