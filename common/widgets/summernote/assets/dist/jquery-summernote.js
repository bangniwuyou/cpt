/**
 * Created by Jiang on 2017/10/8.
 */
/**
 *
 */
(function ($) {
    /**
     * 添加上传逻辑
     * @param {object}   option      Summernote初始化配置
     * @param {string}   uploadUrl   上传到服务器的url
     * @param {string}   id          编辑器id
     * @returns {object}
     */
    $.addUploadImgOption=function (option,uploadUrl,id) {
        option.callbacks = option.callbacks ? option.callbacks : {};
        option.callbacks.onImageUpload = option.callbacks.onImageUpload ? option.callbacks.onImageUpload : function (files) {
            var formData = new FormData();
            formData.append('image',files[0]);
            formData.append('name','image');
            $.ajax({
                type: "POST",
                url: uploadUrl,
                data: formData ,
                dataType:'json',
                processData : false,
                contentType : false ,
                success:function (result) {
                    if(result.status == '200'){
                        $('#'+id).summernote('insertImage', result.data, function ($image) {
                        });
                    }
                    else{
                        alert(result.data);
                    }
                },
                error:function (error) {
                    alert('上传失败');
                }
            });
        };

        return option;
    }


}(jQuery));

