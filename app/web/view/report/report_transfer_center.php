	<div class="easyui-panel" data-options="fit:true">
		<header class="header-hover">
			<div style="float:left;">
				<a id="btn_search" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">今天</a>
				<a id="btn_search" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">昨天</a>
				<a id="btn_search" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">近7天</a>
				<a id="btn_search" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">近30天</a>
				<a id="btn_search" class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true">查询</a>
			</div>
			<div style="float:right;">
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g1',selected:true,plain:true,status:1" style="color:blue;font-weight:bold">调拨明细</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g1',plain:true,status:2">调拨单汇总</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g1',plain:true,status:3">商品汇总</a>
				<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g1',plain:true,status:4">仓库汇总</a>
			</div>
		</header>
		<table id="sale_console_grid" class="easyui-datagrid" style="width:100%;"
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
				footer:'#sale_console_grid_footer',
				data:[{
					oid:1,
					style_name:'秋冬男士修身羊毛呢子大衣',
					barcode:'802',
					style_Code:'123774-ae55',
					color_name:'宝石蓝',
					size_name:'3XL',
					count:20,
					tag_price:568,
					discount:8.8,
					sale_price:350,
					paid_price:500,
					paid_price1:500
				},{
					oid:2,
					style_name:'秋冬男士修身羊毛呢子大衣',
					barcode:'802',
					style_Code:'123774-ae55',
					color_name:'宝石蓝',
					size_name:'3XL',
					count:20,
					tag_price:568,
					discount:8.8,
					sale_price:350,
					paid_price:500,
					paid_price1:500
				},{
					oid:3,
					style_name:'秋冬男士修身羊毛呢子大衣',
					barcode:'802',
					style_Code:'123774-ae55',
					color_name:'宝石蓝',
					size_name:'3XL',
					count:20,
					tag_price:568,
					discount:8.8,
					sale_price:9999350,
					paid_price:500,
					paid_price1:500
				},{
					oid:3,
					style_name:'秋冬男士修身羊毛呢子大衣',
					barcode:'802',
					style_Code:'123774-ae55',
					color_name:'宝石蓝',
					size_name:'3XL',
					count:9999,
					tag_price:568,
					discount:100,
					sale_price:350,
					paid_price:500,
					paid_price1:500
				},{
					oid:3,
					style_name:'秋冬男士修身羊毛呢子大衣',
					barcode:'802',
					style_Code:'123774-ae55',
					color_name:'宝石蓝',
					size_name:'3XL',
					count:20,
					tag_price:99568,
					discount:8.8,
					sale_price:350,
					paid_price:500,
					paid_price1:500
				}]
			">
			<thead data-options="frozen:true">
				<tr>
					<th data-options="field:'details_id',checkbox:true"></th>
					<th data-options="
						field:'id',
						formatter:function(value,row) {
							return '<img src=\'/assets/web/img/20160803143335.png\' href=\'javascript:alert('+row.oid+');\' class=\'datagrid-row-add\' style=\'display:inline-block;width:20px;height:20px;\'></img>';
						}
					"></th>
					<th data-options="field:'style_name',editor:'textbox',width:220"><b>商品</b></th>
					<th data-options="field:'paid_price2',width:150,align:'center'"><b>单据编号</b></th>
					<th data-options="field:'style_Code',width:150"><b>货号</b></th>
				</tr>
			</thead>
			<thead>
				<tr>
					<th data-options="field:'barcode',editor:'textbox',width:120"><b>条码</b></th>
					<th data-options="field:'color_name',editor:'textbox',width:70,align:'center'"><b>颜色</b></th>
					<th data-options="field:'size_name',width:70,align:'center'"><b>尺码</b></th>
					<th data-options="field:'size_name1',width:100,align:'center'"><b>品牌</b></th>
					<th data-options="field:'size_name2',width:100,align:'center'"><b>分类</b></th>
					<th data-options="field:'size_name3',width:70,align:'center'"><b>单位</b></th>
                    <th data-options="field:'count11',width:70,align:'center'"><b>调拨数量</b></th>
                    <th data-options="field:'count4',width:100,align:'center'"><b>调出仓库</b></th>
                    <th data-options="field:'count5',width:100,align:'center'"><b>调入仓库</b></th>
                    <th data-options="field:'count2',width:70,align:'center'"><b>调拨人</b></th>
                    <th data-options="field:'count3',width:160,align:'center'"><b>调拨时间</b></th>
                    <th data-options="field:'count1',width:60,align:'center'"><b>年份</b></th>
					<th data-options="field:'count6',width:60,align:'center'"><b>季节</b></th>
				</tr>
			</thead>
		</table>
		<div id="sale_console_grid_footer" style="padding:2px 5px;">
			<div style="float:left;">
				<input class="easyui-textbox" data-options="label:'盘点单品数',labelWidth:70,width:150,editable:false,value:0"/>
				<input class="easyui-textbox" data-options="label:'盘点商品数',labelWidth:70,width:150,editable:false,value:-99999"/>
				<input class="easyui-textbox" data-options="label:'盘点仓库数',labelWidth:70,width:120,editable:false,value:0"/>
                <input class="easyui-textbox" data-options="label:'盘点盈亏数',labelWidth:70,width:150,editable:false,value:0"/>
                <input class="easyui-numberbox" data-options="label:'盘点盈亏金额',labelWidth:85,width:220,editable:false,value:0,prefix:'￥',precision:2"/>
			</div>
			<div style="float:right;">
				<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="$rola.cashier.sale.removeBillDetails()">删除</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="$rola.cashier.sale.returnGoods()">退款</a>
				<a href="javascript:void(0)" title="Ctrl+0" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="$rola.cashier.sale.newSale()">开新单</a>
				<input class="easyui-textbox" data-options="prompt:'请输入条码......'" style="width:160px;"/>
			</div>
		</div>
	</div>

