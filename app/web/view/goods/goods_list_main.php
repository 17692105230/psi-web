<div class="easyui-layout" data-options="fit:true">
	<div data-options="region:'center',border:false,dataType:'json',href:'/web/goods/goods_list_center'" style="overflow:hidden;"></div>
	<div data-options="region:'east',split:true,hideCollapsedContent:false,title:'&nbsp;',dataType:'json',footer:'#goods_edit_footer',onLoad : $.h.goods.onLoad,href:'/web/goods/goods_list_left'" style="width:600px;"></div>
</div>
<div id="goods_edit_footer" style="height:55px;padding:5px;text-align:right;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-ok',onClick:$.h.goods.onSave" href="javascript:void(0)" style="width:100%;height:100%;">提交</a>
</div>