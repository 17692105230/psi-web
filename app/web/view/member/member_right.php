<div id="member_tabs" class="easyui-tabs"
	data-options="
		fit:true,
		border:false,
        tools:'#member_tabs_tools',
		justified:true,
		plain:true,
		onSelect: function(title,index) { /*alert(title);*/ }
	">
	<div title='基本信息' style="padding:10px;"
		data-options="
			footer:'#member_base_footer',
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
					width = '100%';
				}
				$('.kbi_column_left_50.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
		">
		<form id="member_base_from" method="post">
			<input type="hidden" id="member_base_id" name="id" />
            <div class="form-clo1">
					<div class="name w">会员帐号:</div>
					<div class="ctl w">
						<input id="member_code" name="member_code" class="easyui-textbox" value="" data-options="required:true" style="width:100%;"/>
					</div>
					<div class="name w">顾客姓名:</div>
					<div class="ctl w">
						<input id="member_name" name="member_name" class="easyui-textbox" style="width:100%;" data-options="required:true"/>
					</div>
            </div>
            <div class="form-clo1">
					<div class="name w">手机:</div>
					<div class="ctl w">
						<input id="member_iphone" name="member_iphone" class="easyui-numberbox" value="" data-options="required:true" style="width:100%;"/>
					</div>
					<div class="name w">性别:</div>
					<div class="ctl w">
                        <select id="member_gender" name="member_gender" class="easyui-combobox" style="width:100%;" data-options="editable:false"><option value="0">女</option><option value="1">男</option></select>
					</div>
            </div>
            <div class="form-clo1">
					<div class="name w">接待导购:</div>
					<div class="ctl w">
						<select id="receive_clerk_code" name="receive_clerk_code" class="easyui-combobox" style="width:100%;" data-options="url:'',editable:false"></select>
					</div>
					<div class="name w">会员类别:</div>
					<div class="ctl w">
						<input id="member_category_code" name="member_gender" class="easyui-combobox" style="width:100%;" data-options="url:'',valueField:'id',textField:'dict_name',editable:false,required:true"></input>
					</div>
            </div>
            <div class="form-clo1">
					<div class="name w">会员生日:</div>
					<div class="ctl w">
						<input id="member_birthday" name="member_birthday" class="easyui-datebox" style="width:100%;" data-options="editable:true,required:true"/>
					</div>
                <div class="name"></div>
                <div class="ctl"></div>
            </div>

				<div class="kbi_c"></div>
            <div class="form-panel" title="其他信息">
					<div class="form-clo1">
						<div class="name w">证件号:</div>
						<div class="ctl w">
							<input id="member_idcode" name="member_idcode" class="easyui-textbox" style="width:100%;"/>
						</div>
						<div class="name w">QQ号:</div>
						<div class="ctl w">
							<input id="member_qq" name="member_qq" class="easyui-textbox" style="width:100%;"/>
						</div>
					</div>
					<div class="form-clo1">
						<div class="name w">微信号:</div>
						<div class="ctl w">
							<input id="member_wechat" name="member_wechat" class="easyui-textbox" style="width:100%;"/>
						</div>
						<div class="name w">年龄:</div>
						<div class="ctl w">
							<input id="member_age" name="member_age" class="easyui-numberspinner" data-options="width:80,min:1,max:100,suffix:'岁',precision:0"/>
						</div>
                    </div>
					<div class="form-clo1">
						<div class="name w">身高:</div>
						<div class="ctl w">
							<input id="member_height" name="member_height" class="easyui-numberspinner" data-options="width:90,min:1,max:220,suffix:'CM',precision:0"/>
						</div>
						<div class="name w">居住城市:</div>
						<div class="ctl w">
							<input id="member_city_code" name="member_city_code" class="easyui-combobox" style="width:100%;" data-options="url:'/Dictionary/MemberCityLoad',valueField:'id',textField:'dict_name',editable:false"></input>
						</div>
					</div>
					<div class="form-clo1">
						<div class="name w">电子邮件:</div>
						<div class="ctl w">
							<input id="member_email" name="member_email" class="easyui-textbox" style="width:100%;"/>
						</div>
						<div class="name w">会员状态:</div>
						<div class="ctl w">
							<input id="member_status" name="member_status" class="easyui-switchbutton" data-options="onText:'启用',offText:'禁用',value:1,height:24" checked>
						</div>
					</div>
					<div class="form-clo1">
						<div class="name w">地址:</div>
						<div class="ctl w">
							<input id="member_address" name="member_address" class="easyui-textbox" style="width:100%;"/>
						</div>
                    </div>
                    <div class="form-clo1">
						<div class="name w">会员描述:</div>
						<div class="ctl ">
							<input id="member_details" name="member_details" class="easyui-textbox" style="width:100%;height:50px;" data-options="multiline:true"/>
						</div>
					</div>
            </div>
		</form>
	</div>
    <div title='会员账户' style="padding:10px;"
		data-options="
			footer:'#member_account_footer',
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
					width = '100%';
				}
				$('.kbi_column_left_50.a').each(function(i,n) {
					$(n).css('width', width);
				});
			}
		">
		<form id="member_account_from" method="post">

			<div class="form-clo1">
					<div class="name w">账户积分:</div>
					<div class="ctl w">
						<input id="account_points" name="account_points" class="easyui-numberbox" style="width:100%;" data-options="min:0,max:999999999,precision:0,disabled:true,value:0"/>
					</div>

					<div class="name w">账户金额:</div>
					<div class="ctl w">
						<input id="account_money" name="account_money" class="easyui-numberbox" style="width:100%;" data-options="min:0,max:999999999,prefix:'￥',precision:2,disabled:true,value:0"/>
					</div>
            </div>
            <div class="form-clo1">
					<div class="name w">冻结金额:</div>
					<div class="ctl w">
						<input id="freeze_money" name="freeze_money" class="easyui-numberbox" style="width:100%;" data-options="min:0,max:999999999,prefix:'￥',precision:2,disabled:true,value:0"/>
					</div>
                    <div class="name w"></div>
                    <div class="ctl w"></div>
            </div>
				<div class="kbi_c"></div>
				<div class="form-panel" title="消费汇总">
					    <div class="form-clo1">
						<div class="name w">购物总额:</div>
						<div class="ctl w">
							<input id="account_spend_total" name="account_spend_total" class="easyui-numberbox" value="0" style="width:100%;" data-options="disabled:true,prefix:'￥',precision:2"/>
						</div>
						<div class="name w">购物原价:</div>
						<div class="ctl w">
							<input id="account_spend_original_total" name="account_spend_original_total" class="easyui-numberbox" value="0" style="width:100%;" data-options="disabled:true,prefix:'￥',precision:2"/>
						</div>
                        </div>
                    <div class="form-clo1">
						<div class="name w">购物总数:</div>
						<div class="ctl w">
							<input id="account_count" name="account_count" class="easyui-numberbox" value="0" style="width:100%;" data-options="disabled:true"/>
						</div>
						<div class="name w">订单数量:</div>
						<div class="ctl w">
							<input id="account_orders_count" name="account_orders_count" class="easyui-numberbox" value="0" style="width:100%;" data-options="disabled:true"/>
						</div>
                    </div>
                    <div class="form-clo1">
						<div class="name w">尾单金额:</div>
						<div class="ctl w">
							<input id="account_last_money" name="account_last_money" class="easyui-numberbox" value="0" style="width:100%;" data-options="disabled:true,prefix:'￥',precision:2"/>
						</div>
						<div class="name w">尾单日期:</div>
						<div class="ctl w">
							<input id="last_time" name="last_time" class="easyui-textbox" value="1999-01-01" style="width:100%;" data-options="disabled:true"/>
						</div>
                    </div>
                    <div class="form-clo1">
						<div class="name w">首单金额:</div>
						<div class="ctl w">
							<input id="first_money" name="first_money" class="easyui-numberbox" value="0" style="width:100%;" data-options="disabled:true,prefix:'￥',precision:2"/>
						</div>
                        <div class="name"></div>
                        <div class="ctl"></div>
                    </div>
                </div>
		</form>
	</div>
	<div title='资金明细'>
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
				method: 'get'
			">
			<thead>
				<tr>
					<th data-options="field:'payed',width:20">金额</th>
					<th data-options="field:'cash_type_name',width:10,align:'center'">项目</th>
					<th data-options="field:'cash_time',width:35,align:'center'">时间</th>
					<th data-options="field:'shop_name',align:'center'">&nbsp;门店名称&nbsp;</th>
				</tr>
			</thead>
		</table>

	</div>
	<div title='回访记录'>
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
				method: 'get'
			">
			<thead>
				<tr>
					<th data-options="field:'review_type',width:10">类型</th>
					<th data-options="field:'review_content',width:35">摘要</th>
					<th data-options="field:'review_creatdate',width:20">时间</th>
					<th data-options="field:'review_per',align:'right'">服务人员</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div id="member_base_footer" style="height:40px;padding:5px;text-align:right;">
	<a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">测试</a>
	<a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$.h.member.submitBase();" style="width:180px;">保存会员基本信息</a>
</div>
<div id="member_account_footer" style="height:30px;padding:5px;text-align:right;">
	<a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="" style="width:100px;">测试</a>
	<a class="easyui-linkbutton" data-options="iconCls:'icon-ok'" href="javascript:void(0)" onclick="$.h.member.submitAccount();" style="width:180px;">保存会员账户信息</a>
</div>
<div id="member_tabs_tools" style="border-right:0px;">
	<a href="javascript:void(0)" class="easyui-linkbutton" data-options="plain:true,iconCls:'icon-add'" onclick="$.h.member.onNewAdd();">新建会员</a>
</div>