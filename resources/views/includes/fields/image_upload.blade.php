
@once
    @push('style')
        @push('style')
            <link rel="stylesheet" href="/css/cropper.css" />
        @endpush
    @endpush
@endonce


<div class="image-upload" data-field-name="{{$field_name}}">
    <div class="row ">

        <div class="col-12 col-md-8">
            <label for=".chose-file">{{$field_label}}</label>
            <div class="mt-2">
                <img class="d-block w-100 mt-2" id="main-image" src="{{(!empty($value??null))?$value:''}}" style="max-width: 100%; ">
            </div>

        </div>
        <div class=" col-12 col-md-4">
            <div class="image-preview-container d-none">
                <label>Preview</label>
                <div id="{{$field_name}}-img-preview" class=" mt-2" style="width: 256px; height: 144px; max-width: 100%; overflow: hidden"></div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <div class="input-group mb-3">
                <button class="btn btn-outline-secondary chose-file" type="button">Chose file</button>
                <button class="btn btn-outline-secondary cropper-rotate-image-right" type="button"><i class="fa fa-undo-alt"></i></button>
                <button class="btn btn-outline-secondary cropper-rotate-image-left" type="button"><i class="fa fa-redo-alt"></i></button>
                <button class="btn btn-outline-secondary cropper-reset" type="button"><i class="fa fa-sync-alt"></i></button>
                <button class="btn btn-outline-secondary cropper-destroy" type="button"><i class="fa fa-trash-alt"></i></button>
                @if(!empty($value??null))
                <button class="btn btn-outline-secondary cropper-restore-original" type="button" data-original-value="{{$value}}">Restore original image</button>
                @endif
                <input class="form-control" type="hidden" id="hiddenFile" name="{{$field_name}}">
                <div class="invalid-feedback" data-input-field="#{{$field_name}}"></div>
                <input class="form-control d-none" type="file" id="uploadFile" accept="image/*">

            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>

        if(typeof file_upload_initialized == 'undefined')
        {
            var file_upload_initialized = true;


            $('.image-upload').each(function (id, element) {

                    let filed_name = $(this).attr('data-field-name');

                    let $mainImage = $(this).find('img#main-image');
                    let $uploadFile = $(this).find('input#uploadFile');
                    let $hiddenFile = $(this).find('input#hiddenFile');
                    let $previewContainer = $(this).find('.image-preview-container').first();

                    let $choseFile = $(this).find('button.chose-file').first();
                    let $rotateLeft = $(this).find('button.cropper-rotate-image-left').first();
                    let $rotateRight = $(this).find('button.cropper-rotate-image-right').first();
                    let $reset = $(this).find('button.cropper-reset').first();
                    let $destroy = $(this).find('button.cropper-destroy').first();
                    let $restore_original_image = $(this).find('button.cropper-restore-original').first();


                    $choseFile.click(function (e) {
                        e.stopImmediatePropagation();
                        $uploadFile.trigger('click');
                    });

                    let options = {
                        responsive: true,
                        aspectRatio: 1,
                        viewMode: 2,
                        checkOrientation: false,
                        autoCropArea: 1,
                        preview: '#' + filed_name + '-img-preview',
                    }

                    $uploadFile.change(function (e) {
                        console.log('here');
                        let fileReader = new FileReader();
                        let files = this.files;
                        let file;

                        if (!files.length) {
                            console.log('failed123');
                            return;
                        }
                        file = files[0];

                        if (!(/^image\/\w+$/.test(file.type))) {
                            console.log('failed');
                            return false;
                        }

                        fileReader.readAsDataURL(file);
                        fileReader.onload = function () {
                            $uploadFile.val("");
                            $mainImage.cropper(options).cropper("reset", true).cropper("replace", this.result);
                            $mainImage.attr('data-file-type',file.type);
                            $previewContainer.removeClass('d-none');

                            $rotateLeft.click(function () {
                                if($mainImage.cropper('getCroppedCanvas') == null) return false;
                                $mainImage.cropper("rotate", 90);
                            });
                            $rotateRight.click(function () {
                                if($mainImage.cropper('getCroppedCanvas') == null) return false;
                                $mainImage.cropper("rotate", -90);
                            });
                            $reset.click(function () {
                                $mainImage.cropper("reset");
                            });

                            $destroy.click(function () {
                                $mainImage.cropper("destroy");
                                $previewContainer.addClass('d-none');
                                $mainImage.attr('src', '');
                                $hiddenFile.val('');
                            });
                            $restore_original_image.click(function () {
                                let original_image_value = $(this).attr('data-original-value')||''
                                $mainImage.cropper("destroy");
                                $previewContainer.addClass('d-none');
                                $mainImage.attr('src', original_image_value);
                                $hiddenFile.val('');
                            });
                        };
                    });
                });
        }
    </script>
@endpush
