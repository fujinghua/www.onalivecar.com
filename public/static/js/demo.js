
form.set({ELEM:'#default-form'});

/**
 * 自定义验证规则
 * exist 使用方式 lay-verify="exist" lay-group="XXX" lay-error="XXX"
 * checked 使用方式 lay-verify="checked" lay-group="XXX" lay-error="XXX"
 */
form.verify({
    exist: function (value, item) {
        var $this = $(item);
        if (!$this.checked || (!$this.is('input[type="checkbox"]') && $.trim(value) === '')){
            var group = $this.attr('lay-group');
            var message = $this.attr('lay-error') || '至少选择一个 '+$this.attr('name');
            var pass = false;
            //检查checkbox radio类型
            var checked = $('input[lay-group="'+group+'"]:checked');
            if (checked.length > 0){
                pass = true;
            }else {
                //检查其他类型
                $('input[lay-group="'+group+'"][type!="checkbox"][type!="radio"]').each(function () {
                    if($.trim($this.val()) !== ''){
                        pass = true;
                    }
                });
            }
            if (!pass){
                return message;
            }
        }
    },
    checked: function (value, item) {
        var $this = $(item);
        if (!$this.checked){
            var group = $this.attr('lay-group');
            var message = $this.attr('lay-error') || '至少选择一个 '+$this.attr('name');
            var checked = $('input[lay-group="'+group+'"]:checked');
            if (checked.length <= 0){
                return message;
            }
        }
    }
});

//监听提交
form.on('submit(submit)', function (data) {
    var param = data.field || {},$form = $(data.form),url = $form.attr('action'),index;
    if (url === undefined || url === ''){
        url = $config.url;
    }

    $.ajax({
        url:url,
        type:'POST',
        data:param,
        beforeSend:function () {
            index = top.layer.load(1, {shade:0.1});
        },
        success:function (data) {
            top.layer.close(index);
            if (data.info !== undefined){
                top.layer.msg(data.info);
                if (data.status === 1){
                    top.window.isReload =  true;
                    top.layer.close(top.layer.getFrameIndex(window.name));
                }
            }
        },
        error:function (data) {
            top.layer.close(index);
            top.layer.msg('Most people can not see this message, please share with us! <br> 【God is a girl, please treat her as a lover】');
        }
    });
});