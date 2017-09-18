// 表单中收货地址临时变量
var formsArea,
    vm = {
        formsCustomerAddress: {
            name: '',
            email: '',
            mobile: '',
            area: '北京',
            address: '',
            zip: '',
            remark: ''
        }
    };

$(function() {

    // 表单地区选择
    formsArea = function() {
        var formArea = $(".form-area");
        if (formArea.length) {
            head.load([StaticUrl + "editor/js/jquery.cityselect.js"], function() {
                formArea.each(function() {
                    var _this = $(this),
                        _areaInput = _this.find(".form-area-val"),
                        _area = _areaInput.val().split(","),
                        _selectObj = _this.find("select");
                    $(this).citySelect({
                        prov: _area[0] || null,
                        city: _area[1] || null,
                        dist: _area[2] || null,
                        nodata: "none",
                        url: StaticUrl + "editor/js/city.js"
                    });

                    _selectObj.on("change", function() {
                        setTimeout(function() {
                            _areaInput.val(_selectObj.map(function() {
                                return $(this).val();
                            }).get().join(',')).trigger('change');
                        }, 100);
                    });
                });
            });
        }
    }
    formsArea();

    // 时间选择
    $(".timepicker").each(function() {
        var obj = $(this);
        if (obj.attr('type') != 'text') {
            return
        }
        var language = obj.data("language");
        var futureOnly = obj.data("futureonly");
        var showTime = obj.data("showtime");

        config = {
            locale: language,
            futureOnly: futureOnly,
            dateOnly: showTime,
            autodateOnStart: false
        }

        obj.appendDtpicker(config);
    });


    // 数量类型
    $(".forms_number").each(function() {
        var obj = $(this);
        var input = obj.find('input');
        var total = obj.find('.number_total');
        var min = parseInt(input.attr('min'));
        var max = parseInt(input.attr('max'));
        var unit = input.data('unit');
        var price = parseInt(input.data('price'));

        // 计算总价
        function totalPrice(num) {
            if (price) {
                total.html(num * price)
            }
        }

        // 减 
        obj.on('click', '.number_minus', function(e) {
            var num = parseInt(input.val() ? input.val() : 0);
            if (min && num == min) {
                num = 0;
            } else {
                num = num - 1;
            }
            if (num < 0) {
                num = 0;
            }
            input.val(num);
            totalPrice(num);
        });
        // 加
        obj.on('click', '.number_plus', function(e) {
            var num = parseInt(input.val() ? input.val() : 0);
            if (min && num == 0) {
                num = min;
            } else {
                num = num + 1;
            }

            if (max) {
                if (num > max) {
                    num = max;
                    alert(maxNumberError + max + unit);
                }
            }
            input.val(num);
            totalPrice(num);
        });

        // 输入框的变化
        input.change(function() {
            var num = parseInt(input.val() ? input.val() : 0);
            if (max) {
                if (num > max) {
                    num = max;
                    alert(maxNumberError + max + unit);
                }
            }

            if (min) {
                if (num < min) {
                    num = 0;
                    alert(minNumberError + min + unit);
                }
            }

            if (num < 0) {
                num = 0;
            }
            input.val(num);
            totalPrice(num);
        });

    });



    // 购买商品
    $('.forms_product').each(function() {
        var obj = $(this),
            input = obj.find("input[type='number']"),
            numberMinusObj = obj.find('.number_minus'),
            numberPlusObj = obj.find('.number_plus');

        // 减少数量
        numberMinusObj.on('click', function() {
            var num = input.val();
            num--;
            if (num < 0) {
                num = 0;
            }
            input.val(num);
        });

        // 增加数量
        numberPlusObj.on('click', function() {
            var num = input.val(),
                max = parseInt(input.data('max_num'));
            num++;
            if (num > max) {
                num = max;
            }
            input.val(num);
        });

    });


    // 文件上传　删除按钮点击事件
    $('.upload').on('click', '.icon-del', function(e) {
        var obj = $(this);
        var id = obj.data('id');

        // 清空已上传的文件
        $('#form_id_' + id).val('');
        // 上传按钮上的文案
        $('#form_id_file_name_' + id).html(uploadFileMaxTip);
        // 打开上传按钮
        $("#form_id_file_" + id).show();
    })

    // 监听文件上传按钮点击事件
    $(document).on("change", ".forms_file_upload", function(e) {
        var obj = $(this);

        // 判断用户是否选择文件
        if (obj[0].files.length == 0) {
            alert(selectFile);
            return;
        }

        var id = obj.data('id');
        var params = {};
        // 上传过程提示
        var tip = $(".forms_file_upload_" + id + "_tip");
        var upload_server = 'http://upload.qiniu.com/';
        // 上传按钮文案
        var name = $("#form_id_file_name_" + id);

        var ie = false;
        for (var i in isIe) {
            if (isIe[i] == true) {
                ie = true;
            }
        }

        if (ie) {
            params.needReturn = 1;
        }

        $.post("/upload/policy", params, function(token) {

            // 当为ie 10以下的浏览器时
            if (ie) { // iframe的方式上传文件
                // 创建一个iframe
                var iframe = document.createElement("iframe");
                iframe.src = '';
                iframe.name = 'uploading' + (new Date().getTime());
                iframe.width = "0px";
                iframe.height = "0px";
                $(document.body).append(iframe);
                // 创建一个表单
                var form = document.createElement('form');
                form.action = upload_server;
                form.method = 'post';
                form.enctype = 'multipart/form-data';
                form.target = iframe.name;

                // 向表单中添加新的元素
                function createFormElement(form, name, value, type) {
                    if (!type) {
                        type = "hidden"
                    }
                    var tmp = document.createElement('input');
                    tmp.type = type;
                    tmp.name = name;
                    if (type == "file") {
                        tmp.files = value;
                    } else {
                        tmp.value = value;
                    }
                    form.appendChild(tmp);
                }

                // 添加token
                createFormElement(form, "token", token.token);
                // 添加 key
                createFormElement(form, "key", token.key + "/" + obj[0].files[0].name);
                // 添加要上传的文件
                createFormElement(form, "file", obj[0].files, "file");


                // 开始监听上传成功后的返回事件;
                $(document).one('uploadReturn', function(event, url) {
                        if (url == "error") {
                            alert(uploadErrMSG);
                            // 清空现在的上传框
                            obj.show();

                        } else {
                            $("#form_id_" + id).val(url);
                            name.html(uploadFileUploadFinish);
                        }
                        obj.after(obj.clone().val(''));
                        obj.remove();
                        tip.hide();
                        // 清空文件上传的痕迹
                        $(iframe).remove()
                        $(form).remove()
                    })
                    // 显示上传提示
                obj.hide();
                tip.show().html(uploadFileUploading);
                // 提交表单
                form.submit();
            } else { // ajax上传方式

                if (obj[0].files[0].size / 1024 / 1024 > 10) {
                    alert(uploadFileMaxTip);
                    return;
                }
                var progress = document.createElement("progress");
                progress.max = 100;
                progress.value = 0;
                var speed = document.createElement("span");

                tip.html(progress).show();
                tip.append(speed);
                var speedObj = $(speed);

                var xhr = new XMLHttpRequest();
                xhr.open("post", upload_server, true);
                var formData = new FormData();
                formData.append('token', token.token);
                formData.append('key', token.key + "/" + obj[0].files[0].name);
                formData.append('file', obj[0].files[0]);

                var taking, startDate;
                // 监听发送的情况
                xhr.upload.addEventListener('progress', function(evt) {
                    if (evt.lengthComputable) {
                        var nowDate = new Date().getTime();
                        taking = nowDate - startDate;
                        var x = (evt.loaded) / 1024;
                        var y = taking / 1000;
                        var uploadSpeed = (x / y);
                        var formatSpeed;
                        if (uploadSpeed > 1024) {
                            formatSpeed = (uploadSpeed / 1024).toFixed(2) + "Mb\/s";
                        } else {
                            formatSpeed = uploadSpeed.toFixed(2) + "Kb\/s";
                        }
                        var percentComplete = Math.round(evt.loaded * 100 / evt.total);
                        progress.value = percentComplete;
                        speedObj.html(formatSpeed);
                    }
                }, false)

                // 上传完成时
                xhr.onreadystatechange = function(response) {
                    if (xhr.readyState == 4 && xhr.status == 200 && xhr.responseText != "") { // 上传成功时
                        var blkRet = JSON.parse(xhr.responseText);

                        $("#form_id_" + id).val('http://forms-data.zhuzi.me/' + blkRet.key);
                        name.html(uploadFileUploadFinish)
                    } else if (xhr.status != 200) {
                        if (xhr.status == 413) {
                            alert(uploadFileMaxTip)
                        } else {
                            alert(uploadErrMSG);
                        }

                        obj.show();
                    }
                    obj.after(obj.clone().val(''));
                    obj.remove();
                    tip.hide();
                };

                startDate = new Date().getTime();
                // 开始发送数据
                xhr.send(formData);
                // 隐藏上传框
                obj.hide();
            }
        }, 'json')
    });
});


var validType = {
    tel: function(v) {
        return /^0\d{2,3}-?\d{7,8}$/.test(v);
    },
    phone: function(v) {
        return (/^1[3|4|5|7|8]\d{9}$/.test(v));
    },
    vtype: function(arr) {
        var textArr = { phone: '手机号', tel: '电话号码' },
            texts = "";
        $.each(arr, function(i, v) {
            texts += (i ? "或" : "") + textArr[v];
        });
        return texts
    }
}

// 验证码
$("body").on("click",".gt_popup_cross",function(){
    $(".form-button .btn").eq(0).show().siblings("input[type='submit']").hide();
})
vm.captchaFun = function(formsObj, fun) {
    var formId = formsObj.attr('data-id'),
        capObj = formsObj.find('.forms_captcha'),
        capid = capObj.attr('id');
    var handler = function(captchaObj) {
        captchaObj.bindOn(formsObj.find("input[type='submit']"));
        if (typeof forms_captcha_handler[capid] == 'undefined') {
            forms_captcha_handler[capid] = captchaObj
        }
        captchaObj.appendTo(capObj);
        // 验证成功时候
        captchaObj.onSuccess(function() {
            formsObj.attr("data-handlerVal", true);
            formsObj.find("#origin_btn_" + formId).show().siblings("input[type='submit']").hide();
        })

        captchaObj.onReady(function() {
            // 先隐藏一下SB的表单验证
            setTimeout(function() {
                formsObj.find("#origin_btn_" + formId).show().siblings("input[type='submit']").hide();
                if (typeof fun == 'function') fun();
            }, 300)
        })
    }
    $.post('/validate/serverSurvive?rand=' + Math.round(Math.random() * 100), function(data) {
        capObj.find("input[type='hidden'][name='geetest_server_status']").val(data.server_status);
        initGeetest({
            gt: data.gt,
            challenge: data.challenge,
            product: "popup",
            width: "301px",
            offline: !data.success,
            lang: capObj.data('lang'),
        }, handler);
        if (typeof fun == 'function') fun();
    }, 'json')
}




$.Tipmsg.r = null;


$("form.do-forms").each(function(i) {
    var formsObj = $(this),
        formId = formsObj.data("id"),
        capObj = formsObj.find('.forms_captcha'),
        validate = formsObj.data('v'),
        isProduct = formsObj.find(".form-product").length ? true : false,
        isPre = false;
    // 如果没有产品时候开启
    var handlerVal = false
        // 手机下关闭验证码
    if (!head.desktop) validate = false;
    // 打开验证码
    if (capObj.length > 0 && validate && !isProduct) {
        vm.captchaFun(formsObj);
    }

    var formAddress = [];

    var demo = formsObj.Validform({
        tiptype: function(msg) {
            layer.msg(msg);
        },
        tipSweep: true,
        ajaxPost: true,
        beforeSubmit: function(curform) {

            var obj = $(curform[0]);
            var id = obj.data('id');
            var data = obj.serializeArray();
            var submitButton = obj.find("input[type='submit']"),
                product = obj.find(".product"),
                product_type = 0; // 产品类型;
            // 如果有收货地址，进行数据合并
            if (formAddress.length) data = data.concat(formAddress);
            // 验证规则
            var isValid = true;
            obj.find("input[data-valid]").each(function() {
                var _this = $(this),
                    valid = _this.data("valid").substring(0, _this.data("valid").length - 1).split("|"),
                    _val = _this.val();
                isValid = false;
                $.each(valid, function(i, v) {
                    if (validType[v](_val)) {
                        isValid = true;
                        return;
                    }
                });
                if (!isValid) {
                    var text = "请输入正确的" + validType.vtype(valid) + "！";
                    layer.msg(text);
                }
                return isValid;
            });


            // 如果商品预定
            if (!isPre && product.length) {
                // 开始计算产品
                function count_product() {
                    var html = '<div class="panel panel-concise"><div class="panel-heading">订单列表</div><div class="panel-body"><table class="table table-bordered">\
                                <tr><th>名称</th><th width="100px" style="text-align: center;">数量</th><th width="100px" style="text-align: center;">单价</th></tr>',
                        totalAmount = 0, // 总金额
                        express_fee = 0, // 邮费
                        no_express = false; // 是否包邮

                    // 如果有重复电话 姓名 称呼
                    if (!vm.formsCustomerAddress.name) {
                        vm.formsCustomerAddress.name = formsObj.find("input[name='称呼']").val() || formsObj.find("input[name='姓名']").val() || "";
                        vm.formsCustomerAddress.mobile = formsObj.find("input[name='电话']").val() || formsObj.find("input[name='手机号']").val() || formsObj.find("input[name='手机']").val() || "";
                    }

                    

                    product.each(function() {
                        var obj = $(this);
                        var name = obj.data('title') ? obj.data('title') : obj.data('name'),
                            num = parseInt(obj.val()),
                            saler_remark = obj.data('remark'),
                            type = parseInt(obj.data('product_type')),
                            ispay = obj.data('ispay') ? 1 : 0,
                            price = parseInt(obj.data('price') * 100),
                            expressPrice = parseInt(obj.data('express_price') * 100),
                            amount = parseInt(num * price),
                            noExpress = obj.data('no_express');

                        if (num < 1) return;

                        product_type = type;
                        product_ispay = ispay;

                        // 是否需要填写邮寄地址
                        formsObj.attr('data-product-type', product_type);
                        // 是否弹出支付
                        formsObj.attr('data-ispay', product_ispay);

                        if (noExpress && !no_express) {
                            no_express = noExpress;
                        }

                        // 计算总价
                        totalAmount = totalAmount + amount;
                        // 计算运费
                        if (product_type == 1 && express_fee < expressPrice) {
                            express_fee = expressPrice;
                        }
                        html += '<tr>\
                        <td>' + name + '</td>\
                        <td align="center">' + num + '</td>\
                        <td align="center">￥' + (amount / 100).toFixed(2) + '</td>\
                        </tr>';
                    });

                    // 用户没有购买任何商品
                    if (!totalAmount) {
                        formsObj.removeAttr('data-product-type');
                        // productList.hide();
                        return
                    }

                    html += '</table>'

                    if (express_fee > 0 && !no_express) {
                        html += '<div style="text-align:right">' + window.postage + ':￥' + (express_fee / 100).toFixed(2) + '</div>';
                    } else {
                        if(product_type <= 1) html += '<div style="text-align:right">包邮</div>';
                        express_fee = 0;
                    }
                    html += '<div class="clearfix fb f18" style="text-align:right">￥<span class="f24 cf70">' + ((totalAmount + express_fee) / 100).toFixed(2) + '</span></div>';
                    html += '</div></div>';


                    var cptext = product_type <= 1 ? '<div class="form-group clearfix form-area">\
                                <label class="do-col-2 align-right control-label">地址 ' + (product_type == 1 ? '<span style="color:red">*</span>' : '') + '</label>\
                                <div class="do-col-10"><div class="select-area form-inline" style="margin-bottom: 15px;">\
                                    <select class="prov form-control"></select>\
                                    <select class="city form-control" disabled="disabled" style="display: none;"></select>\
                                    <select class="dist form-control" disabled="disabled" style="display: none;"></select>\
                                </div>\
                                <input type="hidden" class="form-area-val" name="forms_product_address[area]"  value="' + vm.formsCustomerAddress.area + '" />\
                                <input type="text" class="form-control" name="forms_product_address[address]" placeholder="请输入详细地址"  value="' + vm.formsCustomerAddress.address + '"></div>\
                            </div>\
                            <div class="form-group clearfix">\
                                <label class="do-col-2 align-right control-label">邮编</label>\
                                <div class="do-col-10"><input type="text" class="form-control" placeholder="请输入邮编" name="forms_product_address[zip]" value="' + vm.formsCustomerAddress.zip + '"></div>\
                            </div>\
                            <div class="form-group clearfix">\
                                <label class="do-col-2 align-right control-label">备注</label>\
                                <div class="do-col-10"><textarea name="forms_product_address[remark]" cols="40" rows="5" class="form-control" placeholder="请输入给商家的留言">' + vm.formsCustomerAddress.remark + '</textarea></div>\
                            </div>' : '';

                    var formTitle = product_type <= 1 ? "收货地址" : "联系信息";

                    // 地址填写表单
                    html += '<form><div class="panel panel-concise"><div class="panel-heading">'+formTitle+' <span class="f12 c999">(<span style="color:red">*</span>为必填项)</span></div><div class="panel-body">\
                            <div class="form-group clearfix">\
                                <label class="do-col-2 align-right control-label">姓名 <span style="color:red">*</span></label>\
                                <div class="do-col-10"><input required="true" type="text" class="form-control" name="forms_product_address[name]" placeholder="请输入姓名" value="' + vm.formsCustomerAddress.name + '"></div>\
                            </div>\
                            <div class="form-group clearfix">\
                                <label class="do-col-2 align-right control-label">邮箱</label>\
                                <div class="do-col-10"><input type="email" class="form-control" name="forms_product_address[email]" placeholder="请输入邮箱" value="' + vm.formsCustomerAddress.email + '"></div>\
                            </div>\
                            <div class="form-group clearfix">\
                                <label class="do-col-2 align-right control-label">手机号 <span style="color:red">*</span></label>\
                                <div class="do-col-10"><input required="true" type="text" class="form-control" name="forms_product_address[mobile]" placeholder="请输入手机号" value="' + vm.formsCustomerAddress.mobile + '"></div>\
                            </div>'
                            + cptext +
                        '</div></div></form>';
                    return html;

                }

                // if (!count_product()) {
                //     layer.msg("您还没有选择购买的商品");
                //     return false;
                // }

                vm.payConfirm = layer.confirm(count_product(), {
                    title: '订单确认',
                    area: '600px',
                    move: false,
                    closeBtn: 0,
                    zIndex: 999,
                    skin: 'layer-btnRight',
                    success: function(layero, index) {
                        setTimeout(function() {
                            formsArea();
                        }, 300);
                    },
                    btn: ['取消订单', '提交订单'],
                    yes: function(index, layero) {
                        // formsObj[0].reset();
                        if(layero.find("form").serializeJSON().forms_product_address) vm.formsCustomerAddress = layero.find("form").serializeJSON().forms_product_address;
                        layer.close(index);
                    },
                    btn2: function(index, layero) {

                        // 判断是否需要验证收货地址等信息；
                        var productType = parseInt(formsObj.data('product-type'));
                        
                        vm.formsCustomerAddress = layero.find("form").serializeJSON().forms_product_address;

                        // 判断是否需要验证收货地址等信息；
                        if (vm.formsCustomerAddress.name == "") {

                            layer.tips('请输入正确的收货人姓名', layero.find("input[name='forms_product_address[name]']"), {
                                tips: 1
                            });

                            return false;
                        }

                        // 如果邮箱为空，则给一个默认空邮箱
                        var isPay = parseInt(formsObj.data('ispay'));
                        if(!vm.formsCustomerAddress.email && isPay){
                            vm.formsCustomerAddress.email = "default@default.com";
                        }

                        if (!(/^(\w-*\.*)+@(\w-?)+(\.\w{2,})+$/.test(vm.formsCustomerAddress.email))) {

                            layer.tips('请输入正确的收货人邮箱地址', layero.find("input[name='forms_product_address[email]']"), {
                                tips: 1
                            });

                            return false;
                        }

                        if (vm.formsCustomerAddress.mobile == "" || !(/^1[3|4|5|7|8]\d{9}$/.test(vm.formsCustomerAddress.mobile))) {
                            layer.tips('请输入正确的收货人手机号', layero.find("input[name='forms_product_address[mobile]']"), {
                                tips: 1
                            });
                            return false;
                        }
                        if (productType == 1) {
                            if (vm.formsCustomerAddress.area == "" || vm.formsCustomerAddress.address == '') {
                                layer.tips('请输入正确的收货人地址', layero.find("input[name='forms_product_address[address]']"), {
                                    tips: 1
                                });
                                return false;
                            }
                        }


                        // 收货人数据
                        formAddress = layero.find("form").serializeArray();

                        // 判断是否需要邮箱
                        if(!layero.find("input[name='forms_product_address[email]']").val() && isPay){
                            $.each(formAddress,function(index, el) {
                                if(el.name=="forms_product_address[email]"){
                                    formAddress[index].value = "default@default.com";
                                    return false;
                                }
                            });
                        }

                        isPre = true;


                        // 正在提交地址
                        layero.find(".layui-layer-btn1").text('正在提交...');
                        if (validate) {
                            if (!formsObj.attr("data-handlerval")) {
                                vm.captchaFun(formsObj, function() {
                                    formsObj.find("#origin_btn_" + formId).hide().siblings("input[type='submit']").show().trigger("click");
                                });
                            } else {
                                formsObj.find("#origin_btn_" + formId).hide().siblings("input[type='submit']").show().trigger("click");
                            }
                        } else {
                            $(curform).find("input[type='submit']").trigger("click");
                        }

                        return false;
                        // layer.close(index);
                    }

                });
                return false;
            }

            if (!isValid) return false;
            // 激活验证码
            if (capObj.length && validate && !isProduct) {
                // 验证码
                if (!formsObj.attr("data-handlerval")) {
                    formsObj.find("#origin_btn_" + formId).hide().siblings("input[type='submit']").show().trigger("click");
                    return false;
                }
                formsObj.removeAttr("data-handlerVal");
            }

            submitButton.val(submiting).attr('disable', true);
            // 表单跳转
            var gourl = obj.data("gourl");

            // 数据提交
            $.ajax(obj.attr('action'), {
                cache: false,
                dataType: 'json',
                method: "post",
                data: formAddress.length ? obj.serializeArray().concat(formAddress) : obj.serializeArray(),
                error: function() {
                    layer.msg('信息提交失败，请重试。');
                    submitButton.val(submit).removeAttr('disable');
                    if (typeof forms_captcha_handler["forms_captcha_" + id] != "undefined") {
                        forms_captcha_handler["forms_captcha_" + id].refresh();
                    }
                },
                success: function(response) {
                    if (typeof response.errmsg != "undefined") {
                        layer.msg(response.errmsg);
                        return
                    } else {
                        if (gourl) {
                            window.location.href = gourl;
                        } else {
                            if (typeof response.order != "undefined") { // 当时一个创建过订单的表单提交时，需要提醒用户进行支付
                                // 如果是订单则不启用支付功能
                                var product_type = parseInt(formsObj.data('product-type')) || product_type;
                                var isPay = formsObj.data('ispay');
                                if (isPay) {
                                    layer.close(vm.payConfirm);
                                    layer.msg('订单已提交，感谢您的支持。', function() {
                                        window.location.reload(true);
                                    });
                                    return false;
                                }

                                vm.setPay = function(url, item) {
                                    $(item).addClass("cur").siblings().removeClass("cur");
                                    vm.payUrl = url;
                                    // window.open(url);
                                }

                                $.getJSON('/sites/' + response.order.siteid + '/onlinePayPlatform', function(data) {
                                    // 关闭loading
                                    layer.close(vm.payConfirm);
                                    var payItem = '';

                                    for (var i in data) {
                                        payItem += '<a href="javascript:;" onclick="vm.setPay(\'/sites/' + response.order.siteid + '/order/' + response.order.orderid + '/' + data[i].logo + '/gotopay\',this)" title="' + data[i].name + '"><img src="' + StaticUrl + 'editor/element/' + data[i].logo + '.png" /></a>'
                                    }

                                    var text = '<p class="bg-warning align-center mt20">您已下单成功,支付订单我们将尽快为您【发货或者联系您】</p>\
                                                <div class="do-list-dl do-orderPaypop">\
                                                    <dl class="dl-item">\
                                                        <dt>订单编号：</dt>\
                                                        <dd>' + response.order.orderid + '</dd>\
                                                    </dl>\
                                                    <dl class="dl-item">\
                                                        <dt>订单价格：</dt>\
                                                        <dd class="fb">￥<span class="f18 cf70">' + response.order.amount / 100 + '</span></dd>\
                                                    </dl>\
                                                    <dl class="dl-item">\
                                                        <dt>支付平台：</dt>\
                                                        <dd class="do-payTypeBtn">' + payItem + '</dd>\
                                                    </dl>\
                                                </div>';

                                    layer.confirm(text, {
                                        title: '支付订单',
                                        area: '520px',
                                        move: false,
                                        closeBtn: 0,
                                        skin: 'layer-btnRight',
                                        success: function(layero, index) {
                                            // 默认激活第一个支付
                                            layero.find(".do-payTypeBtn a").eq(0).trigger("click");
                                        },
                                        btn: ['取消支付', '立即支付'],
                                        yes: function(index, layero) {
                                            // 刷新验证码
                                            if (typeof forms_captcha_handler["forms_captcha_" + id] != "undefined") {
                                                forms_captcha_handler["forms_captcha_" + id].refresh();
                                            }

                                            formsObj[0].reset();
                                            isPre = false;
                                            layer.close(index);
                                        },
                                        btn2: function(index, layero) {
                                            // 必选一个支付类型
                                            if (!vm.payUrl) {
                                                layer.tips('请选择支付平台', layero.find(".do-payTypeBtn"), {
                                                    tips: 1
                                                });
                                                return false;
                                            }

                                            isPre = false;
                                            // 前往支付平台
                                            window.open(vm.payUrl);
                                            var text = product_type>=2 ? "联系您！" : "为您【发货或者联系您】";
                                            layer.confirm("订单是否支付成功？支付成功我们将尽快"+text, {
                                                title: '提示',
                                                move: false,
                                                closeBtn: 0,
                                                skin: 'layer-btnRight',
                                                btn: ['支付失败', '支付成功'],
                                                yes: function(index, layero) {
                                                    window.open(vm.payUrl);
                                                },
                                                btn2: function(index, layero) {
                                                    layer.close(index);
                                                    window.location.reload(true);
                                                }
                                            });
                                            return false;
                                        }

                                    });

                                })

                            } else {
                                // 这儿可以修改为显示提交成功的 div 
                                layer.msg(submitSuccessText);
                                // swal({ title: submitSuccess, text: submitSuccessText, type: "success", timer: 3000, showConfirmButton: false });
                                formsObj.removeAttr("data-handlerVal");
                            }
                            obj[0].reset();
                        }
                    }
                    submitButton.val(submit).removeAttr('disable');
                    if (typeof forms_captcha_handler["forms_captcha_" + id] != "undefined") {
                        forms_captcha_handler["forms_captcha_" + id].refresh();
                    }
                }
            })
            return false;
        },
        callback: function(data) {
        },
        datatype: {
            "tel": /^0\d{2,3}-?\d{7,8}$/,
            "phone": /^1[3|4|5|7|8]\d{9}$/,
            "product":/^(0|[1-9][0-9]*)$/
        }
    });


});
