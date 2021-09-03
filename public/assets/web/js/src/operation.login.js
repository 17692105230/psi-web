/**
 * 银行信息函数
 */
(function($) {
    var history = {
		_id : '',
        _lockVersion : 0
	}

    /**
     * 主要的
     */
    $.h.login = {
		onLogin : function() {
			var params = {};
			$('#login_form').find('input,select').each(function() {
				if (this.name && $.trim(this.value)) {
					params[this.name] = this.value;
				}
			});
			if ($.isEmptyObject(params)) return;
			$.ajax({
				url: '/web/passport/loginCheck',
				type:'post',
				cache:false,
				dataType:'json',
				data: {data:JSON.stringify(params)},
				beforeSend: function(xhr) {
					$.messager.progress({title:'请稍等...',msg:'正在提交数据...'});
				},
				success: function(data) {
					//data = $.parseJSON(data);
					if (data.errcode == 0) {
						$(location).attr('href', data.data.url);
					} else {
                        $("#captcha").attr("src","/captcha?" + Math.random())
						$.messager.alert('提示', data.errmsg);
					}
				},
				complete: function() {
					$.messager.progress('close');
				}
			});
		}
    }
})(jQuery);