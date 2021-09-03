<!--<div id="main_layout" class="easyui-layout" data-options="fit:true">-->
<!--	<div data-options="region:'center',hideCollapsedContent:false,border:false">-->
<!--		<div class="easyui-layout" data-options="fit:true,border:false">-->
<!--			<div style="height:260px;padding:5px;"-->
<!--				data-options="-->
<!--					region:'north',-->
<!--					title:'关键业务指标',-->
<!--					split:true,-->
<!--					collapsible: false,-->
<!--					tools:[{-->
<!--							iconCls:'hr-settings',-->
<!--							handler:function() {-->
<!--								parent.$.messager.confirm('确认','您确认想要删除记录吗？',function(r){-->
<!--									if (r){-->
<!--										alert('确认删除');-->
<!--									}-->
<!--								});-->
<!--							}-->
<!--						}]">-->
<!--			</div>-->
<!--			<div style="width:50%;padding:5px;"-->
<!--				data-options="-->
<!--					region:'west',-->
<!--					title:'常用表单',-->
<!--					split:true,-->
<!--					collapsible: false,-->
<!--					tools:[{-->
<!--							iconCls:'hr-settings',-->
<!--							handler:function() {-->
<!--								parent.$.messager.alert('警告','警告消息');-->
<!--							}-->
<!--						}]">-->
<!--			</div>-->
<!--			<div style="padding:5px;"-->
<!--				data-options="-->
<!--					region:'center',-->
<!--					title:'流程与提醒',-->
<!--					tools:[{-->
<!--						iconCls:'hr-settings',-->
<!--							handler:function() {-->
<!--								// 消息将显示在顶部中间-->
<!--								parent.$.messager.prompt('提示信息', '请输入你的姓名：', function(r){-->
<!--									if (r){-->
<!--										alert('你的姓名是：' + r);-->
<!--									}-->
<!--								});-->
<!--							}-->
<!--						}]"></div>-->
<!--			<div style="height:320px;padding:5px;"-->
<!--				data-options="-->
<!--					region:'south',-->
<!--					title:'南',-->
<!--					split:true,-->
<!--					collapsible: false,-->
<!--					tools:[{-->
<!--							iconCls:'hr-settings',-->
<!--							handler:function() {-->
<!--								$.messager.show({-->
<!--									title:'我的消息',-->
<!--									msg:'消息将在5秒后关闭。',-->
<!--									timeout:5000,-->
<!--									showType:'slide'-->
<!--								});-->
<!--							}-->
<!--						}]">-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--	-->
<!--	<div data-options="region:'east',split:true,title:'客户',width:560,minWidth:400,hideCollapsedContent:false" style="overflow:hidden;-moz-user-select:none;-webkit-user-select:none;">-->
<!--		<div class="easyui-panel"-->
<!--			data-options="-->
<!--				fit:true,-->
<!--				title:'&nbsp;',-->
<!--				border:false-->
<!--			">-->
<!--			<header style="height:33px;padding:1px;">-->
<!--				<div style="float:left;">-->
<!--					<a id="btn_search" class="easyui-linkbutton"-->
<!--						data-options="-->
<!--							iconCls:'icon-search',-->
<!--							plain:true,-->
<!--							onClick:function(e) {-->
<!--								var p = $('#list_layout');-->
<!--								if (p.layout('panel','north').panel('options').closed) {-->
<!--									p.layout('show','north');-->
<!--								} else {-->
<!--									p.layout('hide','north');-->
<!--								}-->
<!--							}-->
<!--						">查询</a>-->
<!--				</div>-->
<!--				<div style="float:right;">-->
<!--					<a class="easyui-linkbutton" data-options="iconCls:'icon-reload',toggle:true,group:'g1',selected:true,plain:true,status:-1" style="color:blue;font-weight:bold">全部</a>-->
<!--					<a class="easyui-linkbutton" data-options="toggle:true,group:'g1',plain:true,status:0">挂起</a>-->
<!--					<a class="easyui-linkbutton" data-options="toggle:true,group:'g1',plain:true,status:1">撤销</a>-->
<!--					<a class="easyui-linkbutton" data-options="toggle:true,group:'g1',plain:true,status:9">完成</a>-->
<!--				</div>-->
<!--			</header>-->
<!--			<div id="list_layout" class="easyui-layout" data-options="fit:true">-->
<!--				<div style="height:172px;background-color:#eee;padding:10px 10px 0px 10px;border-bottom: 1px solid #ccc;"-->
<!--					data-options="-->
<!--						region:'north',-->
<!--						border:false,-->
<!--						closed:true-->
<!--					">-->
<!--					<form id="list_search_form" method="post">-->
<!--						<div class="form-clo1">-->
<!--							<div class="name w">姓名:</div>-->
<!--							<div class="ctl w">-->
<!--								<input name="sreach_supplier_name" class="easyui-textbox" style="width:100%" /input>-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="form-clo1">-->
<!--							<div class="name w"><strong>日期</strong> 从:</div>-->
<!--							<div class="ctl w" style="display:flex;justify-content:space-between;align-items:center;">-->
<!--								<div style="width:calc(50% - 15px);">-->
<!--								<input name="search_date_begin" class="easyui-datebox" style="width:100%;" /input>-->
<!--								</div>-->
<!--								<div style="width:30px; text-align:center;">至</div>-->
<!--								<div style="width:calc(50% - 15px);">-->
<!--								<input name="search_date_end" class="easyui-datebox" style="width:100%;" /input>-->
<!--								</div>-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="form-clo1">-->
<!--							<div class="name w">单号:</div>-->
<!--							<div class="ctl w">-->
<!--								<input name="search_orders_code" class="easyui-textbox" style="width:100%;" /input>-->
<!--							</div>-->
<!--						</div>-->
<!--						<div class="form-clo1">-->
<!--							<div class="name w"></div>-->
<!--							<div class="ctl w" style="display:flex;justify-content:space-between;align-items:center;">-->
<!--								<a class="easyui-linkbutton" style="width:100px;margin-right:15px;"-->
<!--									data-options="-->
<!--										iconCls:'icon-clear',-->
<!--										onClick:function(e) {-->
<!--											$('#list_layout').layout('hide','north');-->
<!--										}-->
<!--									">关闭</a>-->
<!--								<a class="easyui-linkbutton" style="width:100px;margin-right:15px;"-->
<!--									data-options="-->
<!--										iconCls:'icon-reload',-->
<!--										onClick:function(e) {-->
<!--											$('#list_search_form').form('reset');-->
<!--										}-->
<!--									">重置</a>-->
<!--								<a class="easyui-linkbutton" data-options="iconCls:'icon-search'" style="width:100px;">查询</a>-->
<!--							</div>-->
<!--						</div>-->
<!--					</form>-->
<!--				</div>-->
<!--				<div data-options="region:'center',border:false">-->
<!--					<table id="orders_list" class="easyui-datagrid"-->
<!--						data-options="-->
<!--							fit: true,-->
<!--							fitColumns:true,-->
<!--							border: false,-->
<!--							rownumbers:true,-->
<!--							singleSelect:true,-->
<!--							method:'post',-->
<!--							pagination: true,-->
<!--							pageSize: 25,-->
<!--							showPageList: false,-->
<!--							displayMsg: '共 {total} 行',-->
<!--							footer:'#orders_list_details_footer',-->
<!--							data: {'total':28,'rows':[-->
<!--								{'orders_id':1,'orders_date':'2020-05-01 11:20','supplier_name':'王小明','orders_status':0},-->
<!--								{'orders_id':1,'orders_date':'2020-05-01 11:20','supplier_name':'二嘎子','orders_status':1},-->
<!--								{'orders_id':1,'orders_date':'2020-05-01 11:20','supplier_name':'普拉多','orders_status':9},-->
<!--								{'orders_id':1,'orders_date':'2020-05-01 11:20','supplier_name':'曹孟德','orders_status':1}-->
<!--							]},-->
<!--							onClickCell : function(rowIndex, field, value, row) {-->
<!--								switch(field) {-->
<!--									case 'orders_id1':-->
<!--										parent.$.h.window.winBaseDemo.onOpen();-->
<!--										break;-->
<!--									case 'orders_id2': {-->
<!--										parent.$.h.index.setOperateInfo({-->
<!--											module:'列表功能',-->
<!--											operate:'点击操作',-->
<!--											content:'测试写入日志内是的发送到发送到发送到发斯蒂芬阿斯蒂芬阿斯蒂芬阿斯蒂芬阿斯蒂芬容~~',-->
<!--											icon:'hr-warn'-->
<!--										}, true);-->
<!--										break;-->
<!--									}-->
<!--									case 'orders_id3':-->
<!--										parent.$.h.window.winBaseDemo1.onOpen();-->
<!--										break;-->
<!--									case 'orders_id4':-->
<!--										parent.$.h.window.winBaseDemo2.onOpen();-->
<!--										break;-->
<!--								}-->
<!--							}-->
<!--							">-->
<!--						<thead>-->
<!--							<tr>-->
<!--								<th data-options="field:'orders_id',checkbox:true"></th>-->
<!--								<th data-options="-->
<!--										field:'orders_id1',-->
<!--										align:'center',-->
<!--										fixed:true,-->
<!--										width:30,-->
<!--										formatter: function(value, row, index) {-->
<!--											return '<a href=\'javascript:void(0);\' class=\'datagrid-row-modify\' style=\'display:inline-block;width:16px;height:16px;\'></a>';-->
<!--										}-->
<!--									"></th>-->
<!--								<th data-options="-->
<!--										field:'orders_date',-->
<!--										align:'left',-->
<!--										width:160-->
<!--									"><strong>日期</strong></th>-->
<!--								<th data-options="field:'supplier_name',align:'left',width:100"><strong>姓名</strong></th>-->
<!--								<th data-options="-->
<!--										field:'orders_status',-->
<!--										align:'center',-->
<!--										fixed:true,-->
<!--										width:60,-->
<!--										formatter: function(value,row,index) {-->
<!--											switch(parseInt(value)) {-->
<!--												case 0:-->
<!--													return '<font color=blue>挂起</font>';-->
<!--												case 9:-->
<!--													return '<font color=green><b>完成</b></font>';-->
<!--												case 1:-->
<!--													return '<font color=#aaa><b>撤销</b></font>';-->
<!--											}-->
<!--										}-->
<!--									"><strong>状态</strong></th>-->
<!--								<th data-options="-->
<!--										field:'orders_id2',-->
<!--										align:'center',-->
<!--										fixed:true,-->
<!--										width:30,-->
<!--										formatter: function(value, row, index) {-->
<!--											return '<a href=\'javascript:void(0);\' class=\'datagrid-row-del\' style=\'display:inline-block;width:16px;height:16px;\'></a>';-->
<!--										}-->
<!--									"></th>-->
<!--								<th data-options="-->
<!--										field:'orders_id3',-->
<!--										align:'center',-->
<!--										fixed:true,-->
<!--										width:30,-->
<!--										formatter: function(value, row, index) {-->
<!--											return '<a href=\'javascript:void(0);\' class=\'datagrid-row-edit\' style=\'display:inline-block;width:16px;height:16px;\'></a>';-->
<!--										}-->
<!--									"></th>-->
<!--								<th data-options="-->
<!--										field:'orders_id4',-->
<!--										align:'center',-->
<!--										fixed:true,-->
<!--										width:30,-->
<!--										formatter: function(value, row, index) {-->
<!--											return '<a href=\'javascript:void(0);\' class=\'datagrid-row-copy\' style=\'display:inline-block;width:16px;height:16px;\'></a>';-->
<!--										}-->
<!--									"></th>-->
<!--								<th data-options="-->
<!--										field:'orders_id5',-->
<!--										align:'center',-->
<!--										fixed:true,-->
<!--										width:30,-->
<!--										formatter: function(value, row, index) {-->
<!--											return '<a href=\'javascript:void(0);\' class=\'datagrid-row-add\' style=\'display:inline-block;width:16px;height:16px;\'></a>';-->
<!--										}-->
<!--									"></th>-->
<!--							</tr>-->
<!--						</thead>-->
<!--					</table>-->
<!--					<div id="orders_list_details_footer" style="line-height:50px;height:50px;padding:2px 2px;text-align:center;">该区域可以自定义HTML代码</div>-->
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--</div>-->
<!---->
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center',border:false," style="overflow:hidden;">
        <img src="/assets/web/img/index.png" alt="" style="height: 100%;width: 100%">
    </div>
</div>