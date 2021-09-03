/**
 * 销售订单
 */
(function ($) {

    $.h.marketSo = {
        append: function () {
            $('#market_So_list_details').datagrid('appendRow', {});
        },
        removeit: function removeit() {
            editIndex = $('#market_So_list_details').datagrid('getRows').length - 1
            $('#market_So_list_details').datagrid('cancelEdit', editIndex).datagrid('deleteRow', editIndex);
        },
        reloadFooter: function () {
            rows = $('#market_So_list_details').datagrid('getRows');
            length = rows.length;
            let total_number = 0;
            for (let i = 0; i < length; ++i) {
                total_number += parseInt(rows[i].goods_number);
            }
            data = [{goods_name: "合计", goods_number: total_number}];
            $('#market_So_list_details').datagrid('reloadFooter', data);
        },
        mergeGood: function () {
            let rows = $('#market_So_list_details').datagrid('getRows');
            let length = rows.length;
            let tail = length - 1;
            if(tail == 0){
                return;
            }
            let nowrowinfo = ''+rows[tail].goods_id+'%'+rows[tail].color_id+'%'+rows[tail].size_id;
            for (let i = 0; i<length-1;i++){
                let rowsinfo = ''+rows[i].goods_id+'%'+rows[i].color_id+'%'+rows[i].size_id;
                if(nowrowinfo == rowsinfo){
                    rows[i].goods_number = parseInt(rows[tail].goods_number) + parseInt(rows[i].goods_number);
                    rows.splice(tail,1);
                }
            }
            let data = {rows:rows}
            $('#market_So_list_details').datagrid('loadData',data);
        },
        /**
         * 刷新销售订单脚合计
         */
        marketSoFooter:function () {
            rows = $('#market_So_list_details').datagrid('getRows');
            length = rows.length;
            let total_number = 0;
            let total_money =0;
            for (let i = 0; i < length; ++i) {
                total_number += parseInt(rows[i].goods_number);
                total_money += parseInt(rows[i].price)*parseInt(rows[i].goods_number);
            }
            $("#goods_number").numberbox('setValue',total_number);
            $("#goods_price").numberbox('setValue',total_money);
        }
    }
})(jQuery);