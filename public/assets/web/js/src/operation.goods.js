/**
 * 商品管理函数
 */
(function($) {
    var history = {
        tempCode : '',
		initialColorIds : undefined,
		initialSizeIds : undefined,
		config : {},
        seed : 0,
        prefix : 'G',
        _id : '',
        _lockVersion : 0,
        sList : [],
        csIds : undefined,
        countNumber : 1,
        images: [],
        _goods_type:'add'
	};

    var webUploader = null;
    
    /**
     * 主要的
     */
    $.h.goods = {
        /**
         * 新增商品事件
         */
        onAddNew : function() {
            resetForm(function() {
                clearWebUploader();
            });
        },
        /**
         * 商品表单保存数据
         */
        onSave : function() {
            var details = $('#goods_details_grid').datagrid('getRows');
            details = JSON.stringify(details);
            details = $.parseJSON(details);
            details.forEach(function(row, index) {
                delete row.color_name;
                delete row.size_name;
            });
            var color_ids = $('#color_ids').combotreegrid('getValues');
            var size_ids = $('#size_ids').combotreegrid('getValues');

            if (history._goods_type == 'update') {
                var url = '/web/goods/updateGoods'
            } else {
               // var url ='/web/goods/saveGoods';
                var url ='/web/goods/createGoods';
            }

            $('#goods_base_from').form('submit', {
                url:url,
                novalidate:false,
                queryParams:{
                    goods_id: history._id,
                    color_ids:color_ids,
                    size_ids:size_ids,
                    temp_code: history.tempCode,
                    lock_version: history._lockVersion,
                    details: JSON.stringify(details),
                    images: history.images.join(',')
                },
                onSubmit: function(param) {
                    var v = $(this).form('validate')
                    if (v) {
                        $.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
                    }
                    return v;
                },
                success:function(data) {
                    data = $.parseJSON(data);
                    if (data.errcode == 0) {
                        $.h.goods.onAddNew();
                        $('#goods_grid').datagrid('reload');
                    } else if (data.errcode == 7) {
						$('#color_ids').combotreegrid('setValues', history.initialColorIds);
						$('#size_ids').combotreegrid('setValues', history.initialSizeIds);
					}
                    $.messager.progress('close');
                   // $.h.c.warnMessager(data.errmsg);

                    parent.$.h.index.setOperateInfo({
                        module:'商品管理',
                        operate:history._goods_type = 'update' ?'修改商品' : '添加商品',
                        content:data.errmsg,
                        icon: data.errcode == 0 ? 'hr-ok' :'hr-error'
                    }, true);
                }
            });
        },
        /**
         * 商品列表行单击事件
         */
        onDblClickRow : function(rowIndex, rowData) {
            history.countNumber = 1;
			$('#goods_code').textbox('textbox').css('background','#fff')
            $.ajax({
				url: '/web/goods/loadGoodsInfo',
				type:'get',
				cache:false,
				dataType:'json',
				data: {goods_code:rowData.goods_code},
				beforeSend: function(xhr) {
                    $('#goods_code').textbox('readonly');
                    //$('#goods_tabs').tabs('select', 0);
					$.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
				},
				success: function(data) {
				    if (data.errcode != 0) {
                        parent.$.h.index.setOperateInfo({
                            module:'商品管理',
                            operate:'修改商品',
                            content:data.errmsg,
                            icon: 'hr-error'
                        }, true);
				        return;
                    }
                    /* 加载表单数据 */
					$('#goods_base_from').form('load', data.data);

                    let color_ids = [];
                    let size_ids = [];
                    for (let i=0;i<data.data.detail.length;++i) {
                        if (color_ids.indexOf(data.data.detail[i].color_id) == -1) {
                            color_ids.push(data.data.detail[i].color_id)
                        }

                        if (size_ids.indexOf(data.data.detail[i].size_id) == -1) {
                            size_ids.push(data.data.detail[i].size_id)
                        }
                    }
                    $('#color_ids').combotreegrid('setValues', color_ids);
                    $('#size_ids').combotreegrid('setValues', size_ids);
                    history._id = data.data.goods_id;
                    $('#goods_id').val(data.data.goods_id);
                    //history
                    history._lockVersion = data.data.lock_version;
                    history._goods_type = 'update';
                   // return ;
                    /* 上传图片的货号 */
                    webUploader.option().formData = {goods_code:data.data.goods_code};
                    webUploader.option().isMain = 0;

					history.initialColorIds =  color_ids;
					history.initialSizeIds =size_ids;
                  //  history.csIds = data.goods.color_ids + "," + data.goods.size_ids;
                    /* 加载色码尺码关系数据 */
                    $('#goods_details_grid').datagrid('loadData', data.data.detail);
                    // 存储当前图片列表
                    history.images = [];
                    data.data.images.forEach(function (item) {
                        history.images.push(item.assist_id);
                    });

                    /* 加载图片数据 */
                    loadServerPicture(data.data.images);
				},
				complete: function() {
					$.messager.progress('close');
				}
			});
        },
		/**
		 * 单元格单击单据列表事件
		 */
		onGoodsClickCell : function(index, field, value, row) {
			switch(field) {
				case 'goods_id1':
					/*
					$.h.goods.onDblClickRow(index, row);
					$('#goods_code').textbox('readonly', false);
					$('#goods_code').textbox('clear');
					*/
					break;
				case 'goods_id2':
					$.h.goods.onDblClickRow(index, row);
					$('#goods_code').textbox('textbox').css('background','#ccc');
					break;
				case 'goods_id3':
					$.messager.confirm('确认对话框', '该操作不可恢复，您确定要删除吗？', function(isRun) {
						if (isRun) {
							$.ajax({
								url: '/web/goods/delGoodsItem',
								type:'get',
								cache:false,
								dataType:'json',
								data: {goods_code:row.goods_code},
								beforeSend: function(xhr) {
									$.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
								},
								success: function(data) {
									if (data.errcode == 0) {
										if (row.goods_id == history._id) {
											$.h.goods.onAddNew();
											//$('#goods_grid').datagrid('reload');
										}
                                        $('#goods_grid').datagrid('reload');
                                        parent.$.h.index.setOperateInfo({
                                            module:'商品管理',
                                            operate:'删除商品',
                                            content:'删除成功',
                                            icon: 'hr-error'
                                        }, true);
									} else  {
                                        parent.$.h.index.setOperateInfo({
                                            module:'商品管理',
                                            operate:'删除商品',
                                            content:data.errmsg,
                                            icon: 'hr-error'
                                        }, true);
									}

								},
								complete: function() {
									$.messager.progress('close');
								}
							});
						}
					});
					break;
			}
			
			
		},
        /**
         * 构造色码尺码数据
         * @param array color
         * @param array size
         */
        onBuildColorSize : function() {
            /* 排序颜色 */
            var nodesColor = $('#color_ids').combotreegrid('grid').treegrid('getCheckedNodes');
            var strNodesColor = $('#color_ids').combotreegrid('getValues');
            if (this.id == 'color_ids') {
                strNodesColor = '';
                if (nodesColor.length > 0) {
                    $.h._sort.id = 'color_sort';
                    nodesColor = nodesColor.sort($.h._sort.asc);
                    nodesColor.forEach(function(row, index) {
                        strNodesColor += strNodesColor == '' ? row.color_id : ',' + row.color_id;
                    });
                    $('#color_ids').combotreegrid('setValues', strNodesColor);
                }
            }
            /* 排序尺码 */
            var nodesSize = $('#size_ids').combotreegrid('grid').treegrid('getCheckedNodes');
            var strNodesSize = $('#size_ids').combotreegrid('getValues');
            if (this.id == 'size_ids') {
                strNodesSize = '';
                if (nodesSize.length > 0) {
                    $.h._sort.id = 'size_sort';
                    nodesSize = nodesSize.sort($.h._sort.asc);
                    nodesSize.forEach(function(row, index) {
                        strNodesSize += strNodesSize == '' ? row.size_id : ',' + row.size_id;
                    });
                    $('#size_ids').combotreegrid('setValues', strNodesSize);
                }
                
            }
            /* 颜色和尺码任意为空则不向下执行 */
            if (nodesColor.length < 1 || nodesSize.length < 1) {
                return;
            }
            /* 颜色和尺码为空或没有任何改变则不向下执行 */
            if ((history.colorIds != '' && history.sizeIds != '') && (history.csIds.toString() == strNodesColor + "," + strNodesSize)) {
                return;
            }
            history.csIds = strNodesColor + "," + strNodesSize;
            
            /*合并存在的GRID数据到历史GRID数据中，如果出现重复则替换，如果不存在则加入*/
            //console.log(JSON.stringify(history.sList));
            var _array = $('#goods_details_grid').datagrid('getRows');
            var isNot;
            if (_array.length > 0) {
                /* 如果历史GRID为空则直接赋值存在的GRID */
                if (history.sList.length == 0) {
                    history.sList = JSON.stringify(_array);
                    history.sList = JSON.parse(history.sList);
                } else {
                    /* 赋值历史GRID数据，如果存在则替换否则加入 */
                    for(var i in _array) {
                        isNot = true;
                        for(var n in history.sList) {
                            if (_array[i].color_id == history.sList[n].color_id && _array[i].size_id == history.sList[n].size_id) {
                                history.sList[n] = JSON.parse(JSON.stringify(_array[i]));
                                isNot = false;
                                continue;
                            }
                        }
                        if (isNot) {
                            history.sList.push(JSON.parse(JSON.stringify(_array[i])));
                        }
                    }
                }
            }
            //console.log(JSON.stringify(history.sList));

            /*重新构造颜色和尺码*/
            var goodsCode = $.trim($('#goods_code').textbox('getValue'));
            var buildData = [];
            var len = 4;
            var seed = history.seed;
            var tempCode = '';

            nodesColor.forEach(function(color) {
                nodesSize.forEach(function(size) {
                    isNot = true;
                    /* 如果历史GRID中存在则读取历史GRID数据 */
                    for(var i in history.sList) {
                        if (color.color_id == history.sList[i].color_id && size.size_id == history.sList[i].size_id) {
                            buildData.push(history.sList[i]);
                            isNot = false;
                            continue;
                        }
                    }
                    /* 如果历史GRID中不存在则重新构造 */
                    if (isNot) {
                        if (seed < 10000) {
                            /* 循环获取不重复的条码 */
                            while(isNot) {
                                isNot = false;
                                tempCode = EAN13(history.config.prefix + (Array(len).join('0') + seed.toString()).slice(-len));
                                for(var i in history.sList) {
                                    //console.log(history.sList[i].goods_sbarcode.substring(0,12) +"=="+ tempCode.substring(0,12));
                                    if (history.sList[i].goods_sbarcode.substring(0,12) == tempCode.substring(0,12)) {
                                        isNot = true;
                                        continue;
                                    }
                                }
                                seed++;
                            }
                        } else {
                            tempCode = '';
                        }
                        /* 构造颜色和尺码的交叉行数据 */

                        /* 循环获取不重复的单品货号 */
                        if (history.countNumber < 10) {
                            scode = '00' + history.countNumber;
                        } else if (history.countNumber < 100) {
                            scode = '0' + history.countNumber;
                        } else {
                            scode = history.countNumber;
                        }
                        history.countNumber+=1;
                        var row = {
                            'color_name' : color.color_name,
                            'color_id' : color.color_id,
                            'size_id' : size.size_id,
                            'size_name' : size.size_name,
                            'goods_scode' : goodsCode + scode,
                            'goods_sbarcode' : tempCode
                        };
                        buildData.push(row);
                    }
                });
//                ++number;
            });
            $('#goods_details_grid').datagrid('loadData',buildData);
            //console.log(JSON.stringify(history.sList));
        },
        /**
         * 商品列表行单击事件
         */
        onRefreshCommodityCode : function() {
            var target =$('#commodity_code');
            $.ajax({
                url: '/Manage/Commodity/refreshCommodityCode',
                type:'get',
                cache:false,
                dataType:'json',
                beforeSend: function(xhr) {
                    target.textbox({
                        disabled:true,
                        icons:[{
                            iconCls:'kbi-icon-loading',
                            handler: function(e) {  }
                        }]
                    });
                },
                success: function(data) {
                    target.textbox('setValue', data);
                },
                complete: function() {
                    target.textbox({
                        disabled:false,
                        icons:[{
                            iconCls:'icon-reload',
                            handler: $.h.commodity.onRefreshCommodityCode
                        }]
                    });
                }
            });
        },
        /**
         * 编辑表格
         */
        grid : {
            endEditing : function() {
                var opts = $(this).datagrid('options');
				if (opts.editIndex == undefined) { return true; }
				if ($(this).datagrid('validateRow', opts.editIndex)){
					$(this).datagrid('endEdit', opts.editIndex);
					opts.editIndex = undefined;
					return true;
				} else {
					return false;
				}
			},
			onClickCell : function(rowIndex, field, value) {
				var opts = $(this).datagrid('options');
				if (opts.endEditing.call(this)) {
					$(this).datagrid('editCell', {index:rowIndex,field:field});
					opts.editIndex = rowIndex;
					for(var i = 0, n = opts.editFields.length; i < n; i++) {
						if (field == opts.editFields[i]) {
							opts.editFieldIndex = i;
						}
					}
				}
			},
			onBeforeLoad : function(param) {
                $(this).datagrid('options').editFields = ['goods_scode','goods_sbarcode'];
				$(this).datagrid('bindKeyEvent');
			},
            onQuery : function() {
                var condition = {};
                if ($(this).attr('query') == '1') {
                    var goodsName = $.trim($('#goods_name_query').textbox('getValue'));
                    var goodsCode = $.trim($('#goods_code_query').textbox('getValue'));
                    var goodsBarcode = $.trim($('#goods_barcode_query').textbox('getValue'));
                    var goodsStatus = $.trim($('#goods_status_query').combobox('getValue'));
                    condition.goods_name = goodsName;
                    condition.goods_code = goodsCode;
                    condition.goods_barcode = goodsBarcode;
                    condition.goods_status = goodsStatus;
                }

                $('#goods_grid').datagrid('load', condition);
            }
        },
        copyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-copy" style="display:inline-block;width:16px;height:16px;"></a>';
        },
        delFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-del" style="display:inline-block;width:16px;height:16px;"></a>';
        },
        modifyFormatter : function(value, row, index) {
            return '<a href="javascript:void(0);" class="datagrid-row-modify" style="display:inline-block;width:16px;height:16px;"></a>';
        },
        colorFormtter:function (value, row, index) {
            let color_list = [];
            for(let i=0; i<row.detail.length;++i) {
                if (color_list.indexOf(row.detail[i].color_name) == -1) {
                    color_list.push(row.detail[i].color_name);
                }
            }

            return color_list.join(',');
        },
        sizeFormtter:function (value, row, index) {
            let size_list = [];
            for(let i=0; i<row.detail.length;++i) {
                if (size_list.indexOf(row.detail[i].size_name) ==  -1) {
                    size_list.push(row.detail[i].size_name);
                }
            }
            return size_list.join(',');
        }
    }

    $.h.goods.onLoad = function() {
        history.tempCode = UUID(20, 36);
        resetForm(function() {
            webUploader = $('#goodsUpload').diyUpload({
                url:'/web/goods/imageUpload',
				formData: {goods_code:history.tempCode},
                success:function(data) {
                   // console.log(data);
                    // 图片上传成功 存储起来
                    history.images.push(data.data.assist_id);
                    console.log('图片上传成功 存储起来');
                    console.log(history.images);
				},
                error:function(err) { },
                buttonText : '',
                accept: {
                    title: "Images",
                    extensions: 'gif,jpg,jpeg,bmp,png'
                },
                thumb:{
                    width:100,
                    height:90,
                    quality:100,
                    allowMagnify:true,
                    crop:true,
                    type:"image/jpeg"
                }
            });
        },true);
    }
    /**
     * 重置表单
     */
    function resetForm(_fun, needCode = true) {
        if (_fun) { _fun(); }

        history.images = [];
        $('#goods_tabs').tabs('select',0);
        var goodsCode = $('#goods_code');
        var goodsBarcode = $('#goods_barcode');

        /* 重置表单 */
        $('#goods_base_from').form('reset');
        $('#goods_bnumber').numberspinner('textbox').val(0);
        $('#goods_year').numberspinner('textbox').val(new Date().getFullYear());
        $('#goods_sort').numberspinner('textbox').val(100);
        $('#goods_llimit').numberspinner('textbox').val(0);
        $('#goods_ulimit').numberspinner('textbox').val(0);

        goodsCode.textbox('readonly', false);
        history.countNumber = 1;
        history._goods_type = 'add';

        $('#goods_details_grid').datagrid('loadData', []);
        if (needCode) {
            //获取实例的goods_code
            $.ajax({
                url:'/web/goods/newGoods',
                type:'get',
                dataType:'json',
                success:function (res) {
                    $('#goods_code').textbox('setValue', res.data.goods_code);
                    $('#goods_id').val(res.data.goods_id);
                    /* 加载图片数据 */
                    loadServerPicture(res.data.images);
                    history._id = res.data.goods_id;
                    console.log(history._id);
                }
            });
        }

        $.ajax({
            url: '/web/goods/loadConfig',
            type:'get',
            cache:false,
            dataType:'json',
            beforeSend: function(xhr) {
                goodsCode.textbox({editable : false,icons: [{iconCls:'kbi-icon-loading'}]});
                goodsBarcode.textbox({editable : false,icons: [{iconCls:'kbi-icon-loading'}]});
            },
            success: function(data) {
                /* 单品条码前缀 */
                history.config = data.config;
                /* 单品条码种子 */
                history.seed = data.seed;

                //goodsCode.textbox('setValue', '');
                //goodsBarcode.textbox('setValue', '');
                if (data.errcode == 1) {
                    $.h.c.warnMessager(data.errmsg);
                }

                if (history.seed > 9999) {
                    $.messager.alert('警告','&nbsp;&nbsp;自动声场条码失败，现有厂商识别码下的产品代码已用完，请到条码管理配置新的厂商识别码！','warning');
                }
            },
            complete: function() {
                goodsCode.textbox({editable : true,icons: []});
                goodsBarcode.textbox({editable : true,icons: []});
            }
        });

        history._id = '';
        history._lockVersion = 0;
        history.sList = [];
        history.csIds = '';
        history.tempCode = UUID(20, 36);
        /* 上传图片的货号 */
        webUploader.option().formData = {goods_code:history.tempCode};
        webUploader.option().isMain = 0;
    }


    function loadServerPicture(list) {
        $.each(list, function(index, item) {
            if (item.assist_status == 9) {
                webUploader.option().isMain = 1;
                return false;
            }
        });
        clearWebUploader(function() {
            $.each(list, function(index,item){
                getFileObject(item, function (fileObject) {
                    var wuFile = new WebUploader.Lib.File(WebUploader.guid('rt_'), fileObject);
                    var file = new WebUploader.File(wuFile);
                    file.setStatus('complete');
                    webUploader.addFiles(file);
                    $('#fileBox_'+file.id).children('.viewThumb').children('.diySuccess').show();
                    $('#fileBox_'+file.id).attr('assist_id', item.assist_id);
                    if (item.assist_status == 9) {
                        $('#fileBox_'+file.id).children('.viewThumb').children('.mainImg').show();
                    }
                })
            });
        });
    }

    //$.h.goods.onClearWebUploader = clearWebUploader;

    function clearWebUploader(_fun) {
        var fileArr = webUploader.getFiles();
        $.each(fileArr, function(i, v){
            webUploader.removeFile(v.id);
            $('#fileBox_'+v.id).remove();
        });
        webUploader.reset();

        if (_fun) { _fun(); }
    }

    var getFileBlob = function (url, cb) {
		var xhr = new XMLHttpRequest();
		xhr.open("GET", url.assist_url);
		xhr.responseType = "blob";
		xhr.addEventListener('load', function() {
			cb(xhr.response);
		});
		xhr.send();
	};
	var blobToFile = function (blob, name) {
		blob.lastModifiedDate = new Date();
		blob.name = name;
		return blob;
	};
	var getFileObject = function(filePathOrUrl, cb) {
		getFileBlob(filePathOrUrl, function (blob) {
			cb(blobToFile(blob, 'test.jpg'));
		});
	};

    $.h.goods.config = function() {
        
        /*$('#goods_layout').layout('collapse','east');*/
    }

    /**
     * EAN13 编码
     */
    function EAN13(n){
        var n = n.toString();
        var a;
        a = ((n[1] * 1 + n[3] * 1 + n[5] * 1 + n[7] * 1 + n[9] * 1 + n[11] * 1) * 3 + n[0] * 1 + n[2] * 1 + n[4] * 1 + n[6] * 1 + n[8] * 1 + n[10] * 1) % 10;
        a = a == 0 ? 0 : 10 - a;
        return n+a;
    }

    var num = 1001;
    function insertData() {
        $.ajax({
            url: '/web/goods/saveGoods',
            type:'post',
            cache:false,
            dataType:'json',
            data: {
                goods_name:'衣服' + num,
                goods_code:'100' + num,
                goods_pprice:1,
                goods_wprice:2,
                goods_srprice:0,
                goods_rprice:3,
                goods_barcode:'200' + num,
                goods_bnumber:0,
                category_id:3,
                brand_id:3,
                goods_sex:0,
                unit_id:17,
                goods_year:2018,
                goods_season:1,
                goods_sort:100,
                goods_status:1,
                goods_llimit:2,
                goods_ulimit:2,
                goods_id:'',
                lock_version:0,
                details:'[{"color_id":10,"size_id":4,"goods_scode":"","goods_sbarcode":"","lock_version":0},{"color_id":10,"size_id":5,"goods_scode":"","goods_sbarcode":"","lock_version":0},{"color_id":4,"size_id":4,"goods_scode":"","goods_sbarcode":"","lock_version":0},{"color_id":4,"size_id":5,"goods_scode":"","goods_sbarcode":"","lock_version":0}]'
            },
            beforeSend: function(xhr) {
                
            },
            success: function(data) {
            },
            complete: function() {
                if (num != 10000) {
                    num++;
                    insertData();
                }
            }
        });
    }


    /**
     * 产生 UUID
     */
    function UUID(len, radix) {
        var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'.split('');
        var uuid = [], i;
        radix = radix || chars.length;
        if (len) {
            // Compact form
            for (i = 0; i < len; i++) {
                uuid[i] = chars[0 | Math.random() * radix];
            }
        } else {
            // rfc4122, version 4 form
            var r;
            // rfc4122 requires these characters
            uuid[8] = uuid[13] = uuid[18] = uuid[23] = '-';
            uuid[14] = '4';
            // Fill in random data.  At i==19 set the high bits of clock sequence as
            // per rfc4122, sec. 4.1.5
            for (i = 0; i < 36; i++) {
                if (!uuid[i]) {
                    r = 0 | Math.random() * 16;
                    uuid[i] = chars[(i == 19) ? (r & 0x3) | 0x8 : r];
                }
            }
        }
        return uuid.join('');
    }
    
})(jQuery);