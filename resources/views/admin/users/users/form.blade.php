@extends($packageVariables->get('adminLayout'))
@php
    use Illuminate\Support\Collection;
     /**
     * @var Collection $packageVariables
     * @var Collection $viewVals
     */
@endphp
@component($packageVariables->get('blades').'admin._components.createEditButtons',['viewVals'=>$viewVals]    )

    @if($viewVals->get('action')=='edit' )
        @slot('after')
            <div class="ml-auto">
                @if(auth()->user()->canImpersonate() and $viewVals->get('record')->canBeImpersonated() and auth()->user()->id!=$viewVals->get('record')->id )
                    <a class="btn btn-outline-info  " href="{{ route('admin.impersonate', $viewVals->get('record')->id) }}">
                        <span class="btn-label text-danger"><i class="fa fa-user-o"></i></span>
                        @lang($viewVals->get('modelLang').'.general.impersonate')
                    </a>
                @endif
                @if($contact=$viewVals->get('record')->contact)
                    <a class="btn btn-outline-secondary  m-1" role="button"
                       href="{{route($contact->getFrontEndConfigPrefixed('admin', 'route') . '.edit', $contact)}}">
                        <span class="btn-label"><i class="fa fa-address-card" aria-hidden="true"></i></span>
                        @lang($viewVals->get('modelLang').'.fields.contactAssigned')
                    </a>
                @endif
            </div>
        @endslot
    @endif

@endcomponent

@section('content')
    @php($form=$viewVals->get('extraValues')->get('form'))
    {!!  form_start($form) !!}

    <div class="row">
        <div class="col-xl-5 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.first')] )
                {!! form_until($form, 'bio') !!}
            @endcomponent
        </div>
        <div class="col-xl-4 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('modelLang').'.formTitles.contactDetails')] )
                {!! form_until($form, 'phone2') !!}
            @endcomponent
        </div>
        <div class="col-xl-3 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.advancedSettings')] )
                {!!form_until($form, 'roles'); !!}
            @endcomponent
        </div>
        <div class="col-xl-12 col-md-6 col-12 mb-3">
            @component($packageVariables->get('blades').'admin._components.formCard',['title'=>__($viewVals->get('baseLang').'.formTitles.third')] )
                {!!form_rest($form); !!}
            @endcomponent
        </div>
    </div>
    {!!form_end($form, true); !!}


    <div class="card">
        <div class="card-header">Crop and Upload Image</div>
        <div class="card-body">
            <div class="form-group">
                @csrf
                <div class="row">
                    <div class="col-md-4" style="border-right:1px solid #ddd;">
                        <div id="image-preview"></div>
                    </div>
                    <div class="col-md-4" style="padding:75px; border-right:1px solid #ddd;">
                        <p><label>Select Image</label></p>
                        <input type="file" name="upload_image" id="upload_image" />
                        <br />
                        <br />
                        <button class="btn btn-success crop_image">Crop & Upload Image</button>
                    </div>
                    <div class="col-md-4" style="padding:75px;background-color: #333">
                        <div id="uploaded_image" align="center"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection



<!--ImageField  END -->
@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.css" integrity="sha512-zxBiDORGDEAYDdKLuYU9X/JaJo/DPzE42UubfBw9yg8Qvb2YRRIQ8v4KsGHOx2H1/+sdSXyXxLXv5r7tHc9ygg==" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous"></script>
    <script>$(document).ready(function(){

            $image_crop = $('#image-preview').croppie({
                enableExif:true,
                viewport:{
                    width:200,
                    height:200,
                    type:'circle'
                },
                boundary:{
                    width:300,
                    height:300
                }
            });

            $('#upload_image').change(function(){
                var reader = new FileReader();

                reader.onload = function(event){
                    $image_crop.croppie('bind', {
                        url:event.target.result
                    }).then(function(){
                        console.log('jQuery bind complete');
                    });
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('.crop_image').click(function(event){
                $image_crop.croppie('result', {
                    type:'canvas',
                    size:'viewport'
                }).then(function(response){
                    var _token = $('input[name=_token]').val();
                    $.ajax({
                        url:'{{ ''}}',
                        type:'post',
                        data:{"image":response, _token:_token},
                        dataType:"json",
                        success:function(data)
                        {
                            var crop_image = '<img src="'+data.path+'" />';
                            $('#uploaded_image').html(crop_image);
                        }
                    });
                });
            });

        });  </script>
@endpush
{{--Route::get('image_crop','ImageCropController@index');--}}

{{--Route::post('image_crop/upload', 'ImageCropController@upload')->name('image_crop.upload');--}}

