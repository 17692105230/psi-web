<div id="main_layout" class="easyui-layout" data-options="fit:true">
    <div data-options="region:'east',split:true,hideCollapsedContent:false,title:'查询条件',collapsed:false,dataType:'json',onLoad:$.h.fa.leftLoad,href:'/web/finance/finance_account_east'"
         style="width:320px;"></div>
    <div data-options="region:'center',border:false,dataType:'json',href:'/web/finance/finance_account_center'"
         style="overflow:hidden;"></div>
</div>
<!-- 窗口 -->
<div id="win_finance_account" class="easyui-window" style="padding:5px;"
     data-options="modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"/>
<!-- 窗口 -->
<div id="win_details" class="easyui-window" style="padding:5px;width:700px;height:360px;"
     data-options="modal:true,closed:true,collapsible:false,minimizable:false,maximizable:false,resizable:false,draggable:true"
/>
