/**
 * 采购订单
 */
(function ($) {

    $.h.bookingOrders = {
        append: function () {
            $('#booking_orders_grid_details').datagrid('appendRow', {});
        },
        removeit: function removeit() {
            editIndex = $('#booking_orders_grid_details').datagrid('getRows').length - 1
            $('#booking_orders_grid_details').datagrid('cancelEdit', editIndex).datagrid('deleteRow', editIndex);
        },
        reloadFooter: function () {
            rows = $('#booking_orders_grid_details').datagrid('getRows');
            length = rows.length;
            let total_number = 0;
            for (let i = 0; i < length; ++i) {
                total_number += parseInt(rows[i].goods_number);
            }
            data = [{goods_name: "合计", goods_number: total_number}];
            $('#booking_orders_grid_details').datagrid('reloadFooter', data);
        },
        mergeGood: function () {
            let rows = $('#booking_orders_grid_details').datagrid('getRows');
            let length = rows.length;
            let tail = length - 1;
            if(tail == 0){
                return;
            }
            let nowrowinfo = ''+rows[tail].goods_id+'%'+rows[tail].color_id+'%'+rows[tail].size_id+'%'+rows[tail].price;
            for (let i = 0; i<length-1;i++){
                let rowsinfo = ''+rows[i].goods_id+'%'+rows[i].color_id+'%'+rows[i].size_id+'%'+rows[i].price;
                if(nowrowinfo == rowsinfo){
                    rows[i].goods_number = parseInt(rows[tail].goods_number) + parseInt(rows[i].goods_number);
                    rows.splice(tail,1);
                }
            }
            let data = {rows:rows}
            console.log(rows);
            $('#booking_orders_grid_details').datagrid('loadData',data);
        }
    }
})(jQuery);