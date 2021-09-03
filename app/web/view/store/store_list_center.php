<div class="easyui-panel" data-options="fit:true">
    <table id="goods_grid" class="easyui-datagrid" style="width:100%;"
           data-options="
            url:$.toUrl('store', 'queryGoods'),
            fitColumns:true,
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
            pageSize: 30,
            pageList: [20,30,50],
            view:detailview,
            detailFormatter:$.h.sr.detailFormatter,
            onExpandRow:$.h.sr.onExpandRow
        ">
        <thead>
        <tr>
            <th data-options="field:'stock_id',checkbox:true"></th>
            <th data-options="
                    field:'id',
                    formatter:function(value,row) {
                        return '<img src=\''+ row.assist_url +'\' href=\'javascript:alert('+row.oid+');\' class=\'datagrid-row-add\' style=\'display:inline-block;width:20px;height:20px;\'></img>';
                    }
                "></th>
            <th data-options="field:'goods_name',width:11"><b>商品</b></th>
            <th data-options="field:'color_names',width:8,formatter:function(value,row){

                    return row.row_color_name.join(',');
            }"><b>颜色</b></th>
            <th data-options="field:'size_names',width:3,formatter:function(value,row){

                   return row.row_size_name.join(',');
            }">
                <b>尺码</b>
            </th>
            <th data-options="field:'category_name',width:5,align:'left'"><b>分类</b></th>
            <th data-options="field:'warehouse_name',width:6,align:'left'"><b>仓库</b></th>

            <th data-options="field:'brand_name',width:6,align:'center'"><b>品牌</b></th>
            <th data-options="field:'unit_name',align:'center'">&nbsp;<b>单位</b>&nbsp;</th>
<!--            <th data-options="-->
<!--                    field:'goods_season',-->
<!--                    align:'center',-->
<!--                    formatter: function(value,row,index) {-->
<!--                        switch(value) {case 1:return '春';case 2:return'夏';case 3:return'秋';case 4:return'冬';}-->
<!--                    }-->
<!--                ">&nbsp;<b>季节</b>&nbsp;</th>-->
            <th data-options="field:'goods_year',align:'center'">&nbsp;<b>年份</b>&nbsp;</th>
            <th data-options="field:'goods_code',width:8"><b>货号</b></th>
            <th data-options="field:'goods_barcode',width:7"><b>条码</b></th>
            <th data-options="
                    field:'total_stock_number',
                    align:'center',
                    styler:function(){
                        return 'background-color:#ffee00;color:red;';}
                    ">&nbsp;<b>库存数量</b>&nbsp;</th>
        </tr>
        </thead>
    </table>
</div>