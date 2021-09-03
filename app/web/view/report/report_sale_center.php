	<div class="easyui-panel" data-options="fit:true">
		<header class="header-hover">
			<div style="float:left;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,toggle:true,group:'g1',selected:true,category:1,onClick:_.r.sale.sDate">当天</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,toggle:true,group:'g1',category:2,onClick:_.r.sale.sDate">昨天</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,toggle:true,group:'g1',category:3,onClick:_.r.sale.sDate">本周</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,toggle:true,group:'g1',category:4,onClick:_.r.sale.sDate">本月</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,toggle:true,group:'g1',category:5,onClick:_.r.sale.sDate">本季</a>
				<a id="btn_query" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,toggle:true,group:'g1',category:0,onClick:_.r.sale.btnTopQuery">查询</a>
			</div>
			<div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g2',selected:true,plain:true,category:1,onClick:_.r.sale.sColumns" style="color:blue;font-weight:bold">销售明细</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g2',plain:true,category:2,onClick:_.r.sale.sColumns">商品汇总</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g2',plain:true,category:3,onClick:_.r.sale.sColumns">单据汇总</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g2',plain:true,category:4,onClick:_.r.sale.sColumns">客户汇总</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g2',plain:true,category:5,onClick:_.r.sale.sColumns">销售员汇总</a>
			</div>
		</header>
		<table id="main_grid" class="easyui-datagrid" style="width:100%;"
			data-options="
				rownumbers:true,
				iconCls:'kbi-icon-record',
				singleSelect:true,
				fit:true,
				border:false,
				method:'get',
				remoteSort:false,
				multiSort:true,
				autoRowHeight:false,
				pagination: true,
				pageSize: 20,
				pageList: [20,30,50,100],
				footer:'#main_grid_footer'
			">
		</table>
		<div id="main_grid_footer" style="padding:2px 5px;">
			<div style="float:left;">
				<input id="goods_count" class="easyui-textbox" data-options="label:'销售数量',labelWidth:70,width:160,editable:false,value:0"/>
				<input id="goods_money" class="easyui-numberbox" data-options="label:'销售额',labelWidth:70,width:200,editable:false,value:999999,prefix:'￥',precision:2"/>
			</div>
			<div style="float:right;">
				<input class="easyui-textbox" data-options="prompt:'请输入条码......'" style="width:160px;"/>
			</div>
		</div>
	</div>

