let miuwpwpf_image_id = [];
(function ($) {
    if ($(".wpf-attributes").length) {
        var file_type = $(".wpf-attributes").attr("file-type") === "" ? "image/*" : $(".wpf-attributes").attr("file-type"),
            file_amount = ($(".wpf-attributes").attr("file-amount") === "" || $(".wpf-attributes").attr("file-amount") === 1) ? null : $(".wpf-attributes").attr("file-amount"),
            file_size = ($(".wpf-attributes").attr("file-size") === "") ? null : $(".wpf-attributes").attr("file-size"),
            allowMultiple;
        if (file_amount === null) {
            allowMultiple = false;
        } else {
            allowMultiple = true;
        }
        let allowed_files = [];
        if (file_type === "image/*") {
            allowed_files.push(file_type);
        } else {
            file_type = file_type.split(",");
            var max = file_type.length;
            for (var i = 0; max > i; i++) {
                allowed_files.push("image/" + file_type[i].trim());
            }
        }
        $.fn.filepond.registerPlugin(
            FilePondPluginFileValidateType,
            FilePondPluginImagePreview,
            FilePondPluginImageCrop,
            FilePondPluginImageEdit,
            FilePondPluginFileValidateSize
        );

        $(".jquery-file-upload").filepond();
        $('.jquery-file-upload').filepond({
            labelTapToUndo: "",
            labelTapToCancel: "",
            allowReorder: true,
            allowMultiple: allowMultiple,
            allowFileSizeValidation: true,
            allowImageCrop: true,
            imageCropAspectRatio: '1:1',
            allowFileTypeValidation: true,
            maxFiles: file_amount,
            acceptedFileTypes: allowed_files,
            setImageCropAspectRatio: 1,
            imageResizeTargetWidth: 200,
            imageInstantEdit: true,
            chunkUploads: false,
            maxParallelUploads: 1,
            credits: {label: "P5Cure", url: "https://p5cure.com"},
            fileValidateTypeDetectType: (source, type) => new Promise((resolve, reject) => {
                resolve(type)
            }),
        });

        $('.jquery-file-upload').on('FilePond:addfile', (e) => {

            miuwpwpf_image_id = [];
            miuwpwpf_image_id.push($(e.currentTarget).next());
            $(e.currentTarget).prev().hide("slow").removeClass("alert-danger").empty();

        });

        $(".jquery-file-upload").on('FilePond:warning', function (error) {
            $(error.currentTarget).prev().addClass("alert-danger").empty().append("You hav exceed the file limit, Max upload file limit is " + file_amount).show("slow");
        });

        FilePond.setOptions({
            server: {
                process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    const formData = new FormData();
                    formData.append(fieldName, file);
                    formData.append("action", "miuwp_wpf_ajax");
                    formData.append("method", "upload-file");
                    const request = new XMLHttpRequest();
                    request.open('POST', miuwp_wpf_objects.ajax_url);
                    request.upload.onprogress = (e) => {
                        progress(e.lengthComputable, e.loaded, e.total);
                    };

                    request.onload = () => {
                        if (request.status >= 200 && request.status < 300) {
                            load(request.responseText);
                        } else {
                            error('oh no');
                        }
                    }

                    request.send(formData);

                    request.onreadystatechange = () => {

                        if (request.responseText === "")
                            return false;

                        try {
                            var response = JSON.parse(request.responseText);
                        } catch (error){
                            console.log(error);
                        }

                        var type = response.type,
                            attached_id = response.id;


                        if (type === "error") {
                            return false;
                        } else {

                            var id = attached_id,
                                hidden_element = miuwpwpf_image_id[0],
                                replace_id = $(hidden_element).val();
                            if (replace_id === "" || replace_id === null)
                                replace_id = "";
                            else
                                id = replace_id + "," + id;
                            $(hidden_element).val(id);
                        }
                    }

                    return {
                        abort: () => {
                            request.abort();
                            abort();
                        }
                    };

                }
            }
        });
    }
}(jQuery));