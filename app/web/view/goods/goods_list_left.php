<div id="goods_tabs" class="easyui-tabs"
     data-options="
		fit:true,
		tools:'#goods_tabs_tools',
		border:false,
		justified:true,
		plain:true,
		onSelect: function(title,index) {

		}
	">
    <div title='通用信息' style="padding:10px;"
         data-options="">
        <form id="goods_base_from" method="post">
            <div class="form-clo1">
                <div class="name">商品名称：</div>
                <div class="ctl">
                    <input id="goods_name" name="goods_name" class="easyui-textbox" data-options="required:true" style="width:100%;"/>
                </div>
                <div class="name">商品货号：</div>
                <div class="ctl">
                    <input id="goods_code" name="goods_code" class="easyui-textbox" style="width:100%;" data-options="required:true"/>
                </div>
            </div>

            <div class="form-clo1">
                <div class="name">采购价：</div>
                <div class="ctl">
                    <input id="goods_pprice" name="goods_pprice" class="easyui-numberspinner" style="width:100%;" value="0" data-options="required:true,min:0,max:9999999,prefix:'￥',precision:2"/>
                </div>
                <div class="name">批发价：</div>
                <div class="ctl">
                    <input id="goods_wprice" name="goods_wprice" class="easyui-numberspinner" style="width:100%;" value="0" data-options="required:true,min:0,max:9999999,prefix:'￥',precision:2"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name" >建议零售：</div>
                <div class="ctl">
                    <input id="goods_srprice" name="goods_srprice" class="easyui-numberspinner" style="width:100%;" value="0" data-options="min:0,max:9999999,prefix:'￥',precision:2"/>
                </div>
                <div class="name">零售价：</div>
                <div class="ctl">
                    <input id="goods_rprice" name="goods_rprice" class="easyui-numberspinner" style="width:100%;" value="0" data-options="required:true,min:0,max:9999999,prefix:'￥',precision:2"/>
                </div>
            </div>
            <div class="form-panel">
                <div class="form-clo1">
                    <div class="name">商品颜色：</div>
                    <div class="ctl">
                        <select id="color_ids" class="easyui-combotreegrid" style="width:100%;"
                                data-options="
									multiple:true,
									required:true,
									fitColumns: true,
									rownumbers:true,
									onlyLeafCheck:true,
									panelHeight:'auto',
									icons:[{
										iconCls:'icon-clear',
										handler: $.h.c.clearCombotreegrid
									}],
									url:'/web/color/loadColorList',
									idField:'color_id',
									treeField:'color_name',
									onHidePanel : $.h.goods.onBuildColorSize,
									columns:[[
										{field:'color_id',hidden:true},
										{field:'color_name',title:'颜色名称',width:200}

									]]
								">
                        </select>
                    </div>
                </div>
               <!-- {field:'color_sort',title:'&nbsp;排序&nbsp;',align:'center'}-->
                <div class="form-clo1">
                    <div class="name">商品尺码：</div>
                    <div class="ctl">
                        <select id="size_ids" class="easyui-combotreegrid" style="width:100%;"
                                data-options="
									multiple:true,
									required:true,
									fitColumns: true,
									rownumbers:true,
									onlyLeafCheck:true,
									panelHeight:380,
									icons:[{
										iconCls:'icon-clear',
										handler: $.h.c.clearCombotreegrid
									}],
									url:'/web/size/getSizeList',
									idField:'size_id',
									treeField:'size_name',
									onHidePanel : $.h.goods.onBuildColorSize,
									columns:[[
										{field:'size_id',hidden:true},
										{field:'size_name',title:'尺码名称',width:200}

									]]
								">
                        </select>
                    </div>
                </div>
            </div>
           <!-- {field:'size_sort',title:'&nbsp;排序&nbsp;',align:'center'}-->
            <div class="form-clo1">
                <div class="name">商品条码：</div>
                <div class="ctl">
                    <input id="goods_barcode" name="goods_barcode" class="easyui-textbox" style="width:100%;"/>
                </div>
                <div class="name">起订量：</div>
                <div class="ctl">
                    <input id="goods_bnumber" name="goods_bnumber" class="easyui-numberspinner" style="width:100%;" data-options="min:0,max:9999999,value:0"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name">商品分类：</div>
                <div class="ctl">
                    <select id="category_id" name="category_id" class="easyui-combotree" style="width:100%;"
                            data-options="
                                url:'/web/category/loadData',
                                panelHeight:'auto',
                                lines:true
                            ">

                    </select>
                </div>
                <div class="name">商品品牌：</div>
                <div class="ctl">
                    <input id="brand_id" name="brand_id" class="easyui-combobox" style="width:100%;"
                           data-options="
                                url:'/web/dict/loadList?dict_type=brand',
                                panelHeight:'auto',
                                valueField:'dict_id',
                                textField:'dict_name',
                                editable:false
                    "/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name">商品季节：</div>
                <div class="ctl">
                    <select id="goods_season" name="goods_season" class="easyui-combobox" style="width:100%;"
                            data-options="
                                url:'/web/dict/loadList?dict_type=season',
                                lines:true,
                                valueField: 'dict_id',
                                textField: 'dict_name',
                                panelHeight:'auto',
                                editable:false
                            ">

                    </select>
                </div>
                <div class="name">商品材质：</div>
                <div class="ctl">
                    <input id="material_id" name="material_id" class="easyui-combobox" style="width:100%;"
                           data-options="
                                url:'/web/dict/loadList?dict_type=material',
                                lines:true,
                                valueField: 'dict_id',
                                textField: 'dict_name',
                                panelHeight:'auto',
                                editable:false
                            "/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name">商品性别：</div>
                <div class="ctl">
                    <select id="goods_sex" name="goods_sex" class="easyui-combobox" style="width:100%;" data-options="panelHeight:'auto',editable:false">
                        <option value="0">女装</option>
                        <option value="1">男装</option>
                        <option value="2">童装</option>
                    </select>
                </div>
                <div class="name">单位：</div>
                <div class="ctl">
                    <input id="unit_id" name="unit_id" class="easyui-combobox" style="width:100%;"
                           data-options="
                                url:'/web/dict/loadList?dict_type=unit',
                                lines:true,
                                valueField: 'dict_id',
                                textField: 'dict_name',
                                panelHeight:'auto',
                                editable:false
                           ">

                    </input>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name">年份：</div>
                <div class="ctl">
                    <input id="goods_year" name="goods_year" class="easyui-numberspinner" style="width:110px;"  data-options="spinAlign:'horizontal',min:1900,max:2500,precision:0"/>
                </div>
                <div class="name">排序：</div>
                <div class="ctl">
                    <input id="goods_sort" name="goods_sort" class="easyui-numberspinner" style="width:100px;" data-options="value:100,required:true,labelPosition:'top',spinAlign:'horizontal',min:0,max:9999999,precision:0"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name">状态：</div>
                <div class="ctl">
                    <input id="goods_status" name="goods_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,height:24" checked></input>
                </div>
                <!--<div class="name">款式：</div>
                <div class="ctl">
                    <input id="goods_status" name="goods_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,height:24" checked></input>
                </div>-->
                <input type="hidden" id="goods_id">
            </div>
            <div class="form-panel">
                <div class="form-clo1">
                    <div class="name" style="color: blue">库存预警：</div>
                    <div class="ctl">
                        <input id="goods_llimit" name="goods_llimit" class="easyui-numberspinner" value="0" style="width:100%;" data-options="label:'<b>下限</b>',labelWidth:35,labelPosition:'left',min:0,max:9999999,precision:0"/>
                    </div>
                    <div class="name" style="color: blue">库存预警：</div>
                    <div class="ctl">
                        <input id="goods_ulimit" name="goods_ulimit" class="easyui-numberspinner" value="0" style="width:100%;" data-options="label:'<b>上限</b>',labelWidth:35,labelPosition:'left',min:0,max:9999999,precision:0"/>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div title='单品条码'>
        <table id="goods_details_grid" class="easyui-datagrid"
               data-options="
				fit: true,
				fitColumns: true,
				border: false,
				toolbar: '#goods_details_grid_tollbar',
				iconCls: 'kbi-icon-record',
				singleSelect: true,
                onBeforeLoad : $.h.goods.grid.onBeforeLoad,
                onClickCell : $.h.goods.grid.onClickCell
			">
            <thead>
            <tr>
                <th data-options="field:'details_id',hidden:true"></th>
                <th data-options="
                        field:'color_name',
                        width:90,
                        align:'center',
                        styler: function(value,row,index) {
                            return 'background-color: #fafafa;background: -webkit-linear-gradient(top,#fdfdfd 0,#f5f5f5 100%);background: -moz-linear-gradient(top,#fdfdfd 0,#f5f5f5 100%);background: -o-linear-gradient(top,#fdfdfd 0,#f5f5f5 100%);background: linear-gradient(to bottom,#fdfdfd 0,#f5f5f5 100%);background-repeat: repeat-x;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#fdfdfd,endColorstr=#f5f5f5,GradientType=0);'
                        },
                        formatter:function(value,row,index) {
                            return '<div class=\'datagrid-cell-rownumber\' style=\'width:100%;text-align:center;\'>'+value+'</div>';
                        }"><strong>颜色</strong></th>
                <th data-options="
                        field:'size_name',
                        width:70,
                        align:'center',
                        styler: function(value,row,index) {
                            return 'background-color: #fafafa;background: -webkit-linear-gradient(top,#fdfdfd 0,#f5f5f5 100%);background: -moz-linear-gradient(top,#fdfdfd 0,#f5f5f5 100%);background: -o-linear-gradient(top,#fdfdfd 0,#f5f5f5 100%);background: linear-gradient(to bottom,#fdfdfd 0,#f5f5f5 100%);background-repeat: repeat-x;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#fdfdfd,endColorstr=#f5f5f5,GradientType=0);'
                        },
                        formatter:function(value,row,index) {
                            return '<div class=\'datagrid-cell-rownumber\' style=\'width:100%;text-align:center;\'>'+value+'</div>';
                        }"><strong>尺码</strong></th>
                <th data-options="field:'goods_scode',editor:'textbox',width:150,align:'center'"><strong>单品货号</strong></th>
                <th data-options="field:'goods_sbarcode',editor:'textbox',width:150,align:'center'"><strong>单品条码</strong></th>
            </tr>
            </thead>
        </table>
        <div id="goods_details_grid_tollbar">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-edit',plain:true">设置</a>
            <span style="float:right;">
				<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true">保存</a>
			</span>
        </div>
    </div>
    <div title='商品相册'>
        <div id="goods_grid_toolbar" style="padding-left:5px;" class="datagrid-toolbar">
            <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-home',plain:true">历史图片</a>
            <span style="float:right;">
				<a id="btn_upload" href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-upload',plain:true,disabled:true">上传图片</a>
			</span>
        </div>
        <div style="padding-left:10px;padding-top:10px;">
            <ul class="upload-ul clearfix">
                <li class="upload-pick">
                    <div class="webuploader-container clearfix" id="goodsUpload"></div>
                </li>
            </ul>
        </div>
    </div>
</div>
<div id="goods_tabs_tools" style="border-right:0px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add',onClick:$.h.goods.onAddNew">新建商品</a>
</div>