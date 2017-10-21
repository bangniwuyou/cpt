/**
 * Created by PhpStorm.
 * User: 姜海强 <jhq0113@163.com>
 * Date: 2017/10/11
 * Time: 18:06
 */

/**
 *组件
 */
var Upload={
    /**
     * 事件列表
     */
    event:{
        //上传完毕事件
        uploaded:'uploaded',
        //删除完毕事件
        deleted:'deleted'
    },
    /**
     * 文件类型Map
     */
    fileTypeMap:{
        image:{
            viewTag:'<img />',
            isReader:true
        },
        video:{
            viewTag:'<video controls="controls"></video>',
            isReader:true
        },
        file:{
            viewTag:'<p style="width: 100%;height: 100%;"></p>',
            isReader:false
        }
    },
    /**
     * 获取文件扩展名
     * @param {string} fileName   文件名
     * @returns {string}
     */
    getExtension:function (fileName) {
        var tempArray = fileName.split('.');
        return tempArray[ tempArray.length - 1 ].toLowerCase();
    },
    /**
     * 扩展名背景类
     */
    extensionIconClass:{
        zip:'back-groud-zip',
        mp3:'back-groud-mp3',
        pdf:'back-groud-pdf',
        doc:"back-groud-doc",
        default:'back-groud-default'
    }
};

/**
 * 组件封装
 */
(function (upload) {
    /**
     * 默认配置
     * @type {
        {
            uploadUrl: string,
            fileType: string,
            allowExtension: [*],
            maxCount: number,
            maxUploadingCount: number,
            extraData: {},
            valueSeparator: string,
            autoLoadValue: boolean,
            allowUploadAll: boolean
        }
      }
     */
    var defaultUploadOptions={
        uploadUrl:'/up/upload',
        fileType:'image',
        allowExtension:[],
        maxCount:5,
        maxUploadingCount:3,
        extraData:{},
        valueSeparator:'|',
        autoLoadValue:true,
        allowUploadAll:true
    };

    /**
     *扩展
     */
    $.fn.extend({
        /**
         * 初始化
         * @param {object} options
         */
        bootstrapUpload:function (options) {

            if(this.length >1)
            {
                this.each(function () {
                    $(this).bootstrapUpload(options);
                });
                return;
            }

            options =$.extend({},defaultUploadOptions,options);

            options.extraData.fileType = options.fileType;

            this.setAttr('options',options);

            upload.load(this);
        },
        /**
         * getter
         * @param {string} key
         * @returns {*}
         */
        getAttr:function (key) {
            return this[0][ key ];
        },
        /**
         * setter
         * @param {string} key
         * @param {*}      value
         * @returns {*}
         */
        setAttr:function (key,value) {
            this[0][ key ] = value;
            return this[0][ key ];
        },
        /**
         * 取值
         */
        getValue:function () {
            return this.attr('data-value');
        }
    });

    /**
     *以选择器形式初始化插件
     * @param {string}   selector
     * @param {object}   options
     */
    upload.bootstrapUpload=function (selector,options) {
        $(selector).bootstrapUpload(options);
    };

    /**
     *加载
     * @param {jQuery} inputFileObject
     */
    upload.load=function (inputFileObject) {

        upload.decorateInputFile(inputFileObject);

        upload.loadView(inputFileObject);

        /**
         * 加载默认值
         */
        var items = inputFileObject.attr('data-value');
        if(items && items.length > 0){
            items = items.split(inputFileObject.getAttr('options').valueSeparator);
            upload.loadItems2View(items,inputFileObject);
        }
    };

    /**
     * 装饰input file
     * @param {jQuery} inputFileObject
     */
    upload.decorateInputFile=function (inputFileObject) {
        var options = inputFileObject.getAttr('options');

        /**
         * 多文件
         */
        if(options.maxCount > 1)
        {
            inputFileObject.attr('multiple','multiple');
        }

        /**
         * 过滤
         */
        switch (options.fileType)
        {
            case 'image':
                inputFileObject.attr('accept','image/*');
                break;
            case 'video':
                inputFileObject.attr('accept','video/*');
                break;
        }

        inputFileObject.hide();

        if(options.allowUploadAll)
        {
            var btnUploadAll =upload.createUploadAllBtn(inputFileObject);
            inputFileObject.after(btnUploadAll);
        }

        var btnSelect = upload.createSelectBtn(inputFileObject);
        inputFileObject.after(btnSelect);

        upload.addValueElement(inputFileObject);
    };

    /**
     * 添加值handler
     * @param {string} eventName
     * @param {object} params
     */
    upload.addValueHandler=function (eventName,params) {
        var oldValue  = $(this).val();
        var newValues = [];
        if(oldValue.length >0){
            newValues=oldValue.split(params.separator);
        }
        newValues.push(params.value);
        var newValue = newValues.join(params.separator);
        $(this).val(newValue);
        params.delegateObject.attr('data-value',newValue);
    };

    /**
     * 删除值handler
     * @param {string} eventName
     * @param {object} params
     */
    upload.delValueHandler=function (eventName,params) {
        var oldValue  = $(this).val();
        var newValues = [];
        if(oldValue.length >0){
            newValues=oldValue.split(params.separator);
        }

        for(var k=0;k<newValues.length;k++){
            if(newValues[k] == params.value){
                newValues.splice(k,1);
                break;
            }
        }
        var newValue = newValues.join(params.separator);
        $(this).val(newValue);
        params.delegateObject.attr('data-value',newValue);
    };

    /**
     * 添加值节点
     * @param {jQuery} inputFileObject
     */
    upload.addValueElement =function (inputFileObject) {
        if(inputFileObject.getAttr('options').autoLoadValue){
            var name = inputFileObject.attr('name');
            var id   = inputFileObject.attr('id');
            id = (id.length > 0) ? id + '_value' : '';
            if(name.length > 0){
                var hidden = $('<input type="hidden" '+((id.length > 0) ? 'id="'+id+'"' : '')+' name="'+name+'" />');

                hidden.on('addValue',upload.addValueHandler);

                hidden.on('delValue',upload.delValueHandler);

                inputFileObject.setAttr('valueEle',hidden);
                inputFileObject.after(hidden);
            }
        }
    };

    /**
     * 加载预览视图
     * @param {jQuery} inputFileObject
     */
    upload.loadView=function (inputFileObject) {
        var div = $('<div class="row bootstrap-upload-frame"></div>');
        div[0].addEventListener('drop',function (e) {
            e.stopPropagation();
            e.preventDefault();

            for (var k=0; k<e.dataTransfer.files.length; k++){
                upload.checkExtension(e.dataTransfer.files[k].name,inputFileObject,function (result) {
                    if(result){
                        upload.addItem2View(e.dataTransfer.files[k],inputFileObject);
                    }
                });
            }
        });

        div.append('<div class="drop-title">支持拖拽上传</div>');

        inputFileObject.after(div);

        inputFileObject.setAttr('view',div);
    };

    /**
     * 创建选择按钮
     * @param {jQuery} inputFileObject
     * @returns {*|jQuery|HTMLElement}
     */
    upload.createSelectBtn=function (inputFileObject) {
        var btnSelect=$('<p class="btn-warning btn bootstrap-upload-btn">选择</p>');
        btnSelect.on('click',function () {
            inputFileObject.click();
        });

        inputFileObject.on('change',function () {
            for (var k=0; k<this.files.length; k++){
                var self = this;
                upload.checkExtension(self.files[k].name,inputFileObject,function (result) {
                    if(result){
                        upload.addItem2View(self.files[k],inputFileObject);
                    }
                });
            }
        });

        return btnSelect
    };

    /**
     * 创建全部上传按钮
     * @param {jQuery}  inputFileObject
     * @returns {*|jQuery|HTMLElement}
     */
    upload.createUploadAllBtn=function (inputFileObject) {
        var btnUploadAll = $('<p class="btn-success btn bootstrap-upload-btn">全部上传</p>');
        btnUploadAll.on('click',function () {
            var self = this;
            if($(self).hasClass('uping')){
                return;
            }
            $(self).addClass('uping');


            var view = inputFileObject.getAttr('view');
            if(view.length < 1){
                $(self).removeClass('uping');
                return;
            }

            var items = inputFileObject.getAttr('view').find('.file-upload');
            if(items.length < 1){
                $(self).removeClass('uping');
                return;
            }

            clearInterval(timer);

            var maxUploadCount = inputFileObject.getAttr('options').maxUploadingCount;

            var timer = setInterval(function () {

                items = inputFileObject.getAttr('view').find('.file-upload');
                if(items.length < 1){

                    $(self).removeClass('uping');

                    clearInterval(timer);
                    return;
                }

                if(items.length == inputFileObject.getAttr('view').find('.up-all').length)
                {
                    $(self).removeClass('uping');

                    clearInterval(timer);
                    return;
                }

                if(inputFileObject.getAttr('view').find('.uploading').length < maxUploadCount){
                    for (var k=0;k<items.length;k++){
                        var uploadBtn = $(items[ k ]);
                        if( !(uploadBtn.hasClass('uploading') ) && !(uploadBtn.hasClass('up-all')) ){
                            uploadBtn.addClass('up-all');
                            uploadBtn.click();
                            return;
                        }
                    }
                }

            },100);
        });
        return btnUploadAll;
    };

    /**
     * 校验扩展名是否合法
     * @param {string}      fileName
     * @param {jQuery}      inputFileObject
     * @param {function}    callback
     */
    upload.checkExtension=function (fileName,inputFileObject,callback) {
        var accessExtension = inputFileObject.getAttr('options').allowExtension;
        //空数组表示不限制
        var access = true;
        if(accessExtension.length > 0){
            access = false;
            var ext = upload.getExtension(fileName);
            access  = (accessExtension.indexOf(ext) > -1);
            if(!access){
                console.log('只允许上传['+accessExtension.join(',')+']扩展名的文件！');
            }
        }

        callback(access);
    };

    /**
     * 创建item
     * @param {string} src
     * @param {jQuery} inputFileObject
     * @param {bool}   isUploaded
     * @returns {*|jQuery|HTMLElement}
     */
    upload.createItem=function (src,inputFileObject,isUploaded) {
        isUploaded = isUploaded ? isUploaded : false;
        var item=$('<div class="bootstrap-upload-item col-sm-6 col-md-3"></div>');

        var options = inputFileObject.getAttr('options');
        var viewItem = $(upload.fileTypeMap[options.fileType].viewTag);
        if(options.fileType == 'file'){
            var ext = upload.getExtension(src);
            var iconClass = upload.extensionIconClass[ ext ] ? upload.extensionIconClass[ ext ] : upload.extensionIconClass.default;
            viewItem.append('<p class="'+iconClass+'"></p>');
            viewItem.append('<p class="upload-file">'+src+'</p>');
        }else{
            viewItem.attr('src',src);
        }

        if(isUploaded){
            viewItem.addClass('uploaded');
        }

        var content = $('<div class="thumbnail"><div class="caption"></div></div>');
        var viewContainer =$('<div class="bootstrap-upload-view-item"></div>');

        viewContainer.append(viewItem);
        content.prepend(viewContainer);

        content.children('.caption').append(upload.createBtnGroup(inputFileObject,isUploaded));

        content.children('.caption').before('<div class="progress" style="margin: 5px 0px;"><div class="progress-bar progress-success"></div></div><div class="process-show" style="width: 100%;margin: 2px 0px;height: 20px;"></div>');


        item.append(content);

        return item;
    };

    /**
     * 添加item到预览框
     * @param {object} fileInfo
     * @param {jQuery} inputFileObject
     */
    upload.addItem2View=function (fileInfo,inputFileObject) {
        inputFileObject.getAttr('view').find('.drop-title').hide();

        if(upload.fileTypeMap[inputFileObject.getAttr('options').fileType].isReader)
        {
            upload.read(fileInfo,function (result) {
                var item = upload.createItem(result,inputFileObject);
                item.setAttr('file',fileInfo);
                inputFileObject.getAttr('view').append(item);
            });
        }
        else{
            var item = upload.createItem(fileInfo.name,inputFileObject);
            item.setAttr('file',fileInfo);
            inputFileObject.getAttr('view').append(item);
        }
    };

    /**
     * 加载项目到预览框
     * @param items
     * @param inputFileObject
     */
    upload.loadItems2View = function(items,inputFileObject){
        var options = inputFileObject.getAttr('options');
        if(items.length > 0){
            inputFileObject.getAttr('view').find('.drop-title').hide();
            for (var k=0; k< items.length;k++){
                var item = upload.createItem(items[k],inputFileObject,true);
                inputFileObject.getAttr('view').append(item);
                if(options.autoLoadValue){
                    inputFileObject.getAttr('valueEle').trigger('addValue',{value:items[k],separator:options.valueSeparator,delegateObject:inputFileObject});
                }
            }
        }
    };

    /**
     * 创建操作按钮组
     * @param {jQuery} inputFileObject
     * @param {bool}   isUploaded
     * @returns {*|jQuery|HTMLElement}
     */
    upload.createBtnGroup=function (inputFileObject,isUploaded) {
        var p =$('<p></p>');
        p.append(upload.createDeleteBtn(inputFileObject));

        if(!isUploaded){
            p.append(upload.createUploadBtn(inputFileObject));
        }

        return p;
    };

    /**
     * 递归开始值
     * @type {number}
     */
    var recursionStart = 0;

    /**
     * 递归结束值
     * @type {number}
     */
    var recursionEnd   = 50;

    /**
     * 递归寻找上传item
     * @param {jQuery} element
     * @param {number} start
     */
    upload.findItem=function (element,start) {
        start = start ? start : 0;
        var parent = element.parent();
        if(parent.hasClass('bootstrap-upload-item'))
        {
            return parent;
        }

        recursionStart = start;
        recursionStart++;
        if(recursionStart >= recursionEnd){
            return;
        }

        return upload.findItem(parent,recursionStart);
    };

    /**
     * 创建删除按钮
     * @param {jQuery} inputFileObject
     * @returns {*|jQuery|HTMLElement}
     */
    upload.createDeleteBtn=function (inputFileObject) {
        var btn = $('<a href="javascript:void(0);" class="bootstrap-upload-btn btn btn-danger file-delete" role="button">删除</a>');
        btn.on('click',function () {
            if(confirm('您真的要删除该文件吗？')){
                var item =upload.findItem($(this));
                var info ={
                    file:item.getAttr('file'),
                    item:item,
                    clickElement:this
                };

                upload.delete(inputFileObject,info,function () {
                    if($._data(inputFileObject[0],'events')[ upload.event.deleted ])
                    {
                        inputFileObject.trigger(upload.event.deleted,info);
                    }
                });
            }
        });
        return btn;
    };

    /**
     * 创建上传按钮
     * @param {jQuery} inputFileObject
     * @returns {*|jQuery|HTMLElement}
     */
    upload.createUploadBtn=function (inputFileObject) {
        var btn = $('<a href="javascript:void(0);" class="bootstrap-upload-btn btn btn-warning file-upload" role="button">上传</a>');
        btn.on('click',function () {
            var item=upload.findItem($(this));
            var info ={
                file:item.getAttr('file'),
                item:item,
                clickElement:this
            };

            upload.upload(inputFileObject,info,function (response) {
                info.response = response;
                if($._data(inputFileObject[0],'events')[ upload.event.deleted ])
                {
                    inputFileObject.trigger(upload.event.uploaded,info);
                }
            });
        });
        return btn;
    };

    /**
     * 读客户端文件资源
     * @param {object}      fileInfo
     * @param {function}    callback
     */
    upload.read=function (fileInfo,callback) {
        var reader = new FileReader();
        reader.readAsDataURL(fileInfo);
        reader.onload=function () {
            callback(reader.result);
        };
    };

    /**
     * 上传
     * @param {jQuery}      inputFileObject
     * @param {object}      info
     * @param {function}    callback
     */
    upload.upload=function (inputFileObject,info,callback) {
        var formData = new FormData();
        formData.append('bootstrap-file',info.file);

        var options=inputFileObject.getAttr('options');
        if(!$.isEmptyObject(options.extraData))
        {
            $.each(options.extraData,function (key,value) {
                formData.append(key,value);
            });
        }

        $(info.clickElement).addClass('uploading');
        $.ajax({
            url:options.uploadUrl,
            type:'post',
            dataType:'json',
            data:formData,
            processData : false,
            contentType : false ,
            xhr: function() {
                var xhr = $.ajaxSettings.xhr();
                xhr.upload.addEventListener('progress', function (e) {
                    if (e.lengthComputable) {
                        var percent = e.loaded/e.total*100;
                        var process = info.item.find('.progress-bar');
                        process.css('width',percent.toFixed() + "%");
                        info.item.find('.process-show').html('<span style="color: #0000aa;">'+parseInt(e.loaded/1024) + "/" + parseInt(e.total/1024)+" KB,"+percent.toFixed() + "%</span>");
                    }
                }, false);
                return xhr;
            },
            success:function (response) {
                $(info.clickElement).removeClass('uploading');
                if(response.status == '200'){
                    if(inputFileObject.getAttr('options').autoLoadValue){
                        inputFileObject.getAttr('valueEle').trigger('addValue',{value:response.data,separator:inputFileObject.getAttr('options').valueSeparator,delegateObject:inputFileObject});
                    }
                    upload.showUploadedFile(info,options,response.data);
                }else{
                    alert(response.data);
                }
                callback(response);
            },
            error:function (error) {
                $(info.clickElement).removeClass('uploading');
                alert('上传失败');
            }
        });
    };

    /**
     * 显示上传完的文件
     * @param {object}  info
     * @param {object}  options
     * @param {string}  src
     */
    upload.showUploadedFile=function (info,options,src) {
        switch (options.fileType)
        {
            case 'image':
                info.item.find('.bootstrap-upload-view-item img').addClass('uploaded').attr('src',src);
                break;
            case 'video':
                info.item.find('.bootstrap-upload-view-item video').addClass('uploaded').attr('src',src);
                break;
            default:
                info.item.find('.bootstrap-upload-view-item .upload-file').addClass('uploaded').html(src);
        }
        info.item.find('.process-show').html('<span style="color: #00aa00;">上传成功</span>');
        $(info.clickElement).remove();
    };

    /**
     * 删除
     * @param {jQuery}      inputFileObject
     * @param {object}      info
     * @param {function}    callback
     */
    upload.delete=function (inputFileObject,info,callback) {
        info.item.remove();
        if(inputFileObject.getAttr('view').find('.bootstrap-upload-item').length < 1 ){
            inputFileObject.getAttr('view').find('.drop-title').show();
        }

        var options = inputFileObject.getAttr('options');
        if(options.autoLoadValue){
            var uploaded = info.item.find('.bootstrap-upload-view-item .uploaded');
            if(uploaded.length > 0){
                var value = '';
                if(options.fileType == 'file'){
                    value = uploaded.html();
                }
                else {
                    value = uploaded.attr('src');
                }
                if(value.length > 0){
                    inputFileObject.getAttr('valueEle').trigger('delValue',{value:value,separator:options.valueSeparator,delegateObject:inputFileObject});
                }
            }
        }

        callback();
    };

}(Upload));

/**
 * 阻止浏览器拖拽默认行为
 */
$(document).on({
    dragleave:function(e){
        e.preventDefault();
    },
    drop:function(e){
        e.preventDefault();
    },
    dragenter:function(e){
        e.preventDefault();
    },
    dragover:function(e){
        e.preventDefault();
    }
});
