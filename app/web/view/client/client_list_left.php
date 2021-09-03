<div id="client_tabs" class="easyui-tabs"
     data-options="
		fit:true,
		border:false,
        tools:'#client_tabs_tools',
		justified:true,
		plain:true,
		onSelect: function(title,index) { /*alert(title);*/ }
	">
    <div title='基本信息' style="padding:10px;"
         data-options="
			footer:'#client_base_footer',
			onResize: function(w, h) {
				w = $(this).panel('panel').width();
				var width = '50%';
				if (w <= 2000 && w > 1500) {
					width = '20%';
				} else if (w <= 1500 && w > 1100) {
					width = '25%';
				} else if (w <= 1100 && w > 800) {
					width = '33.33%';
				} else if (w <= 800 && w > 440) {
					width = '50%';
				} else {
					width = w - 35;
				}
				$('.kbi_column_left_50.a').each(function(i,n) {
					$(n).css('width', width);
				});
				$('.kbi_column_left_100.a').each(function(i,n) {
					$(n).css('width', w - 35);
				});
			}
		">
        <form id="client_from" method="post">
                <div class="form-clo1">
                    <div class="name">客户姓名:</div>
                    <div class="ctl">
                        <input id="client_name" name="client_name" class="easyui-textbox" style="width:100%;" data-options="prompt:'客户姓名',required:true"/>
                    </div>
                </div>
                <div class="form-clo1">
                    <div class="name">客户类别:</div>
                    <div class="ctl">
                        <input id="client_category_id" name="client_category_id" class="easyui-combobox" style="width:100%;"
                               data-options="
                                prompt:'客户类别',
                                method:'get',
                                queryParams:{dict_type:'client'},
								url:'/web/customer/loadList',
								valueField:'dict_id',
								textField:'dict_name',
								editable:false,
								panelHeight:100,
								required:true
							"/>
                    </div>
                </div>
                <div class="form-clo1">
                    <div class="name">默认折扣:</div>
                    <div class="ctl">
                        <input id="client_discount" name="client_discount" class="easyui-numberspinner" data-options="prompt:'默认折扣',min:0,max:100,precision:0,value:100,required:true" style="width:100px;"/>
                    </div>
                </div>
                <div class="form-clo1">
                    <div class="name">客户状态:</div>
                    <div class="ctl">
                        <input id="client_status" name="client_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,width:70,height:30" checked>
                    </div>
                </div>
                <div class="kbi_c"></div>
                <div class="form-panel">
                    <div class="form-clo1">
                        <div class="name">账户金额:</div>
                        <div class="ctl">
                            <input id="account_money" name="account_money" class="easyui-numberbox" style="width:100%;" value="0" data-options="disabled:true,min:0,max:999999999,prefix:'￥',precision:2"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">冻结金额:</div>
                        <div class="ctl">
                            <input id="account_fmoney" name="account_fmoney" class="easyui-numberbox" style="width:100%;" value="0" data-options="disabled:true,min:0,max:999999999,prefix:'￥',precision:2"/>
                        </div>
                    </div>
                </div>
                <div class="kbi_c"></div>
            <div class="form-panel">
                    <div class="form-clo1">
                        <div class="name">采购总额:</div>
                        <div class="ctl">
                            <input id="account_ptmoney" name="account_ptmoney" class="easyui-numberspinner" value="0" style="width:100%;" data-options="disabled:true,prefix:'￥',precision:2"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">采购原价:</div>
                        <div class="ctl">
                            <input id="account_potmoney" name="account_potmoney" class="easyui-numberspinner" value="0" style="width:100%;" data-options="disabled:true,prefix:'￥',precision:2"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">采购总数:</div>
                        <div class="ctl">
                            <input id="account_number" name="account_number" class="easyui-numberspinner" value="0" style="width:100%;" data-options="disabled:true"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">尾单金额:</div>
                        <div class="ctl">
                            <input id="account_last_money" name="account_last_money" class="easyui-numberspinner" value="0" style="width:100%;" data-options="disabled:true,prefix:'￥',precision:2"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">尾单日期:</div>
                        <div class="ctl">
                            <input id="account_last_time" name="account_last_time" class="easyui-datebox" value="1999-01-01" style="width:100%;" data-options="disabled:true"/>
                        </div>
                    </div>
                </div>
                <div class="form-panel">
                    <div class="form-clo1">
                        <div class="name">客户电话:</div>
                        <div class="ctl">
                            <input id="client_phone" name="client_phone" class="easyui-numberbox" data-options="prompt:'客户电话'" style="width:100%;"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">邮箱:</div>
                        <div class="ctl">
                            <input id="client_email" name="client_email" class="easyui-textbox" data-options="prompt:'电子信箱'" style="width:100%;"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">地址:</div>
                        <div class="ctl">
                            <input id="client_address" name="client_address" data-options="prompt:'客户地址'" class="easyui-textbox" style="width:100%;"/>
                        </div>
                    </div>
                    <div class="form-clo1">
                        <div class="name">客户描述:</div>
                        <div class="ctl">
                            <input id="client_story" name="client_story" class="easyui-textbox" style="width:100%;height:50px;padding:4px;" data-options="multiline:true"/>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <div title='资金科目'>
        <table class="easyui-datagrid" style="width:100%;height:100%;"
               data-options="
				fit:true,
				border:false,
				fitColumns:true,
				rownumbers:true,
				iconCls: 'icon-edit',
				singleSelect: true,
				collapsible:false,
				autoRowHeight:false,
				method: 'get',
				data:[
					{client_capital_subjects:'押金',client_capital_money:20000,client_capital_time:'2016.12.12',client_capital_annotate:'品牌代理客户支付的押金，到期需退还。'},
					{client_capital_subjects:'订金',client_capital_money:150000,client_capital_time:'2016.12.30',client_capital_annotate:'2016年冬装预付订金总额。'}
				]
			">
            <thead>
            <tr>
                <th data-options="field:'client_capital_subjects',fixed:true,width:80,align:'center'">科目</th>
                <th data-options="
							field:'client_capital_money',
							fixed:true,
							width:100,
							formatter: function(value,row,index) {
								return $.formatMoney(value,'￥');
							}
						">金额</th>
                <th data-options="field:'client_capital_time',fixed:true,width:100,align:'center'">时间</th>
                <th data-options="field:'client_capital_annotate',width:30,align:'left'">注解</th>
            </tr>
            </thead>
        </table>
    </div>
    <div title='资金明细'></div>
</div>
<div id="client_base_footer" style="height:60px;padding:5px;text-align:right;">
    <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.client.onSave" style="width:100%;height:50px;size:14px;">保存客户信息</a>
</div>
<div id="client_tabs_tools" style="border-right:0px;">
    <a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add',onClick:$.h.client.onAddNew">新建客户</a>
</div>