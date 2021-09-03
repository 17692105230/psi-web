<div class="easyui-panel" data-options="footer:'#win_base_client_form_footer'" style="width:100%;height:100%;padding:15px 10px;">
    <form id="win_base_barcode_form" method="post">
            <div class="form-clo1">
                <div class="name w120" style="font-weight:bold;">自动生成条码:</div>
                <div class="ctl w120">
                    <div>
                        <input id="win_base_barcode_form_auto_generate" class="easyui-switchbutton" name="auto_generate" data-options="onText:'启用',offText:'禁用',width:80">
                    </div>
                    <div class="tag">启用后在新建或编辑商品时将自动生成条码</div>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w120" style="font-weight:bold;">自动生成项:</div>
                <div class="ctl w120">
                    <div>
                        <input id="win_base_barcode_form_item_type" class="easyui-switchbutton" name="item_type" data-options="onText:'商品条码',offText:'单品条码',width:120">
                    </div>
                    <div class="tag">选择商品条码后，在新建商品、补充无条码商品、重置所有商品条码时将只对商品条码有效；选择单品条码后，在新建商品、补充无条码商品、重置所有商品条码时将只对单品条码有效；</div>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w120" style="font-weight:bold;">条码类型:</div>
                <div class="ctl w120">
                    <div>
                        <select class="easyui-combobox" name="type" data-options="panelHeight:'auto',editable:false" style="width:120px;">
                            <option value="EAN-13">EAN-13</option>
                        </select>
                    </div>
                    <div class="tag">EAN-13码由8位厂商识别代码、4位产品代码及1位校验码组成。厂商识别码由企业向国家物品编码中心申请取得；产品代码可由企业自己定义（4位产品代码0000~9999 适用于1万种产品）；1位校验码可以由前面12位确定后计算出来。</div>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w120" style="font-weight:bold;">条码格式:</div>
                <div class="ctl w120">
                    <div>
                        <input class="easyui-textbox" name="firm_code" data-options="width:100,value:'12345678'">
                        -
                        <input class="easyui-textbox" data-options="width:165,disabled:true,value:'4位产品代码(系统生成)'">
                        -
                        <input class="easyui-textbox" data-options="width:150,disabled:true,value:'1位校验码(系统生成)'">
                    </div>
                    <div class="tag">EAN-13码由8位厂商识别代码、4位产品代码及1位校验码组成。厂商识别码由企业向国家物品编码中心申请取得；产品代码可由企业自己定义（4位产品代码0000~9999 适用于1万种产品）；1位校验码可以由前面12位确定后计算出来。</div>
                </div>
            </div>
            <div class="form-clo1">
                <div class="name w120" style="font-weight:bold;">产品代码起始值:</div>
                <div class="ctl w120">
                    <div>
                        <input class="easyui-numberspinner" name="start_value" data-options="required:true,value:0" style="width:100px;"></input>
                    </div>
                    <div class="tag">EAN-13码由8位厂商识别代码、4位产品代码及1位校验码组成。厂商识别码由企业向国家物品编码中心申请取得；产品代码可由企业自己定义（4位产品代码0000~9999 适用于1万种产品）；1位校验码可以由前面12位确定后计算出来。</div>
                </div>
            </div>
    </form>
    <div id="win_base_client_form_footer" style="padding:5px;overflow:hidden;">
        <div style="float:left;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseBarcode.onGenerate" style="width:200px;margin-right:20px;">为无条码商品生成条码</a>
        </div>
        <div style="float:right;">
            <a class="easyui-linkbutton" data-options="iconCls:'icon-reload',onClick:$.h.window.winBaseBarcode.onReset" style="width:100px;">重置</a>
            <a class="easyui-linkbutton" data-options="iconCls:'icon-save',onClick:$.h.window.winBaseBarcode.onSave" style="width:100px;">保存</a>
        </div>
    </div>
</div>
