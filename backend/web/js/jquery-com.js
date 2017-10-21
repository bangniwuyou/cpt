/**
 * Created by 姜海强 on 2016/2/3.houtai
 */
/**
 * 1.日期时间插件
 *$.layoutDatePicker('#family-membership_ceiling');
 */

/**
 * 数组类扩展
 */
Array.prototype.removeByValue=function(val,removeAll){
    for(var k in this){
        if(this[k]==val){
            this.splice(k,1);
            if(!removeAll)
            {
                return this;
            }
            this.removeByValue(val,removeAll);
        }
    }
    return this;
};

/**
 * 校验必填字段
 * @param {object} objJquery   jquery对象
 * @param {string} msg         字段名称
 */
function checkRequire(objJquery,msg)
{
    var value=objJquery.val();
    if(value.length<1)
    {
        $('.help-block').empty();
        objJquery.siblings('.help-block').html(msg+'必填');
        objJquery.focus();
        value=false;
    }
    return value;
}

//项目Jquery扩展
(function(c) {
    c.loginAfter = null;
    //操作成功
    c.SUCCESS = '200';
    //没有权限
    c.NOT_ALLOW = '403';

    //是否正在提交
    c.isRequest = {};

    /**
     * 公共的Ajax调用
     * @param data     ajax参数对象
     */
    c.comAjax = function (data) {
        if (c.isRequest[data.url]) {
            return;
        }
        c.isRequest[data.url] = true;
        var params = {};
        params.url = data.url;
        params.type = (typeof data.type == 'undefined') ? 'post' : data.type;
        params.dataType = (typeof data.dataType == 'undefined') ? 'json' : data.dataType;
        params.data = (typeof data.data == 'undefined') ? {} : data.data;
        var param = $("head meta[name='csrf-param']");
        var token = $("head meta[name='csrf-token']");
        if (param.length > 0 && token.length > 0 && params.type == 'post') {
            params.data[param.attr('content')] = token.attr('content');
        }

        /* if(!$.isEmptyObject(params.data))
         {
         params.data=$.param(params.data);
         }*/

        //回调函数
        params.success = function (ret) {
            c.isRequest[data.url] = false;
            if (ret.status == c.NOT_ALLOW) {
                alert('没有权限');
            }
            else {
                data.success(ret);
            }
        };

        params.error = function () {
            c.isRequest[data.url] = false;
            if (typeof data.error == 'function') {
                data.error();
            }
        };

        $.ajax(params);
    };

    //--------------------------------------------时间相关-------------------------------------
    c.TIMETYPE_MINUTE = 1;
    c.TIMETYPE_HOUR = 2;

    /**
     * 日期变为Unix时间戳
     * @param {String|null} dateStr       日期字符串
     */
    c.dateToUnix = function (dateStr) {
        if (!dateStr) {
            return parseInt(new Date().getTime() / 1000);
        }
        return parseInt(new Date(Date.parse(date.replace(/-/g, "/"))).getTime() / 1000);
    };

    /**
     * 小于10补前面补0
     * @param {int} value
     */
    c.zeroFix = function (value) {
        return (parseInt(value) < 10) ? '0' + value.toString() : value.toString();
    };

    /**
     * 秒->分:秒 格式,如：66->01:06
     * @param {int} second
     */
    c.secondToMinute = function (second) {
        var theTime = parseInt(value);
        var theTime1 = 0;
        if (theTime > 60) {
            theTime1 = parseInt(theTime / 60);
            theTime = parseInt(theTime % 60);
        }
        var result = c.zeroFix(theTime);
        return (theTime1 > 0) ? c.zeroFix(theTime1) + ":" + result : "00:" + result;
    };

    /**
     * 秒->时:分:秒 格式
     * @param {int} second
     */
    c.secondToHour = function (second) {
        var theTime = parseInt(value);
        var theTime1 = 0;
        var theTime2 = 0;
        if (theTime > 60) {
            theTime1 = parseInt(theTime / 60);
            theTime = parseInt(theTime % 60);
            if (theTime1 > 60) {
                theTime2 = parseInt(theTime1 / 60);
                theTime1 = parseInt(theTime1 % 60);
            }
        }
        var result = c.zeroFix(theTime);
        result = (theTime1 > 0) ? c.zeroFix(theTime1) + ":" + result : "00:" + result;
        return (theTime2 > 0) ? c.zeroFix(theTime2) + ":" + result : "00:" + result;
    };
    
    c.alert=function (content,title) {
        title = title ? title : '操作提示';
        $('#commonModal_title').html(title);
        $('#commonModal_body').html(content);
        $('#commonModal_ok').hide();
        $('#commonModal').modal('show');
    };
    
    c.comfirm=function (content,callback,title) {
        title = title ? title : '操作提示';
        $('#commonModal_ok').show();
        $('#commonModal_title').html(title);
        $('#commonModal_body').html(content);
        $('#commonModal').modal('show');
        
        $('#commonModal_ok').on('click',function () {
            callback(true);
        });
        
        $('#commonModal_cancel').on('click',function () {
            callback(false);
        });
    };
}(jQuery));

//每个页面对象
var Cell={};

$(document).ready(function(){
    //页面初始化
    if(typeof Cell.init=='function')
    {
        Cell.init();
    }

    $.imgUpUrl='/up/img';
    $.fileUpUrl='/up/file';
    $.videoUpUrl='/up/video';
    $.voiceUpUrl='/up/voice';

    var upElements=$('.up-btn');
    if(upElements.length >0)
    {
        /**
         * 根据类型选择上传地址
         * @param type
         * @returns {*}
         */
        function getUrlByType(type) {
            switch (type){
                case 'img':
                    return $.imgUpUrl;
                case 'file':
                    return $.fileUpUrl;
                case 'video':
                    return $.videoUpUrl;
                case 'voice':
                    return $.voiceUpUrl;
            }
        }

        /**
         * 处理成功
         * @param self
         * @param valueElement
         * @param result
         * @param type
         */
        function dealSuccess(self,valueElement,result,type) {
            valueElement.val(result.data);
            switch (type){
                case 'img':
                    self.parent().siblings('.img-preview').find('img').attr('src',window.staticUrl+result.data);
                    break;
            }
        }

        /**
         * 上传状态Key
         * @type {{}}
         */
        var upStatusKey={};

        $('.up-btn').on('click',function () {
            var self=$(this);

            //文件节点
            var fileNodeId=$(this).attr('file-node-id');
            var valueNodeId=$(this).attr('value-node-id');
            var type=$(this).attr('type');

            if(upStatusKey[valueNodeId])
            {
                return;
            }

            //参数名
            var name=$('#'+fileNodeId).attr('name');
            if(name.length <1)
            {
                name = fileNodeId;
            }

            var valueElement=$('#'+valueNodeId);
            if(name.length >0 && valueElement.length > 0 && type.length >0)
            {
                upStatusKey[valueNodeId]=true;

                self.html('正在努力上传中...').attr('class','up-btn btn btn-warning');

                $.ajaxFileUpload({
                    url:getUrlByType(type),
                    secureuri: false,
                    data:{name:name,isAjax:'1'},
                    fileElementId: fileNodeId,
                    dataType: 'json',
                    success: function (result){

                        upStatusKey[valueNodeId]=false;
                        if(result.status == '200'){
                            self.html('上传成功').attr('class','up-btn btn btn-success');

                            dealSuccess(self,valueElement,result,type);
                        }else{
                            self.html(result.data);
                        }

                        setTimeout(function () {
                            self.html('重新上传');
                        },3000);
                    },
                    error:function (error) {

                        upStatusKey[valueNodeId]=false;

                        self.html('上传失败').attr('class','up-btn btn btn-danger');
                        setTimeout(function () {
                           self.html('重新上传');
                        },1000);
                    }
                });
            }
        });
    }


});
