<div id="layout_color_size" class="easyui-layout" data-options="fit:true">
	<div data-options="region:'north',split:true,hideCollapsedContent:false,collapsed:true,fit:true,title:'参考库存[F1]'">
		<table id="stock_number_grid" class="easyui-datagrid"
			data-options="
				fit: true,
				fitColumns: true,
				border: false,
				iconCls: 'kbi-icon-record',
				singleSelect: true,
				method: 'get',
				remoteSort:false,
				multiSort:true
			">
		</table>
	</div>
    <div data-options="region:'center'">
        <table id="current_number_grid" class="easyui-datagrid"
			data-options="
				fit:true,
				fitColumns:true,
				border:false,
				toolbar:'#current_number_grid_toolbar',
				iconCls:'kbi-icon-record',
				singleSelect:false,
                /* 设置选择列的字符串 */
                strColumns:'',
				method:'get',
				remoteSort:false,
				multiSort:true,
                selectColumn:true,
				onClickCell:$.h.c.csw.onClickCell,
                onKeyUpDownEvent:$.h.c.csw.onClickCell
			">
		</table>
		<div id="current_number_grid_toolbar" style="height:32px;padding:1px 1px 1px 1px;">
			<input id="current_set_global_number" class="easyui-numberspinner" value="0" data-options="min:0,max:9999" style="width:60px;"/>
			<a href="javascript:void(0)" title="Ctrl+0" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true" onclick="$.h.c.csw.onSetGlobalNumber()">设置</a>
			<span id="goods_reject_panel" style="display:none;">
				<input id="goods_reject" class="easyui-switchbutton" data-options="onText:'退货',offText:'销售',value:1,width:80" checked />
			</span>
			<div style="float:right;">
				<a id="current_color_size_submit" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true">保存</a>
			</div>
		</div>
    </div>
</div>