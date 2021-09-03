$.h = {};

/**
 * JSON 排序
 */
$.h._sort = {
    id : '',
    desc : function(x, y) {
        return (x[$.h._sort.id] < y[$.h._sort.id]) ? 1 : -1;
    },
    asc : function(x, y) {
        return (x[$.h._sort.id] > y[$.h._sort.id]) ? 1 : -1;
    }
};

var _ = {
	h : {},
	r : {},
	f : {}
};

/**
 * 转换数字
 */
$.toFloat = function(s) {
	var reg = /^(-?\d+)(\.\d+)?$/;
	if (reg.test($.trim(s))) {
		return parseFloat(s);
	} else {
		return 0;
	}
};

/**
 * 拼接 URL
 */
$.toUrl = function(c, a) {
	return '/' + ASSETS + '/' + c  + '/' +  a;
};

/**
 * 数字金额转换为大写人民币汉字
 */
$.toChineseCurrency = function(money) {
	//汉字的数字
	var cnNums = new Array('零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
	//基本单位
	var cnIntRadice = new Array('', '拾', '佰', '仟');
	//对应整数部分扩展单位
	var cnIntUnits = new Array('', '万', '亿', '兆');
	//对应小数部分单位
	var cnDecUnits = new Array('角', '分', '毫', '厘');
	//整数金额时后面跟的字符
	var cnInteger = '整';
	//整型完以后的单位
	var cnIntLast = '元';
	//最大处理的数字
	var maxNum = 999999999999999.9999;
	//金额整数部分
	var integerNum;
	//金额小数部分
	var decimalNum;
	//输出的中文金额字符串
	var chineseStr = '';
	//分离金额后用的数组，预定义
	var parts;
	if (money == '') { return ''; }
	money = parseFloat(money);
	if (money >= maxNum) {
		//超出最大处理数字
		return '';
	}
	if (money == 0) {
		chineseStr = cnNums[0] + cnIntLast + cnInteger;
		return chineseStr;
	}
	//转换为字符串
	money = money.toString();
	if (money.indexOf('.') == -1) {
		integerNum = money;
		decimalNum = '';
	} else {
		parts = money.split('.');
		integerNum = parts[0];
		decimalNum = parts[1].substr(0, 4);
	}
	//获取整型部分转换
	if (parseInt(integerNum, 10) > 0) {
		var zeroCount = 0;
		var IntLen = integerNum.length;
		for (var i = 0; i < IntLen; i++) {
			var n = integerNum.substr(i, 1);
			var p = IntLen - i - 1;
			var q = p / 4;
			var m = p % 4;
			if (n == '0') {
				zeroCount++;
			} else {
				if (zeroCount > 0) {
					chineseStr += cnNums[0];
				}
				//归零
				zeroCount = 0;
				chineseStr += cnNums[parseInt(n)] + cnIntRadice[m];
			}
			if (m == 0 && zeroCount < 4) {
				chineseStr += cnIntUnits[q];
			}
		}
		chineseStr += cnIntLast;
	}
	//小数部分
	if (decimalNum != '') {
		var decLen = decimalNum.length;
		for (var i = 0; i < decLen; i++) {
			var n = decimalNum.substr(i, 1);
			if (n != '0') {
				chineseStr += cnNums[Number(n)] + cnDecUnits[i];
			}
		}
	}
	if (chineseStr == '') {
		chineseStr += cnNums[0] + cnIntLast + cnInteger;
	} else if (decimalNum == '') {
		chineseStr += cnInteger;
	}
	return chineseStr;
};
/**
 * 转换为金额
 * 参数: s 需要转换的值
 * 参数: prefix 前缀
 * 参数: suffix 后缀
 */
$.formatMoney = function(s,prefix,suffix) {
    var _v = (prefix ? prefix : '') + $.toFloat(s).toFixed(2) + (suffix ? suffix : '');
    return _v;
};

/**
 * 对Date的扩展，将 Date 转化为指定格式的String
 * 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
 * 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
 * 例子：
 * (new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
 * (new Date()).Format("yyyy-M-d h:m:s.S") ==> 2006-7-2 8:9:4.18
 * 
 */
Date.prototype.Format = function(fmt) { // author: meizz
	
	var o = {
		"M+" : this.getMonth() + 1, // 月份
		"d+" : this.getDate(), // 日
		"h+" : this.getHours(), // 小时
		"m+" : this.getMinutes(), // 分
		"s+" : this.getSeconds(), // 秒
		"q+" : Math.floor((this.getMonth() + 3) / 3), // 季度
		"S" : this.getMilliseconds()
	// 毫秒
	};
	if (/(y+)/.test(fmt))
		fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "")
				.substr(4 - RegExp.$1.length));
	for ( var k in o)
		if (new RegExp("(" + k + ")").test(fmt))
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k])
					: (("00" + o[k]).substr(("" + o[k]).length)));
	return fmt;
};
/**
 * 时间戳与日期转换
 * 例（日期转换时间戳）: $.DT.DateToUnix('2014-5-15 20:20:20');
 * 例（时间戳转换日期）: $.DT.UnixToDate(1325347200);
 *
 * 例 Today : $.DT.Today();
 */
(function($) {
    $.extend({
        DT : {
            /**
             * 当前时间戳
             * @return <int> unix时间戳(秒)
             */
            CurTime : function() {
                return Date.parse(new Date()) / 1000;
            },
            /**              
             * 日期 转换为 Unix时间戳
             * @param <string> 2014-01-01 20:20:20  日期格式
             * @return <int> unix时间戳(秒)
             */
            DateToUnix: function(string) {
                var f = string.split(' ', 2);
                var d = (f[0] ? f[0] : '').split('-', 3);
                var t = (f[1] ? f[1] : '').split(':', 3);
                return (new Date(
                        parseInt(d[0], 10) || null,
                        (parseInt(d[1], 10) || 1) - 1,
                        parseInt(d[2], 10) || null,
                        parseInt(t[0], 10) || null,
                        parseInt(t[1], 10) || null,
                        parseInt(t[2], 10) || null
                        )).getTime() / 1000;
            },
            /**              
             * 时间戳转换日期
             * @param <int> unixTime    待时间戳(秒)              
             * @param <int> isFull      返回完整时间(1、Y-m-d 2、Y-m-d H:i 3、Y-m-d H:i:s)
             * @param <bool> isFormat   是否带有格式
             */
            UnixToDate: function(unixTime, isFull, isFormat) {
                var time = new Date(parseInt(unixTime) * 1000);
                var ymdhis = "";
                ymdhis += time.getFullYear() + "-";
                ymdhis += isFormat ? "<strong>" : "";
                ymdhis += ((time.getMonth()+1) < 10 ? "0" + (time.getMonth()+1) : (time.getMonth()+1));
                ymdhis += isFormat ? "</strong>-<strong>" : "-";
                ymdhis += (time.getDate() < 10 ? "0" + time.getDate() : time.getDate());
                ymdhis += isFormat ? "</strong>" : "";
				switch(isFull) {
                    case 0:
                        break;
					case 1:
						ymdhis += " " + (time.getHours() < 10 ? "0" + time.getHours() : time.getHours());
						break;
					case 2:
						ymdhis += " " + (time.getHours() < 10 ? "0" + time.getHours() : time.getHours()) + ":";
						ymdhis += (time.getMinutes() < 10 ? "0" + time.getMinutes() : time.getMinutes());
						break;
					case 3:
						ymdhis += " " + (time.getHours() < 10 ? "0" + time.getHours() : time.getHours()) + ":";
						ymdhis += (time.getMinutes() < 10 ? "0" + time.getMinutes() : time.getMinutes()) + ":";
						ymdhis += (time.getSeconds() < 10 ? "0" + time.getSeconds() : time.getSeconds());
						break;
					default:
				}
                return ymdhis;
            },
			/* 获得当前时间戳 */
            GetUnix: function() {
                return new Date().getTime();
            },
			/* 获得两个时间段“今天-明天” */
			Today : function() {
				var today = {};
				var nowDate = new Date();
				today.beginFormat = nowDate.Format("yyyy-MM-dd");
				today.begin = $.DT.DateToUnix(today.beginFormat);
				nowDate.setDate(nowDate.getDate() + 1);
				today.endFormat = nowDate.Format("yyyy-MM-dd");
				today.end = $.DT.DateToUnix(today.endFormat);
				return today;
			},
			/* 获得两个时间段“昨天-今天” */
			Yesterday : function() {
				var yesterday = {};
				var nowDate = new Date();
				nowDate.setDate(nowDate.getDate() - 1);
				yesterday.beginFormat = nowDate.Format("yyyy-MM-dd");
				yesterday.begin = $.DT.DateToUnix(yesterday.beginFormat);
				nowDate = new Date();
				nowDate.setDate(nowDate.getDate());
				yesterday.endFormat = nowDate.Format("yyyy-MM-dd");
				yesterday.end = $.DT.DateToUnix(yesterday.endFormat);
				return yesterday;
			},
			/* 获得两个时间段“本周开始-本周结束” */
			ThisWeek : function() {
				var thisWeek = {};
				var nowDate = new Date();
				nowDate.setDate(nowDate.getDate() - nowDate.getDay());
				thisWeek.beginFormat = nowDate.Format("yyyy-MM-dd");
				thisWeek.begin = $.DT.DateToUnix(thisWeek.beginFormat);
				nowDate = new Date();
				nowDate.setDate(nowDate.getDate() + (7 - nowDate.getDay()));
				thisWeek.endFormat = nowDate.Format("yyyy-MM-dd");
				thisWeek.end = $.DT.DateToUnix(thisWeek.endFormat);
				return thisWeek;
			},
			/* 获得两个时间段“本月开始-本月结束” */
			ThisMonth : function() {
				var thisMonth = {};
				var nowDate = new Date();
				nowDate.setDate(1);
				thisMonth.beginFormat = nowDate.Format("yyyy-MM-dd");
				thisMonth.begin = $.DT.DateToUnix(thisMonth.beginFormat);
				nowDate.setDate((new Date(nowDate.getFullYear(), nowDate.getMonth() + 1, 0)).getDate());
				thisMonth.endFormat = nowDate.Format("yyyy-MM-dd");
				thisMonth.end = $.DT.DateToUnix(thisMonth.endFormat);
				return thisMonth;
			},
			/* 获得两个时间段“本季度开始-本季度结束” */
			ThisQuarter : function() {
				var thisQuarter = {};
				var nowDate = new Date();
				if(nowDate.getMonth() < 3) {
					nowDate.setMonth(0);
					nowDate.setDate(1);
					thisQuarter.beginFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.begin = $.DT.DateToUnix(thisQuarter.beginFormat);

					nowDate.setMonth(2);
					nowDate.setDate(31);
					thisQuarter.endFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.end = $.DT.DateToUnix(thisQuarter.beginFormat);
				} else if(nowDate.getMonth() < 6) {
					nowDate.setMonth(3);
					nowDate.setDate(1);
					thisQuarter.beginFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.begin = $.DT.DateToUnix(thisQuarter.beginFormat);

					nowDate.setMonth(5);
					nowDate.setDate(30);
					thisQuarter.endFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.end = $.DT.DateToUnix(thisQuarter.beginFormat);
				} else if(nowDate.getMonth() < 9) {
					nowDate.setMonth(6);
					nowDate.setDate(1);
					thisQuarter.beginFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.begin = $.DT.DateToUnix(thisQuarter.beginFormat);
					nowDate.setMonth(8);
					nowDate.setDate(30);
					thisQuarter.endFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.end = $.DT.DateToUnix(thisQuarter.beginFormat);
				} else if(nowDate.getMonth() < 12) {
					nowDate.setMonth(9);
					nowDate.setDate(1);
					thisQuarter.beginFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.begin = $.DT.DateToUnix(thisQuarter.beginFormat);
					nowDate.setMonth(11);
					nowDate.setDate(31);
					thisQuarter.endFormat = nowDate.Format("yyyy-MM-dd");
					thisQuarter.end = $.DT.DateToUnix(thisQuarter.beginFormat);
				}
				return thisQuarter;
			},
			/* 获取系统当前时间的前一个月时间 */
			ThisAgoMonth : function() {
				var thisAgoMonth = {};
				var nowDate = new Date();
				nowDate.setDate(nowDate.getDate() + 1);
				nowDate.setMonth(nowDate.getMonth() - 1);
				thisAgoMonth.beginFormat = nowDate.Format("yyyy-MM-dd");
				thisAgoMonth.begin = $.DT.DateToUnix(thisAgoMonth.beginFormat);
				nowDate= new Date();
				thisAgoMonth.endFormat = nowDate.Format("yyyy-MM-dd");
				thisAgoMonth.end = $.DT.DateToUnix(thisAgoMonth.endFormat);
				return thisAgoMonth;
			}
        }
    });
})(jQuery);
/*-----------------------------------------------------------------------------------------*/
/**
 * 扩展 datagrid 属性
 */
$.extend($.fn.datagrid.defaults, {
	/**
	 * 编辑对象正在编辑的索引
	 */
	editFieldIndex : 0,
	/**
	 * 编辑对象需要编辑的字段数组
	 */
	editFields : [],
	/**
	 * 编辑对象开始编辑的索引，结束编辑置为 undefined
	 */
	editIndex : undefined,
	/**
	 * 结束 datagrid 的编辑状态
	 */
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
	}
});

/**
 * 扩展 datagrid 方法
 */
$.extend($.fn.datagrid.methods, {
	/**
	 * 扩展 datagrid 方法
	 * 方法名: editCell
	 * 参数1: {index:需要编辑的行索引,field:需要编辑的字段名称}
	 *
	 * 调用实例:
	 * var rowIndex = $(this).datagrid('getRowIndex',$(this).datagrid('getSelected'));
	 * var field = 'name';
	 * $(this).datagrid('selectRow',rowIndex).datagrid('editCell',{index:rowIndex,field:field});
	 */
	editCell : function(jq,param) {
		return jq.each(function() {
			var target = $(this);
			var opts = target.datagrid('options');
			/*
			var fields = target.datagrid('getColumnFields',true).concat(target.datagrid('getColumnFields'))
			*/
			var fields = opts.editFields;
			for(var i = 0; i < fields.length; i++) {
				var col = target.datagrid('getColumnOption', fields[i]);
				col.editor1 = col.editor;
				if (fields[i] != param.field){
					col.editor = null;
				}
			}
			target.datagrid('beginEdit', param.index);
			for(var i = 0; i < fields.length; i++){
				var col = target.datagrid('getColumnOption', fields[i]);
				col.editor = col.editor1;
			}
            var edField = target.datagrid('getEditor', param);

			if (edField) {
				switch(edField.type) {
					case 'textbox':
					case 'numberbox':
						$(edField.target).textbox('textbox').select();
						break;
					case 'combobox':
						/* $(edField.target).combobox('showPanel'); */
						break;
				}
			}
			
		});
	},
    unbindKeyEvent : function(jq) {
        return jq.each(function() {
			$(this).datagrid('getPanel').panel('panel').attr('tabindex', 1).unbind('.edit');
        });
    },
	/**
	 * 扩展 datagrid 方法，绑定键盘事件。
	 * 方法名: bindKeyEvent
	 *
	 * 调用实例: 需要先设置 datagrid 扩展属性 editFields
	 * $(this).datagrid('options').editFields = ['style_name','barcode','style_Code','color_name','count','tag_price','discount','paid_price'];
	 * $(this).datagrid('bindKeyEvent');
	 */
	bindKeyEvent : function(jq, event) {
		return jq.each(function() {
			
			var target = $(this);
			var panel = target.datagrid('getPanel').panel('panel');
			var opts = $(this).datagrid('options');
			var KeyDown = false;
			panel.attr('tabindex', 1).unbind('.edit').bind('keydown.edit', function (e) {
				if (KeyDown) return;
				KeyDown = true;
				var row = target.datagrid('getSelected');
				switch(e.keyCode) {
					/**
					 * 键盘方向键 Left
					 */
					case 37:
						/**
						 * 取消事件的默认动作
						 */
						e.preventDefault();
						panel.attr('tabindex', 1).focus();
						if (row) {
							var rowIndex = target.datagrid('getRowIndex', row);
							opts.editFieldIndex = (opts.editFieldIndex <= 0) ? (opts.editFields.length - 1) : (opts.editFieldIndex - 1);
							if (opts.onKeyUpDownEvent) {
								opts.onKeyUpDownEvent.call(target, rowIndex, opts.editFields[opts.editFieldIndex], row[opts.editFields[opts.editFieldIndex]], row);
							}
						}
						
						break;
					/**
					 * 键盘方向键 Up
					 */
					case 38:
						/**
						 * 取消事件的默认动作
						 */
						e.preventDefault();
						panel.attr('tabindex', 1).focus();
						var rowIndex = row ? target.datagrid('getRowIndex', row) - 1 : target.datagrid('getRows').length - 1;
						if (rowIndex == -1) {
							rowIndex = target.datagrid('getRows').length - 1;
						}
						var oldRowIndex = rowIndex + 1;
						if (oldRowIndex == target.datagrid('getRows').length) {
							oldRowIndex = 0;
						}
						target.datagrid('unselectRow', oldRowIndex);
						target.datagrid('selectRow', rowIndex);
						/* 重新获得选择行 */
						row = $.extend(true, {}, target.datagrid('getSelected'));
						if (opts.onKeyUpDownEvent) {
							opts.onKeyUpDownEvent.call(target, rowIndex, opts.editFields[opts.editFieldIndex], row[opts.editFields[opts.editFieldIndex]], row);
						}
						
						break;
					/**
					 * 键盘方按键 Tab
					 */
					case 9:
					/**
					 * 键盘方向键 Right
					 */
					case 39:
						/**
						 * 取消事件的默认动作
						 */
						e.preventDefault();
						panel.attr('tabindex', 1).focus();
						if (row) {
							var rowIndex = target.datagrid('getRowIndex', row);
							opts.editFieldIndex = (opts.editFieldIndex >= (opts.editFields.length - 1)) ? 0 : opts.editFieldIndex + 1;
							if (opts.onKeyUpDownEvent) {
								opts.onKeyUpDownEvent.call(target, rowIndex, opts.editFields[opts.editFieldIndex], row[opts.editFields[opts.editFieldIndex]], row);
							}
						}
						
						break;
					/**
					 * 键盘方向键 Down
					 */
					case 40:
						/**
						 * 取消事件的默认动作
						 */
						e.preventDefault();
						panel.attr('tabindex', 1).focus();
						var rowIndex = row ? target.datagrid('getRowIndex', row) + 1 : 0;
						if (rowIndex == target.datagrid('getRows').length) {
							rowIndex = 0;
						}
						var oldRowIndex = rowIndex - 1;
						if (oldRowIndex == -1) {
							oldRowIndex = target.datagrid('getRows').length - 1;
						}
						target.datagrid('unselectRow', oldRowIndex);
						target.datagrid('selectRow', rowIndex);
						/* 重新获得选择行 */
						row = $.extend(true, {}, target.datagrid('getSelected'));
						if (opts.onKeyUpDownEvent) {
							opts.onKeyUpDownEvent.call(target, rowIndex, opts.editFields[opts.editFieldIndex], row[opts.editFields[opts.editFieldIndex]], row);
						}
						
						break;
					/**
					 * 键盘方按键 .
					 */
					case 190:
					/**
					 * 键盘方按键 .
					 */
					case 110:
						break;
					/**
					 * 键盘方按键 Backspace
					 */
					case 8:
						break;
                    /**
					 * 键盘方按键 Enter
					 */
					case 13:
					/**
					 * 键盘方按键 Esc
					 */
					case 27:
						if (opts.endEditing.call(target)) {
							panel.attr('tabindex', 1).focus();
						}
						break;
					/**
					 * 键盘空格键
					 */
					case 32:
						if (event && event.event32) {
							event.event32();
						}
						break;
				}
			}).bind('keyup.edit', function(e) {
				KeyDown = false;
			}).bind('click.edit', function(e) {
                if (e.target.className.indexOf('datagrid-cell') < 0 && e.target.className != '' && opts.editIndex != undefined) {
                    opts.endEditing.call(target)
                }
            });
		});
	},
    /**
     * 行号宽度
     */
    fixRownumber : function (jq) {
        return jq.each(function () {
            var panel = $(this).datagrid("getPanel");
            //获取最后一行的number容器,并拷贝一份
            var clone = $(".datagrid-cell-rownumber", panel).last().clone();
            //由于在某些浏览器里面,是不支持获取隐藏元素的宽度,所以取巧一下
            clone.css({
                "position" : "absolute",
                left : -1000
            }).appendTo("body");
            var width = clone.width("auto").width();
            //默认宽度是25,所以只有大于25的时候才进行fix
            if (width > 25) {
                //多加5个像素,保持一点边距
                $(".datagrid-header-rownumber,.datagrid-cell-rownumber", panel).width(width + 5);
                //修改了宽度之后,需要对容器进行重新计算,所以调用resize
                $(this).datagrid("resize");
                //一些清理工作
                clone.remove();
                clone = null;
            } else {
                //还原成默认状态
                $(".datagrid-header-rownumber,.datagrid-cell-rownumber", panel).removeAttr("style");
            }
        });
    }
});

/**
 * 扩展 combogrid 属性
 */
$.extend($.fn.combogrid.defaults, {
    /**
	 * combogrid 查询使用的地址
	 */
	searchUrl : undefined,
    /**
	 * 重写键盘事件默认函数
	 */
    keyHandler : {
        up : function(e) {
            var target = $(e.data.target);
            var row = target.combogrid('grid').datagrid('getSelected');
            if (row) {
                //取得选中行的rowIndex
                var rowIndex = target.combogrid('grid').datagrid('getRowIndex', row);
                //向上移动到第一行为止
                if (rowIndex > 0) {
                    target.combogrid('grid').datagrid('selectRow', rowIndex - 1);
                }
            } else {
                var rows = target.combogrid('grid').datagrid('getRows');
                target.combogrid('grid').datagrid('selectRow', rows.length - 1);
            }
        },
        down : function(e) {
            var target = $(e.data.target);
            //取得选中行
            var row = target.combogrid('grid').datagrid('getSelected');
            if (row) {
                //取得选中行的rowIndex
                var rowIndex = target.combogrid('grid').datagrid('getRowIndex', row);
                //向下移动到当页最后一行为止
                if (rowIndex < target.combogrid('grid').datagrid('getData').rows.length - 1) {
                    target.combogrid('grid').datagrid('selectRow', rowIndex + 1);
                }
            } else {
                target.combogrid('grid').datagrid('selectRow', 0);
            }
        },
        left : function(e) {},
        right : function(e) {},
        enter : function(e) {
            var target = $(e.data.target);
            target.combogrid('hidePanel');
            var row = target.combogrid('grid').datagrid('getSelected');
            if (row) {
                alert(JSON.stringify(row));
            }
            target.combogrid('grid').datagrid('unselectAll');
            target.combogrid('grid').datagrid('loadData',{total:0,rows:[]});
        },
        query : function(q,e) {
            var target = $(e.data.target);
            var keyword = target.combogrid('getText');
            target.combogrid('grid').datagrid('options').url = target.combogrid('options').searchUrl;
            alert( target.combogrid('options').searchUrl);
            target.combogrid('grid').datagrid('reload',{keyword:keyword});
        }
    }
});

/**
 * 扩展 textbox clear图标
 *
 */

/**
 * 扩展 textbox clear 图标
 * 方法名: addClearBtn
 *
 * 调用实例: 需要先设置 textbox 扩展属性 addClearBtn
 * $('#tt').textbox().textbox('addClearBtn', 'icon-clear');
 */
$.extend($.fn.textbox.methods, {
	addClearBtn: function(jq, iconCls, fun){
		return jq.each(function(){
			var t = $(this);
			var opts = t.textbox('options');
			opts.icons = opts.icons || [];
			opts.icons.unshift({
				iconCls: iconCls,
				handler: function(e){
					$(e.data.target).textbox('clear').textbox('textbox').focus();
					$(this).css('visibility','hidden');
					if (fun) { fun(); }
				}
			});
			t.textbox();
			if (!t.textbox('getText')){
				t.textbox('getIcon',0).css('visibility','hidden');
			}
			t.textbox('textbox').bind('keyup', function(){
				var icon = t.textbox('getIcon',0);
				if ($(this).val()){
					icon.css('visibility','visible');
				} else {
					icon.css('visibility','hidden');
					if (fun) { fun(); }
				}
			});
		});
	}
});

/**
 * 扩展easyui表单的验证 datagrid 属性
 * 邮箱验证：<input type="text" validtype="email" required="true" missingMessage="不能为空" invalidMessage="邮箱格式不正确" />
 * 网址验证：<input type="text" validtype="url" invalidMessage="url格式不正确[http://www.example.com]" />
 * 长度验证：<input type="text" validtype="length[8,20]" invalidMessage="有效长度8-20" />
 * 手机验证：<input type="text" validtype="mobile"  />
 * 邮编验证：<input type="text" validtype="zipcode" />
 * 账号验证：<input type="text" validtype="account[8,20]" />
 * 汉子验证：<input type="text" validtype="CHS" />
 * 远程验证：<input type="text" validtype="remote['checkname.aspx','name']" invalidMessage="用户名已存在"/>
 */
$.extend($.fn.validatebox.defaults.rules, {
	/**
	 * 非零
	 */
	nonzero : {
		validator : function (value) {
            return value.replace('￥','').replace('$','') != 0;
        },
        message: '当前值不能为零'
	},
    /**
     * 值必须大于零
     */
    zero : {
        validator : function (value) {
            return value.replace('￥','').replace('$','') > 0;
        },
        message: '当前值必须大于零'
    },
    /**
     * 验证汉字
     */
    chc : {
        validator : function (value) {
            return /^[\u0391-\uFFE5]+$/.test(value);
        },
        message: '只能输入汉字'
    },
    /**
     * 移动手机号码验证
     */
    mobile : {
        /**
         * value 值为文本框中的值
         */
        validator : function (value) {
            var reg = /^1[3|4|5|6|7|8|9]\d{9}$/;
            return reg.test(value);
        },
        message : '输入手机号码格式不准确.'
    },
    /**
     * 国内邮编验证
     */
    zipcode : {
        validator : function (value) {
            var reg = /^[1-9]\d{5}$/;
            return reg.test(value);
        },
        message : '邮编必须是非0开始的6位数字.'
    },
    /**
     * 用户账号验证(只能包括 _ 数字 字母)
     */
    account : {
        /**
         * param 的值为 [] 中值
         */
        validator : function (value, param) {
            if (value.length < param[0] || value.length > param[1]) {
                $.fn.validatebox.defaults.rules.account.message = '用户名长度必须在' + param[0] + '至' + param[1] + '范围';
                return false;
            } else {
                if (!/^[\w]+$/.test(value)) {
                    $.fn.validatebox.defaults.rules.account.message = '用户名只能数字、字母、下划线组成.';
                    return false;
                } else {
                    return true;
                }
            }
        },
        message : ''
    },
	/**
     * 验证两次密码输入是否相等
     */
	equalTo: {
		validator : function(value, param) {
			return $(param[0]).val() == value;
		},
		message:'两次输入的密码不同。'
	}
});

/**
 * layout方法扩展  
 * @param {Object} jq  
 * @param {Object} region
 * 使用示例:
 * $('#wrap').layout('hide',region);
 * $('#wrap').layout('show',region);
 * $('#wrap').layout('hide',"all");
 * $('#wrap').layout('show',"all");
 */
$.extend($.fn.layout.methods, {
    /**
     * 面板是否存在和可见
     * @param {Object} jq
     * @param {Object} params
     */
    isVisible: function(jq, params) {
        var panels = $.data(jq[0], 'layout').panels;
        var pp = panels[params];
        if(!pp) {
            return false;
        }
        if(pp.length) {
            return pp.panel('panel').is(':visible');
        } else {
            return false;
        }
    },   
    /**  
     * 隐藏除某个region，center除外。  
     * @param {Object} jq  
     * @param {Object} params
     */  
    hide: function(jq, params) {   
        return jq.each(function() {   
            var opts = $.data(this, 'layout').options;
            var panels = $.data(this, 'layout').panels;
            if(!opts.regionState) {
                opts.regionState = {};
            }   
            var region = params;   
            function hide(dom,region,doResize) {
                var first = region.substring(0,1);
                var others = region.substring(1);
                var expand = 'expand' + first.toUpperCase() + others;
                if(panels[expand]) {
                    if($(dom).layout('isVisible', expand)) {
                        opts.regionState[region] = 1;
                        panels[expand].panel('close');
                    } else if($(dom).layout('isVisible', region)) {
                        opts.regionState[region] = 0;
                        panels[region].panel('close');
                    }
                } else {
                    panels[region].panel('close');
                }
                if(doResize){
                    $(dom).layout('resize');
                }
            };   
            if(region.toLowerCase() == 'all'){   
                hide(this,'east',false);   
                hide(this,'north',false);   
                hide(this,'west',false);   
                hide(this,'south',true);   
            }else{   
                hide(this,region,true);   
            }   
        });   
    },   
    /**  
     * 显示某个region，center除外。  
     * @param {Object} jq  
     * @param {Object} params  
     */  
    show: function(jq, params) {
        return jq.each(function() {
            var opts = $.data(this, 'layout').options;
            var panels = $.data(this, 'layout').panels;
            var region = params;
            function show(dom,region,doResize){   
                var first = region.substring(0,1);   
                var others = region.substring(1);   
                var expand = 'expand' + first.toUpperCase() + others;   
                if(panels[expand]) {   
                    if(!$(dom).layout('isVisible', expand)) {   
                        if(!$(dom).layout('isVisible', region)) {   
                            if(opts.regionState[region] == 1) {   
                                panels[expand].panel('open');   
                            } else {
                                panels[region].panel('open');
                            }
                        }   
                    }   
                } else {   
                    panels[region].panel('open');   
                }   
                if(doResize){   
                    $(dom).layout('resize');   
                }   
            };   
            if(region.toLowerCase() == 'all'){   
                show(this,'east',false);   
                show(this,'north',false);   
                show(this,'west',false);   
                show(this,'south',true);   
            }else{   
                show(this,region,true);   
            }   
        });   
    }   
});

/**
 * tabs 方法扩展  
 * @param {Object} jq  
 * @param {Object} params
 * 使用示例:
 * $('#tabs').tabs('addIframe',{title:'标题',fit:true,url:'地址'});
 */
$.extend($.fn.tabs.methods, {
	addIframe : function (jq, params) {
		return jq.each(function() {
			if (!params) return;
			var rid = 't' + Math.round(Math.random() * 1000000);
			params = params || {};
			params = {
				id : params.id || rid,
				title : params.text || params.title || '无标题',
				url : params.url || '',
				closable : params.closable || true
			};

			if (!$(this).tabs('exists',params.title)) {
				var _iframe = $('<iframe style="width:100%;height:100%;border:0px;overflow:hidden;"/>');
				_iframe.attr({
					src : params.url
				});
				$(this).tabs('add', {
					id : params.id,
					fit : true,
					style: {overflow:'hidden',padding:'4px'},
					content : _iframe,
					title : params.title,
					closable : params.closable
				});
			}
		});
	},
    getTabById : function(jq, id) {
        var tabs = $.data(jq[0], 'tabs').tabs;
        for(var i=0; i<tabs.length; i++){
            var tab = tabs[i];
            if (tab.panel('options').id == id){
                return tab;
            }
        }
        return null;
    },
    existsById : function(jq, id) {
        return $(jq[0]).tabs('getTabById',id) != null;
    },
    selectById : function(jq, id) {
        return jq.each(function() {
            var state = $.data(this, 'tabs');
            var opts = state.options;
            var tabs = state.tabs;
            var selectHis = state.selectHis;
            if (tabs.length == 0) {return;}
            var panel = $(this).tabs('getTabById', id);
            if (!panel) { return }
            var selected = $(this).tabs('getSelected');
            if (selected){
                if (panel[0] == selected[0]) { return; }
                $(this).tabs('unselect',$(this).tabs('getTabIndex', selected));
                if (!selected.panel('options').closed){return}
            }
            panel.panel('open');
            var title = panel.panel('options').title;
            selectHis.push(title);
            var tab = panel.panel('options').tab;
            tab.addClass('tabs-selected');
            var wrap = $(this).find('>div.tabs-header>div.tabs-wrap');
            var left = tab.position().left;
            var right = left + tab.outerWidth();
            if (left < 0 || right > wrap.width()) {
                var deltaX = left - (wrap.width()-tab.width()) / 2;
                $(this).tabs('scrollBy', deltaX);
            } else {
                $(this).tabs('scrollBy', 0);
            }
            $(this).tabs('resize');
            opts.onSelect.call(this, title, $(this).tabs('getTabIndex',panel));
        });
    }
});

/**
 * 指定位置显示$.messager.show
 * options $.messager.show的options
 * param = {left,top,right,bottom}
使用示例
 function showBySite(event){
    var element = document.elementFromPoint(event.x,event.y);//获取点击对象
    $.messager.showBySite({
        title:'My Title',
        msg:'Message.',
        showType:'show'
    },{
        top : $(element).position().top+$(element).height(),//将$.messager.show的top设置为点击对象之下
        left : $(element).position().left,//将$.messager.show的left设置为与点击对象对齐
        bottom : ""
    });
}
 */
$.extend($.messager, {
    showBySite : function(options,param) {
        var site = $.extend( {
            left : "",
            top : "",
            right : 0,
            bottom : -document.body.scrollTop
                    - document.documentElement.scrollTop
        }, param || {});
        var win = $("body > div .messager-body");
        if(win.length<=0)
            $.messager.show(options);
        win = $("body > div .messager-body");
        win.window("window").css( {
            left : site.left,
            top : site.top,
            right : site.right,
            zIndex : $.fn.window.defaults.zIndex++,
            bottom : site.bottom
        });
    }
});