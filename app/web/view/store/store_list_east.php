<div class="easyui-panel" style="padding:10px;background-color:#eee;"
     data-options="
		fit:true,
		border:false
	">

        <form id="query_form" method="get">
            <div class="form-clo1">
                <div class="name w">仓库:</div>
                <div class="ctl w">
                    <input id="warehouse_id" name="warehouse_id" class="easyui-combobox" style="width:100%;"
                           data-options="
						valueField:'org_id',
						textField:'org_name',
                        prompt:'选择仓库...',
						panelHeight:'auto',
                        panelMaxHeight:120,
						editable:false,
						url:'/web/organization/loadWarehouseList'
					"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">商品:</div>
                <div class="ctl w">
                    <input id="goods_ncb" name="goods_ncb" class="easyui-textbox" data-options="prompt:'名称、货号、条码'" style="width:100%;"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">分类:</div>
                <div class="ctl w">
                    <select id="category_id" name="category_id" class="easyui-combotree" style="width:100%;" data-options="panelHeight:'auto',url:'/web/category/loadData',lines:true,prompt:'选择分类...'"></select>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">品牌:</div>
                <div class="ctl w">
                    <input id="bland_id" name="bland_id" class="easyui-combobox" style="width:100%;" data-options="url:'/web/dict/loadlist?dict_type=brand',panelHeight:135,valueField:'dict_id',textField:'dict_name', panelHeight:'auto',editable:false,prompt:'选择品牌...'"></input>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">年份:</div>
                <div class="ctl w">
                    <input id="goods_year" name="goods_year" class="easyui-numberspinner" data-options="spinAlign:'horizontal',min:1980,max:2100,precision:0"/>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">季节:</div>
                <div class="ctl w">
                    <input id="goods_season" name="goods_season" class="easyui-combobox" style="width:100%;"
                           data-options="
						valueField:'dict_id',
						textField:'dict_name',
                        prompt:'选择季节...',
						panelHeight:'auto',
						editable:false,
						url:'/web/dict/loadList?dict_type=season'
					" /input>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w">库存预警:</div>
                <div class="ctl w">
                    <input id="goods_warn" name="goods_warn" class="easyui-switchbutton" data-options="onText:'全部',offText:'预警',onChange:$.h.sr.changeSwitchBtn,value:1,width:82" checked>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w"></div>
                <div class="ctl w" style='color:blue;'><i>达到库存预警线的商品</i></div>
            </div>
            <div class="form-clo1">
                <div class="name w">库存类型:</div>
                <div class="ctl w">
                    <input id="goods_no" name="goods_no" class="easyui-switchbutton" data-options="onText:'全部',offText:'无库存',onChange:$.h.sr.changeSwitchBtn,value:1,width:82" checked />
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w"></div>
                <div class="ctl w" style='color:blue;'><i>库存为零或小于零</i></div>
            </div>
        </form>
        <div class="form-clo1">
            <div class="name w">
                <a id="btn" class="easyui-linkbutton" data-options="iconCls:'icon-clear',onClick:$.h.sr.btnReset" style="width:100%;height:30px;">重置</a>
            </div>
            <div class="ctl w" style="text-align:right">
                <a id="btn" class="easyui-linkbutton" data-options="iconCls:'icon-search',onClick:$.h.sr.btnQuery" style="width:100%;height:30px;">查询</a>
            </div>
        </div>
 
</div>