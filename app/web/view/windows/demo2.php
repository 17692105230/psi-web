<div class="easyui-panel" data-options="footer:'#win_base_demo2_form_footer'" style="width:100%;height:100%;padding:15px 10px;overflow-y:hidden;">
    <form id="win_base_demo2_form" method="post">
    <div style="margin-right:20px;">
		<div class="form-clo1">
			<div class="name">URL地址:</div>
			<div class="ctl">
				<div><input class="easyui-textbox" style="width:100%" /input></div>
				<div class="tag">地址</div>
			</div>
		</div>
		<div class="form-clo2">
			<div class="name w">上级节点:</div>
			<div class="ctl w">
				<div><input class="easyui-textbox" style="width:100%" /input></div>
				<div class="tag">启用后在新条码</div>
			</div>
			<div class="name w">节点名称:</div>
			<div class="ctl w">
				<div><input class="easyui-textbox" style="width:100%" /input></div>
				<div class="tag">启用后在新建生成条码</div>
			</div>
		</div>
		<div class="form-clo2">
			<div class="name w">类型:</div>
			<div class="ctl w">
				<div>
					<select class="easyui-combobox" data-options="panelHeight:'auto',editable:false" style="width:100%;">
                        <option value="0">上级节点</option>
                        <option value="1">视图节点</option>
                        <option value="2">数据节点</option>
                    </select>
				</div>
				<div class="tag">启用后在新条码</div>
			</div>
			<div class="name w">是否菜单:</div>
			<div class="ctl w">
				<div><input class="easyui-switchbutton" data-options="onText:'是',offText:'否',width:80"></div>
				<div class="tag">启用后在新建生成条码</div>
			</div>
		</div>
		<div class="form-clo2">
			<div class="name w">菜单名称:</div>
			<div class="ctl w">
				<div><input class="easyui-textbox" style="width:100%" /input></div>
				<div class="tag">启用后在新条码</div>
			</div>
			<div class="name w">访问上限:</div>
			<div class="ctl w">
				<div><input class="easyui-textbox" style="width:100%" /input></div>
				<div class="tag">启用后在新建生成条码</div>
			</div>
		</div>
		<div class="form-clo2">
			<div class="name w">排序:</div>
			<div class="ctl w">
				<div><input class="easyui-numberspinner" style="text-align:center;" data-options="width:120,value:100,spinAlign:'horizontal',min:1,max:99999"/></div>
				<div class="tag"></div>
			</div>
		</div>
    </div>
    </form>
    <div id="win_base_demo2_form_footer" style="padding:5px;overflow:hidden;">
        <div style="float:left;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseDemo2.onClose" style="width:200px;margin-right:20px;">为无条码商品生成条码</a>
        </div>
        <div style="float:right;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-reload',onClick:$.h.window.winBaseDemo2.onClose" style="width:100px;">重置</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseDemo2.onSave" style="width:100px;">保存</a>
        </div>
    </div>
</div>
	