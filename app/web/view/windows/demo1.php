<div class="easyui-panel" data-options="footer:'#win_base_demo1_form_footer'" style="width:100%;height:100%;padding:15px 10px;">
    <form id="win_base_demo1_form" method="post">
		<div class="form-clo1">
			<div class="name w120">品牌名称:</div>
			<div class="ctl w120">
				<input id="dict_name" name="dict_name" class="easyui-textbox" type="text" data-options="required:true" style="width:100%;"></input>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w120">自动生成条码:</div>
			<div class="ctl w120">
				<div><input class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',width:80"></div>
				<div class="tag">启用后在新建或编辑商品时将自动生成条码</div>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w120">自动生成项:</div>
			<div class="ctl w120">
				<div><input class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',width:80"></div>
				<div class="tag">选择商品条码后，在新建商品、补充无条码商品、重置所有商品条码时将只对商品条码有效；选择单品条码后，在新建商品、补充无条码商品、重置所有商品条码时将只对单品条码有效；</div>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w120">条码类型:</div>
			<div class="ctl w120">
				<div><input class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',width:80"></div>
				<div class="tag">EAN-13码由8位厂商识别代码、4位产品代码及1位校验码组成。厂商识别码由企业向国家物品编码中心申请取得；产品代码可由企业自己定义（4位产品代码0000~9999 适用于1万种产品）；1位校验码可以由前面12位确定后计算出来。</div>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w120">下拉菜单:</div>
			<div class="ctl w120">
				<div>
					<input id="mCustomCombobox" class="easyui-numberbox" data-options="
						width:200,
						buttonIcon:'hjtr-money',
						validType:'zero',
						value:0,
						prefix:'￥',
						precision:2,
						onClickButton:function(e) {
							var mCustomMenu = $('#mCustomMenu');
							mCustomMenu.html('');
							mCustomMenu.menu('appendItem', {value:100, text: '采购价:￥100.00',iconCls:'hjtr-money'});
							mCustomMenu.menu('appendItem', {value:200, text: '批发价:￥200.00',iconCls:'hjtr-money'});
							mCustomMenu.menu('appendItem', {value:300, text: '零售价:￥300.00',iconCls:'hjtr-money'});
							mCustomMenu.menu('show', {
								left: $(this).parent().offset().left,
								top: $(this).parent().offset().top + $(this).parent().height() + 1
							});
						}
					"/>
				</div>
				<div class="tag">自定义组合下拉菜单。</div>
				<div id="mCustomMenu" class="easyui-menu" data-options="onClick:mCustomMenuHandler"></div>
				<script type="text/javascript">
					function mCustomMenuHandler(item) {
						 $("#mCustomCombobox").numberbox('setValue', item.value);
					}
				</script>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w120">条码格式:</div>
			<div class="ctl w120">
				<div>
					<input class="easyui-textbox" data-options="width:100,value:'12345678'">
					-
					<input class="easyui-textbox" data-options="width:165,disabled:true,value:'4位产品代码(系统生成)'">
					-
					<input class="easyui-textbox" data-options="width:150,disabled:true,value:'1位校验码(系统生成)'">
				</div>
				<div class="tag">EAN-13码由8位厂商识别代码、4位产品代码及1位校验码组成。厂商识别码由企业向国家物品编码中心申请取得；产品代码可由企业自己定义（4位产品代码0000~9999 适用于1万种产品）；1位校验码可以由前面12位确定后计算出来。</div>
			</div>
		</div>
		<div class="form-clo1">
			<div class="name w120">代码起始值:</div>
			<div class="ctl w120">
				<div>
					<input class="easyui-numberspinner" data-options="required:true,value:0" style="width:100px;"></input>
				</div>
				<div class="tag">EAN-13码由8位厂商识别代码、4位产品代码及1位校验码组成。厂商识别码由企业向国家物品编码中心申请取得；产品代码可由企业自己定义（4位产品代码0000~9999 适用于1万种产品）；1位校验码可以由前面12位确定后计算出来。</div>
			</div>
		</div>
    </form>
    <div id="win_base_demo1_form_footer" style="padding:5px;overflow:hidden;">
        <div style="float:left;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseDemo1.onClose" style="width:200px;margin-right:20px;">为无条码商品生成条码</a>
        </div>
        <div style="float:right;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-reload',onClick:$.h.window.winBaseDemo1.onClose" style="width:100px;">重置</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseDemo1.onSave" style="width:100px;">保存</a>
        </div>
    </div>
</div>
	