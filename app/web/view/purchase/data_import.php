
<div class="easyui-layout" data-options="fit:true">
    <div data-options="region:'center'" style="width:524px;">
    <div class="easyui-panel" data-options="fit:true,border:false">
        <table id="file-datagrid" class="easyui-datagrid" style="height:100%;"
               data-options="singleSelect:true,fit:true,fitColumns:true,toolbar:'#upbar'">
            <thead>
            <tr>
                <th data-options="field:'fileid',width:2,hidden:true">ID</th>
                <th data-options="field:'filename',width:7">文件名</th>
                <th data-options="field:'status',width:3,formatter:$.h.fileup.formatter">状态</th>
            </tr>
            </thead>
        </table>
    </div>
    </div>
    <div data-options="region:'east'" style="width: 480px;">
        <div class="easyui-layout" data-options="fit:true">
            <div class="easyui-panel" style="" data-options="fit:true,border:false">
                <table id="file_datagrid" class="easyui-datagrid" title="" style="height:100%;"
                       data-options="
                       singleSelect:true,
                       fit:true,
                       rownumbers:true,
                       url:'/web/purchase/seeOrdersFile?ordersCode='+$('#orders_code_view').html(),
                       onLoadSuccess:function(){$('.createSelectUpBtn').linkbutton({plain: true});}
               ">
                    <thead>
                    <tr>
                        <th data-options="field:'assist_id',hidden:true"></th>
                        <th data-options="field:'assist_name',width:120">文件名</th>
                        <th data-options="field:'create_time',width:200,">上传时间</th>
                        <th data-options="field:'cz',align:'center',
                                  width:50,
                                  fixed:true,
                                  formatter:$.h.po.assistExtensionIf
                ">详情</th>
                        <th data-options="field:'cz2',align:'center',width:50,fixed:true,formatter:$.h.po.delFileOrders">删除</th>
                    </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>
</div>
<div class="easyui-window" id="yulan_box" data-options="
                    closed:true,
                    width:820,height:450,title:'图片预览',
                    modal:true,
                    maximizable:false,minimizable:false,collapsible:false,
                    ">
    <div style="text-align: center;">
        <img src="" alt="" id="yulan_img" style="width: 100%;">
    </div>
</div>
<div id="upbar">
    <a href="#" class="l-btn l-btn-small l-btn-plain" data-options="plain:true,iconCls:'selectd'" group="">
        <span class="l-btn-left l-btn-icon-left" id="import_picker">
            <span class="l-btn-text">选择文件</span>
            <span
                    class="l-btn-icon selectd">
                &nbsp;
            </span>
        </span>
    </a>

    <a href="#" id="load_fj" class="easyui-linkbutton" data-options="plain:true,iconCls:'uploading'">上传</a>
</div>
<script>

    (function($) {
        $.h.fileup = {
            filelist:[],
            //初始化,zonglonglong
            oninit:function(){

                let str = [
                    {
                        fileid:1,
                        filename:"test",
                        status:"测试"
                    },
                    {
                        fileid:2,
                        filename:"test2",
                        status:"10"
                    }
                ];
                $("#file-datagrid").datagrid("loadData",$.h.fileup.filelist);
            },
            download_model:function(){
                window.open('/template/car_ins_template.xlsx');
            },
            //更新文件列表状态,zonglonglong
            upFileListStatus:function(fileid,status){
                let list = $.h.fileup.filelist;
                for (let i = 0; i < list.length; i++) {
                    if(list[i].fileid==fileid){
                        list[i].status = status;
                        break;
                    }
                }
                $.h.fileup.filelist = list;
                $("#file-datagrid").datagrid("loadData",$.h.fileup.filelist);
                // $("#file-datagrid").datagrid("load");
            },
            //格式化数据,zonglonglong
            formatter:function(value,row,index){
                let zz = /^[\u4e00-\u9fa5]*$/;
                if(!zz.test(value)){//
                    let percentage = Math.round(value);
                    //如果是数字说明是进度条
                    let str = '<div id="p" class="easyui-progressbar progressbar" style="width: 100%; height: 22px;" data-options="value:20">'+
                        '<div class="progressbar-text" style="width: 100%; height: 20px; line-height: 20px;">'+
                        '</div>'+
                        '<div class="progressbar-value" style="width: '+
                        percentage+
                        '%; height: 20px; line-height: 20px;">'+
                        '<div class="progressbar-text" style="width: 100%; height: 20px; line-height: 20px;">'+
                        percentage+
                        '%'+
                        '</div>'+
                        '</div>'+
                        '</div>';
                    return str;
                }else{
                    //如果不是数字,就直接显示
                    return value;
                }
            }
        }
    })(jQuery);
    var $ = jQuery,
        //   $list = $('#thelist'),
        $btn_upload = $('#load_fj'),
        state = 'pending',
        uploader;
    uploader = WebUploader.create({
        // swf文件路径
        // swf: BASE_URL + '/js/Uploader.swf',
        // 文件接收服务端。
        server: '/web/purchase/uploadfile',
        //设定name值
        fileVal:'file',
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#import_picker',

        // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
        resize: false
    });
    uploader.on( 'uploadBeforeSend', function( block, data ) {
        data.orders_code =  $("#orders_code_view").html();
    });
    // 当有文件被添加进队列的时候
    uploader.on( 'fileQueued', function( file ) {
        $filerow = {fileid:file.id,filename:file.name,status:"待上传"};
        $.h.fileup.filelist.push($filerow);
        $.h.fileup.oninit();
    });
    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        $.h.fileup.upFileListStatus(file.id,percentage*100);
    });

    uploader.on( 'all', function( type ) {
        if ( type === 'startUpload' ) {
            state = 'uploading';
        } else if ( type === 'stopUpload' ) {
            state = 'paused';
        } else if ( type === 'uploadFinished' ) {
            state = 'done';
        }

        if ( state === 'uploading' ) {
            // $btn_upload.text('暂停上传');
            $btn_upload.linkbutton({text:"暂停上传"});
        } else {
            // $btn_upload.text('开始上传');
            $btn_upload.linkbutton({text:"开始上传"});

        }
    });

    $btn_upload.on( 'click', function() {
        if ( state === 'uploading' ) {
            uploader.stop();
        } else {
            uploader.upload();
        }
    });

    uploader.on( 'uploadSuccess', function( file, res ) {
        if (res.errcode != 0){
            $.h.fileup.upFileListStatus(file.id,res.errmsg);
        } else {
            $.h.po.fileDatagrid();
            $.h.fileup.upFileListStatus(file.id,100);
        }

    });

    uploader.on( 'uploadError', function( file ) {
        $.h.fileup.upFileListStatus(file.id,"上传出错,文件名重复");
    });

    uploader.on( 'uploadComplete', function( file ) {
        $( '#'+file.id ).find('.progress').fadeOut();
    });
</script>
