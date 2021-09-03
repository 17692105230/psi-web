(function( $ ) {
    $.fn.extend({
        diyUpload:function(opt, serverCallBack) {
            if (typeof opt != "object") {
                alert('参数错误!');
                return;
            }
            opt.formData.goods_id = 1;

            var $fileInput = $(this);
            var $fileInputId = $fileInput.attr('id');
            /* 上传地址 */
            if( opt.url ) {
                opt.server = opt.url;
                delete opt.url;
            }
			/* 成功回调 */
            if( opt.success ) {
                var successCallBack = opt.success;
                delete opt.success;
            }
			/* 错误回调 */
            if( opt.error ) {
                var errorCallBack = opt.error;
                delete opt.error;
            }
			/* 迭代出默认配置 */
            $.each(getOption('#'+$fileInputId), function(key, value){
                opt[ key ] = opt[ key ] || value;
            });
			/* 按钮文字 */
            if (opt.buttonText) {
                opt['pick']['label'] = opt.buttonText;
                delete opt.buttonText;
            }
			/* 实例化对象 */
            var webUploader = getUploader(opt);
            if ( !WebUploader.Uploader.support() ) {
                alert( ' 上传组件不支持您的浏览器！');
                return false;
            }
            /* 绑定文件加入队列事件 */
            webUploader.on('fileQueued', function( file ) {
                createBox($fileInput, file ,webUploader);
            });
            /* 进度条事件 */
            webUploader.on('uploadProgress',function( file, percentage  ){
                var $fileBox = $('#fileBox_'+file.id);
                var $diyBar = $fileBox.find('.diyBar');
                $diyBar.show();
                percentage = percentage * 100;
                showDiyProgress( percentage.toFixed(2), $diyBar);
            });
            /* 全部上传结束后触发 */
            webUploader.on('uploadFinished', function(){
				$('#btn_upload').linkbutton({
                    text : '开始上传',
                    disabled : true
                });
            });
            /* 绑定发送至服务端返回后触发事件 */
            webUploader.on('uploadAccept', function( object ,data ){
                if (serverCallBack) {serverCallBack(data);}
            });
			/* 某个文件开始上传前触发，一个文件只会触发一次。 */
			webUploader.on('uploadStart',function(file) {
                if ($('#fileBox_' + file.id).children('.viewThumb').children('.mainImg').is(':visible')) {
                    webUploader.option().formData.main_img = file.name;
                    webUploader.option().formData.goods_id = $('#goods_id').val();
                } else {
                    delete webUploader.option().formData.main_img;
                }
				//console.log(webUploader.option().formData);
			});
            /* 上传成功后触发事件 */
            webUploader.on('uploadSuccess',function(file, response) {

                var $fileBox = $('#fileBox_'+file.id);
				$fileBox.attr('assist_id', response.data.assist_id);
                var $diyBar = $fileBox.find('.diyBar');
                //$fileBox.removeClass('diyUploadHover');
                $diyBar.fadeOut(500 ,function(){
                    $fileBox.children('.viewThumb').children('.diySuccess').show();
                });
                if ( successCallBack ) {
                    successCallBack( response );
                }
            });
            /* 上传失败后触发事件 */
            webUploader.on('uploadError',function( file, reason ){
                var $fileBox = $('#fileBox_'+file.id);
                var $diyBar = $fileBox.find('.diyBar');
                showDiyProgress( 0, $diyBar , '上传失败!' );
                var err = '上传失败！文件：' + file.name + ' 错误码：' + reason;
                if ( errorCallBack ) { errorCallBack( err ); }
            });
            /* 选择文件错误触发事件 */
            webUploader.on('error', function( code ) {
                var text = '';
                switch( code ) {
                    case  'F_DUPLICATE' : text = '该文件已经被选择了!' ;
                        break;
                    case  'Q_EXCEED_NUM_LIMIT' : text = '上传文件数量超过限制!' ;
                        break;
                    case  'F_EXCEED_SIZE' : text = '文件大小超过限制!';
                        break;
                    case  'Q_EXCEED_SIZE_LIMIT' : text = '所有文件总大小超过限制!';
                        break;
                    case 'Q_TYPE_DENIED' : text = '文件类型不正确或者是空文件!';
                        break;
                    default : text = '未知错误!';
                        break;
                }
                alert(text);
            });

			
            return webUploader;
        }
    });

    /* Web Uploader默认配置 */
    function getOption(objId) {
        return {
            /* 按钮容器 */
            pick:{
                id:objId,
                label:""
            },
            /* 类型限制 */
            accept:{
                title:"Images",
                extensions:"gif,jpg,jpeg,bmp,png",
                mimeTypes:"image/jpg,image/jpeg,image/png"
            },
            /* 配置生成缩略图的选项 */
            thumb:{
                width:160,
                height:120,
                /* 图片质量，只有type为`image/jpeg`的时候才有效。 */
                quality:90,
                /* 是否允许放大，如果想要生成小图的时候不失真，此选项应该设置为false. */
                allowMagnify:false,
                /* 是否允许裁剪。 */
                crop:true,
                /* 为空的话则保留原有图片格式。 */
                /* 否则强制转换成指定的类型。 */
                type:"image/jpg,image/jpeg,image/png"
            },
            /* 文件上传方式 */
            method:"POST",
            /* 服务器地址 */
            server:"",
            /* 是否已二进制的流的方式发送文件，这样整个上传内容php://input都为文件内容 */
            sendAsBinary:false,
            /* 开起分片上传。 thinkphp的上传类测试分片无效,图片丢失 */
            chunked:true,
            /* 分片大小 */
            chunkSize:512 * 1024,
            /* 最大上传的文件数量, 总文件大小,单个文件大小(单位字节) */
            fileNumLimit:5,
            fileSizeLimit:500000 * 1024,
            fileSingleSizeLimit:50000 * 1024,
        };
    }

    /* 实例化Web Uploader */
    function getUploader(opt) {
        return new WebUploader.Uploader(opt);
    }

    /* 操作进度条 */
    function showDiyProgress( progress, $diyBar, text ) {
        if ( progress >= 100 ) {
			progress = progress + '%';
			text = text || '上传完成';
		} else {
			progress = progress + '%';
			text = text || progress;
		}
		
		var $diyProgress = $diyBar.find('.diyProgress');
		var $diyProgressText = $diyBar.find('.diyProgressText');
		$diyProgress.width( progress );
		$diyProgressText.text( text );

    }

    /**
	 * 取消事件 
    function removeLi ($li ,file_id ,webUploader) {
		var assist_id = $li.attr('assist_id');
		if (assist_id) {
			webUploader.option().callbackFun(
				'/manage/upload/delImage',
				assist_id,
				function(data) {
					if (data.errcode == 0) {
						webUploader.removeFile(file_id);
						$li.remove();
					}
					$.h.c.warnMessager(data.errmsg);
				}
			);
		} else {
			webUploader.removeFile(file_id);
			$li.remove();
		}
    }
	 */

    /**
	 *左移事件;
    function leftLi ($leftli, $li) {
        $li.insertBefore($leftli);
    }
	 */

    /**
	 * 右移事件;
    function rightLi ($rightli, $li) {
        $li.insertAfter($rightli);
    }
	 */

    /* 创建文件操作div */
    function createBox($fileInput, file, webUploader) {
        var file_id = file.id;
        var $parentFileBox = $fileInput.parents(".upload-ul");
        var fileLen = $parentFileBox.children(".diyUploadHover").length;
		

        /* 创建按钮 */
		if (file.getStatus() == 'inited') {
			$('#btn_upload').linkbutton({
                disabled : false,
                onClick : function() {
                    uploadStart();
                }
            });
			
			/* 开始上传,暂停上传,重新上传事件 */
			var uploadStart = function() {
                webUploader.upload();
                $('#btn_upload').linkbutton({
                    text : '暂停上传',
                    onClick : function() {
                        webUploader.stop();
                        $('#btn_upload').linkbutton({
                            text:'继续上传',
                            onClick : function() {
                                uploadStart();
                            }
                        });
                    }
                });
            }
		}
        /* 添加子容器 */
        var li = '<li id="fileBox_'+file_id+'" class="diyUploadHover"> \
					<div class="viewThumb">\
						<div class="mainImg"></div> \
					    <input type="hidden">\
					    <div class="diyBar"> \
                            <div class="diyProgress"></div> \
							<div class="diyProgressText">0%</div> \
					    </div> \
						<div class="diySuccess"></div> \
					    <p class="diyControl"><span class="diyLeft"><i></i></span><span class="diyRight"><i></i></span></p>\
					</div> \
				</li>';

		$(li).insertBefore($parentFileBox.find('.upload-pick'));
        /* $parentFileBox.prepend(li); */
        var $fileBox = $parentFileBox.find('#fileBox_'+file_id);
		if (fileLen == 0 && webUploader.option().isMain == 0) {
			$fileBox.children('.viewThumb').children('.mainImg').show();
		}

        /* 绑定取消事件 */
        $fileBox.find('.diyRight').click(function() {
			var li = $('#fileBox_' + file_id);
			var assist_id = li.attr('assist_id');
			var assist_id_next = 0;
			var file_id_next = undefined;

			var fileArr = $parentFileBox.children(".diyUploadHover");
			
			if (li.children('.viewThumb').children('.mainImg').is(':visible') && fileArr.length > 1) {
				$.each(fileArr, function(i, v) {
					if (v.id != ('fileBox_' + file_id)) {
						//console.log('#' + v.id);
						file_id_next = $('#' + v.id);
						assist_id_next = file_id_next.attr('assist_id');
						return false;
					}
				});
			}
			
			if (assist_id) {
                $.messager.confirm('确认信息', '确定删除吗', function (r) {
                    if (!r) {
                        return false;
                    }
                    $.ajax({
                        url: '/web/goods/delImage',
                        type:'post',
                        cache:false,
                        dataType:'json',
                        data: {
                            assist_id:assist_id,
                            assist_id_next:assist_id_next
                        },
                        beforeSend: function(xhr) {
                            $.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
                        },
                        success: function(data) {
                            if (data.errcode == 0) {
                                //console.log(file_id_next);
                                if (file_id_next) {
                                    file_id_next.children('.viewThumb').children('.mainImg').show();
                                }
                                file_id_next = undefined;
                                webUploader.removeFile(file_id);
                                li.remove();
                            }
                            //$.h.c.warnMessager(data.errmsg);
                            //parent.$.h.index.setOperateInfo()
                            parent.$.h.index.setOperateInfo({
                                module: '商品相册',
                                operate: '删除图片',
                                content: data.errmsg,
                                icon: data.errcode == 0 ? 'icon-ok':'hr-error'
                            }, true);
                        },
                        complete: function() {
                            $.messager.progress('close');
                        }
                    });
                });

			} else {
				if (file_id_next) {
					file_id_next.children('.viewThumb').children('.mainImg').show();
				}
				file_id_next = undefined;
				webUploader.removeFile(file_id);
				li.remove();
			}

        });

		/* 绑定主图片事件 */
        $fileBox.find('.diyLeft').click(function() {
			var fileArr = $parentFileBox.children(".diyUploadHover");
			var li = $('#fileBox_' + file_id);
			$.each(fileArr, function(i, v) {
				$('#' + v.id).children('.viewThumb').children('.mainImg').hide();
			});

			var assist_id = li.attr('assist_id') || 0;

			if (file.getStatus() == 'complete') {
				$.ajax({
					url: '/web/goods/mainImage',
					type:'post',
					cache:false,
					dataType:'json',
					data: {
						assist_id:assist_id
					},
					beforeSend: function(xhr) {
						$.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
					},
					success: function(data) {
						if (data.errcode == 0) {
							$('#fileBox_' + file.id).children('.viewThumb').children('.mainImg').show();
						}
						//$.h.c.warnMessager(data.errmsg);
                        parent.$.h.index.setOperateInfo({
                            module: '商品相册',
                            operate: '设置主图',
                            content: data.errmsg,
                            icon: data.errcode == 0 ? 'icon-ok':'hr-error'
                        }, true);
					},
					complete: function() {
						$.messager.progress('close');
					}
				});
			} else {
				$('#fileBox_' + file.id).children('.viewThumb').children('.mainImg').show();
			}
        });

        /**
		 * 绑定左移事件;
        $fileBox.find('.diyLeft').click(function() {
            leftLi($(this).parents('.diyUploadHover').prev(), $(this).parents('.diyUploadHover'));
        });
		 */

        /**
		 * 绑定右移事件;
        $fileBox.find('.diyRight').click(function() {
            rightLi($(this).parents('.diyUploadHover').next(), $(this).parents('.diyUploadHover') );
        });
		 */

        /* 生成预览缩略图 */
        webUploader.makeThumb( file, function( error, dataSrc ) {
            if (!error) {
                $fileBox.find('.viewThumb').append('<img src="'+dataSrc+'" >');
            }
        });
    }
	
})( jQuery );