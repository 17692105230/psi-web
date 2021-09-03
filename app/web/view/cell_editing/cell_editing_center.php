<div class="easyui-layout" data-options="fit:true">
	<div style="overflow-x:hidden;padding:10px 10px 0px 10px;width:100%;height:90px;border-bottom:1px solid #ddd;background-color:#eee;"
		data-options="
			region:'north',
			title:'权限节点列表',
			split:true,
			hideCollapsedContent:false,
			onResize: function(w, h) {}
	">
		<form id="module_form" method="post">
			<div class="form-clo1">
				<div class="name w120">模块名称:</div>
				<div class="ctl w120">
					<input id="module_name" name="module_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
				</div>
				<div class="name w120">控制器名称:</div>
				<div class="ctl w120">
					<input id="controller_name" name="controller_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
				</div>
				<div class="name w120">操作名称:</div>
				<div class="ctl w120">
					<input id="action_name" name="action_name" type="text" class="easyui-textbox" data-options="required:true" style="width:100%;" />
				</div>
			</div>
		</form>
	</div>
	<div data-options="border:false,region:'center'" style="overflow:hidden;">
        <table id="orders_grid_details" class="easyui-datagrid" style="width:100%;"
            data-options="
                fit:true,
                fitColumns:true,
                iconCls:'kbi-icon-record',
                singleSelect:true,
                rownumbers:true,
                pagination: true,
                pageSize: 20,
                pageList: [20,30,50,100],
                border:true,
                toolbar:'#orders_grid_details_toolbar',
                method:'post',
                checkOnSelect:false,
                selectOnCheck:false,
				url:$.toUrl('cell_editing', 'loadInventoryDetails'),
                onBeforeLoad:function(param) {
					$(this).datagrid('options').editFields = ['color_id','size_id','goods_number'];
					$(this).datagrid('bindKeyEvent');
				},
                onClickCell:function(rowIndex, field, value, row) {
					var opts = $(this).datagrid('options');
					if (opts.endEditing.call(this)) {
						for(var i = 0, n = opts.editFields.length; i < n; i++) {
							if (field == opts.editFields[i]) {
								$(this).datagrid('editCell', {index:rowIndex,field:field});
								opts.editIndex = rowIndex;
								opts.editFieldIndex = i;
								switch (field) {
									case 'color_id': {
										var ed = $(this).datagrid('getEditor', {index:rowIndex,field:'color_id'});
										$(ed.target).combobox('setValue', row.color_id).combobox('setText', row.color_name);
										$(ed.target).combobox({
											url:$.toUrl('cell_editing', 'getGoodsColorData'),
											queryParams:{
												goods_code:row.goods_code
											},
											onLoadSuccess : function() {
												var arr = {'colors':$(this).combobox('getData')};
												$(this).combobox('select', row.color_id);
											}
										});
										break;
									}
									case 'size_id': {
										var ed = $(this).datagrid('getEditor', {index:rowIndex,field:'size_id'});
										$(ed.target).combobox('setValue', row.size_id).combobox('setText', row.size_name);
										$(ed.target).combobox({
											url:$.toUrl('cell_editing', 'getGoodsSizeData'),
											queryParams:{
												goods_code:row.goods_code
											},
											onLoadSuccess : function() {
												var arr = {'sizes':$(this).combobox('getData')};
												$(this).combobox('select', row.size_id);
											}
										});
										break;
									}
								}
							}
						}
					}
				},
                onEndEdit:function(index, row) {
					var edColor = $(this).datagrid('getEditor', {index:index,field:'color_id'});
					if (edColor && edColor.type == 'combobox' && $.trim($(edColor.target).combobox('getText')) != '') {
						row.color_name = $(edColor.target).combobox('getText');
					}
					var edSize = $(this).datagrid('getEditor', {index:index,field:'size_id'});
					if (edSize && edSize.type == 'combobox' && $.trim($(edSize.target).combobox('getText')) != '') {
						row.size_name = $(edSize.target).combobox('getText');
					}
				},
                onAfterEdit:function(index, row, changes) {
					if (!$.isEmptyObject(changes)) {
						var arr = new Array();
						var name,value;
						for(key in changes) {
							name = key;
							value = changes[key];
						}
						switch (name) {
							case 'goods_number':
								/*商品数量*/
								console.log('商品数量更改为：' + value)
								break;
							case 'color_id':
								console.log('颜色更改为：' + value)
								break;
							case 'size_id':
								console.log('尺寸更改为：' + value)
								break;
						}
					}
				},
                onAfterLoad:function(param) {
					
				},
                onKeyUpDownEvent:function(rowIndex, field, value, row) {
					var opts = $(this).datagrid('options');
					if (opts.endEditing.call(this)) {
						for(var i = 0, n = opts.editFields.length; i < n; i++) {
							if (field == opts.editFields[i]) {
								$(this).datagrid('editCell', {index:rowIndex,field:field});
								opts.editIndex = rowIndex;
								opts.editFieldIndex = i;
								switch (field) {
									case 'color_id': {
										console.log('颜色');
										break;
									}
									case 'size_id': {
										console.log('尺寸');
										break;
									}
								}
							}
						}
					}
				}
            ">
            <thead>
                <tr>
                    <th data-options="field:'details_id',checkbox:true"></th>
                    <th data-options="field:'goods_name',width:200,sortable:true"><b>商品</b></th>
                    <th data-options="field:'goods_barcode',width:200,sortable:true"><b>条码</b></th>
                    <th data-options="field:'goods_code',width:200,sortable:true"><b>货号</b></th>
                    <th data-options="
                            field:'color_id',
                            editor:{
                                type:'combobox',
                                options:{
                                    editable:false,
                                    panelHeight:80,
                                    valueField:'color_id',
                                    textField:'color_name',
                                    panelHeight:'auto',
                                    panelMaxHeight:200
                                }
                            },
                            fixed:true,
                            width:120,
                            align:'center',
                            formatter:function(value,row,index) {
                                return row.color_name;
                            }
                        "><b>颜色</b></th>
                    <th data-options="
                            field:'size_id',
                            editor:{
                                type:'combobox',
                                options:{
                                    editable:false,
                                    panelHeight:80,
                                    valueField:'size_id',
                                    textField:'size_name',
                                    panelHeight:'auto',
                                    panelMaxHeight:200
                                }
                            },
                            fixed:true,
                            width:120,
                            align:'center',
                            formatter:function(value,row,index) {
                                return row.size_name;
                            }
                        "><b>尺码</b></th>
                    <th data-options="
                            fixed:true,
                            width:100,
                            field:'goods_number',
                            editor:{
                                type:'numberbox'
                            },
                            align:'center'
                        "><b>盘点数量</b></th>
                    <th data-options="
                            field:'goods_anumber',
                            fixed:true,
                            width:100,
                            align:'center'
                        "><b>盘点前数量</b></th>
                </tr>
            </thead>
        </table>
        <div id="orders_grid_details_toolbar" style="height:33px;padding:1px;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true">删除</a>
            <input class="easyui-switchbutton" data-options="onText:'查询',offText:'扫码',width:80,checked:true"/>
            <span style="color:#ccc;">|</span>
            <input id="children_orders" class="easyui-combobox" data-options="
                buttonText:'盘点人',
                buttonAlign:'left',
                editable:false,
                panelHeight:'auto',
                panelMaxHeight:200,
                width:260,
                valueField:'children_code',
                textField:'children_name',
                icons: [{
                    iconCls:'icon-add',
                    handler:function(e) {}
                },{
                    iconCls:'icon-remove',
                    handler:function(e) {}
                }],
                onSelect:function(record) {}
            "/>
            <div style="float:right;">
                <a id="btnGridQuery" class="easyui-linkbutton easyui-tooltip"
                    data-options="
                        showEvent:'click',
                        hideEvent:'none',
                        iconCls:'icon-search',
                        plain:true,
                        position:'bottom',
                        content: function(){
                            return $('#btn_grid_query_div');
                        },
                        onShow: function(){
                            var t = $(this);
                            $(document).one('click', function(e) { t.tooltip('hide'); });
                            t.tooltip('tip').click(function() { event.stopPropagation(); });
                        }
                    ">搜索商品</a>
                <span style="color:#ccc;">|</span>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true">提交销售</a>
                <a class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true">保存草稿</a>
            </div>
        </div>
        <div style="display:none;">
            <div id="btn_grid_query_div" class="easyui-panel" data-options="width:300,height:220" style="background-color:#eee;padding:10px 5px 0px 10px;">
                <form id="btn_grid_query_from" method="post">
                        <div class="form-clo1">
                            <div class="name w">商品:</div>
                            <div class="ctl w">
                                <input id="ws_goods" name="ws_goods" class="easyui-textbox" data-options="width:200,prompt:'名称、货号、条码...'"/>
                            </div>
                        </div>
                        <div class="form-clo1">
                            <div class="name w">商品颜色:</div>
                            <div class="ctl w">
                                <input id="ws_color_id" name="ws_color_id" class="easyui-combobox"
                                    data-options="
                                        valueField:'color_id',
                                        textField:'color_name',
                                        panelHeight:'auto',
                                        panelMaxHeight:200,
                                        width:200,
                                        editable:false,
                                        prompt:'选择颜色...',
                                        valueField:'value',
										textField:'label',
										data: [{label:'红色',value:'1'},{label:'黄色',value:'2'},{label:'绿色',value:'3'}]"
                                    " /input>
                            </div>
                        </div>
                        <div class="form-clo1">
                            <div class="name w">商品尺码:</div>
                            <div class="ctl w">
                                <input id="ws_size_id" name="ws_size_id" class="easyui-combobox"
                                    data-options="
                                        valueField:'size_id',
                                        textField:'size_name',
                                        panelHeight:'auto',
                                        panelMaxHeight:200,
                                        width:200,
                                        editable:false,
                                        prompt:'选择尺码...',
                                        valueField:'value',
										textField:'label',
										data: [{label:'S',value:'1'},{label:'M',value:'2'},{label:'3XL',value:'3'}]"
                                    " /input>
                            </div>
                        </div>
                        <div class="form-clo1">
                            <div class="name w">盈亏状态:</div>
                            <div class="ctl w">
                                <input id="ws_gain_loss" name="ws_gain_loss" class="easyui-combobox" data-options="
                                    editable:false,
                                    prompt:'请选择...',
                                    panelHeight:'auto',
                                    panelMaxHeight:380,
                                    width:200,
                                    valueField:'value',
                                    textField:'label',
                                    data: [{label:'盘盈',value:'1'},{label:'正常',value:'2'},{label:'盘亏',value:'3'}]"
                                />
                            </div>
                        </div>
                        <div class="form-clo1">
                            <div class="name w">
                                <a class="easyui-linkbutton" data-options="height:35,width:60">重置</a>
                            </div>
                            <div class="ctl w">
                                <a class="easyui-linkbutton" data-options="iconCls:'icon-search',height:35,width:200">查 询</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>