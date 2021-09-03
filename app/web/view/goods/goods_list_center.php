<table id="goods_grid" class="easyui-datagrid" style="width:100%;" title=""
       data-options="
        fitColumns:true,
        rownumbers:true,
        iconCls:'kbi-icon-record',
        singleSelect:true,
        fit:true,
        border:true,
		url:'/web/goods/loadGoodsGrid',
        method:'get',
        remoteSort:false,
        multiSort:true,
        toolbar:'#goods_grid_toolbar',
		pagination:true,
		pageSize:30,
		pageList:[30,50,100],
        view:detailview,
        detailFormatter:function(rowIndex, rowData) {
            var sex = rowData['goods_sex'] == 0 ? '女' : '男';
            var status = rowData['goods_status'] ==1 ? '启用' : '禁用';
            var season = '未知';
            switch(rowData['goods_season']) {case 1:season='春季';break;case 2:season='夏季';break;case 3:season='秋季';break;case 4:season='冬季';break;}
            var first_image = rowData.images[0] ? rowData.images[0].assist_url : '{{:config('goods.image.default')}}';
            var color = $.h.goods.colorFormtter('',rowData,rowIndex);
            var size = $.h.goods.sizeFormtter('',rowData,rowIndex);
            return '<div style=\'width:100%;padding:10px 0px;\'>' +
				'<div style=\'width:135px;float:left;\'>' +
                '<img src=\''+ first_image +'\' style=\'width:125px;height:160px;\'>' +
                '</div>' +
                '<div style=\'background-color:red;\'>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>商品名称：</span>'+rowData['goods_name']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>商品货号：</span>'+rowData['goods_code']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>采购价：</span>￥'+rowData['goods_pprice']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>批发价：</span>￥'+rowData['goods_wprice']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>建议零售价：</span>￥'+rowData['goods_srprice']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>零售价：</span>￥'+rowData['goods_rprice']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:520px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>商品颜色：</span>'+color+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:520px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><span style=\'color:red;font-weight:bold;\'>商品尺码：</span>'+size+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>商品条码：</b>'+rowData['goods_barcode']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>起订量：</b>'+rowData['goods_bnumber']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>商品分类：</b>'+rowData['category_name']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>商品品牌：</b>'+rowData['detail_info']['branch_name']+'</div>' +
                    '</div>' +
					'<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>商品性别：</b>'+sex+'</div>' +
                    '</div>' +
					'<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>单位名称：</b>'+rowData['detail_info']['unit_name']+'</div>' +
                    '</div>' +
					'<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>商品年份：</b>'+rowData['goods_year']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>商品季节：</b>'+ rowData['detail_info']['season_name'] +'</div>' +
                    '</div>' +
					'<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>库存下限：</b>'+rowData['goods_llimit']+'</div>' +
                    '</div>' +
					'<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>库存上限：</b>'+rowData['goods_ulimit']+'</div>' +
                    '</div>' +
                    '<div style=\'float:left; width:260px; min-width:180px; margin-bottom:10px;\'>' +
                    '<div style=\'height:22px;line-height:22px;\'><b>商品状态：</b>'+status+'</div>' +
                    '</div>' +
                '<div>' +
                '</div>';
        },
		onExpandRow:function(index,row){
			$(this).datagrid('resize');
		},
        onLoadSuccess:function () {
            $(this).datagrid('fixRownumber');
        },
        onClickCell:$.h.goods.onGoodsClickCell
    " xmlns="http://www.w3.org/1999/html">
    <thead>
    <tr>
        <th data-options="field:'goods_id',width:50"><b>ID</b></th>
        <th data-options="field:'goods_name',width:100"><b>商品名称</b></th>
        <th data-options="field:'goods_code',width:70"><b>货号</b></th>
        <th data-options="
                           field:'color_names',
                           width:100,
                           align:'center',
                           formatter:$.h.goods.colorFormtter
                        ">
            <b>颜色</b>
        </th>
        <th data-options="
                        field:'size_names',
                        width:100,
                        align:'center',
                        formatter:$.h.goods.sizeFormtter
        ">
            <b>尺码</b>
        </th>
        <th data-options="field:'goods_pprice',width:50,align:'right',editor:{type:'numberbox',options:{precision:1}},formatter:function(value, row){ return '￥' + value; }"><b>采购价</b></th>
        <th data-options="field:'goods_wprice',width:50,align:'right',editor:'numberbox',formatter:function(value, row){ if (value == null) { return 8.5; } else { return value; } }"><b>批发价</b></th>
        <th data-options="field:'goods_rprice',width:50,align:'right',formatter:function(value, row){ return '￥' + value; }"><b>零售价</b></th>
        <th data-options="field:'goods_srprice',width:60,align:'right',editor:'numberbox',formatter:function(value, row){ return '￥' + value; }"><b>建议零售价</b></th>
        <th data-options="field:'goods_year',width:40,align:'center'"><b>年份</b></th>
        <th data-options="field:'goods_status',width:40,align:'center',formatter:function(value, row){
         if(value == 1){
         return '启用'
         }else{
         return '禁用'
         }
        ;}"><b>状态</b></th>
        <!--th data-options="field:'goods_id1',align:'center',formatter:$.h.goods.copyFormatter">复制</th-->
        <th data-options="field:'goods_id2',align:'center',formatter:$.h.goods.modifyFormatter">修改</th>
        <th data-options="field:'goods_id3',align:'center',formatter:$.h.goods.delFormatter">删除</th>
    </tr>
    </thead>
</table>
<div id="goods_grid_toolbar" style="padding-left:5px;">
    商品名称：<input id="goods_name_query" class="easyui-textbox" style="width:120px;"/>
    货号：<input id="goods_code_query" class="easyui-textbox" style="width:120px;"/>
    条码：<input id="goods_barcode_query" class="easyui-textbox" style="width:120px;"/>
    <select id="goods_status_query" class="easyui-combobox" style="width:100px;" data-options="panelHeight:'auto'">
    <option value="all">全部</option>
    <option value= 1>启用</option>
    <option value= 0>禁用</option>
    </select>
    <a href="javascript:void(0)" query='1' class="easyui-linkbutton" data-options="iconCls:'icon-search',plain:true,onClick:$.h.goods.grid.onQuery">搜索</a>
    <a href="javascript:void(0)" query='0' class="easyui-linkbutton" data-options="iconCls:'icon-back',plain:true,onClick:$.h.goods.grid.onQuery">显示全部</a>
    <span style="float:right;">
        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="">导出</a>
		<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="">导入</a>
    </span>
</div>