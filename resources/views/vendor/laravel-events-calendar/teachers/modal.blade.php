
{{--
    This modal is used in the event.create and event.edit view to add a new teacher
    It is loaded in view/partials/forms/modal-frame when the 
    button "Add new teacher" is clicked in the event create view
--}}

@extends('laravel-events-calendar::layouts.modal')

@section('javascript-document-ready')
    @parent
    
    $('#teacherModalForm').validate({
        rules: {
            name: "required",
            bio: "required",
            year_starting_practice: {
                required: true,
                range: [1972, {{Carbon\Carbon::now()->year}}],
            },
            year_starting_teach: {
                required: true,
                range: [1972, {{Carbon\Carbon::now()->year}}]
            },
            significant_teachers: "required",
            facebook: {
                required: false,
                url: true
            },
            website: {
                required: false,
                url: true
            }
        },
        submitHandler: function(form) {
            //alert("Do some stuff... 2");

             $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: '/create-teacher/modal/',
                data: new FormData($("#teacherModalForm")[0]),
                type: 'POST',
                contentType: false,
                processData: false,
                success: function(res) {
                    console.log("teacher created succesfully");
                    console.log(res.teacherId);

                    $('.modalFrame').modal('hide');
                    
                    $("select#teacher").append('<option value="'+res.teacherId+'" selected="">'+res.teacherName+'</option>');
                    $("select#teacher").selectpicker("refresh");
                    
                    $("input[name='multiple_teachers']").val($("input[name='multiple_teachers']").val() + "," + res.teacherId);
                },
                error: function(error) {
                    //$('.modalFrame').modal('hide');
                    console.log(error);
                }          
            });
        }
    });
    
@stop

@section('content')
    
    <div class="row">
        <div class="col-12">
            <button type="button" class="close" data-dismiss="modal"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="col-12 pb-3">
            <h4>@lang('laravel-events-calendar::teacher.add_new_teacher')</h4>
        </div>
    </div>

    @include('laravel-form-partials::error-management', [
          'style' => 'alert-danger',
    ])

    <form id="teacherModalForm" action="{{ route('teachers.storeFromModal') }}" method="POST" enctype="multipart/form-data">
        @csrf

         <div class="row">
            <div class="col-12">
                @include('laravel-form-partials::input', [
                      'title' => __('laravel-events-calendar::general.name'),
                      'name' => 'name',
                      'placeholder' => 'Teacher name',
                      'required' => true,
                ])
            </div>
            
            {{-- Created by - hidden --}}
            <div class="col-12 d-none">
                @include('laravel-form-partials::select', [
                    'title' => __('laravel-events-calendar::general.created_by'),
                    'name' => 'created_by',
                    'placeholder' => __('laravel-events-calendar::general.select_owner'),
                    'records' => $users,
                    'liveSearch' => 'true',
                    'mobileNativeMenu' => false,
                    'selected' => Auth::id(),
                    'required' => false,
                ])
            </div>

            <div class="col-12">
                @include('laravel-form-partials::select', [
                      'title' => __('laravel-events-calendar::general.country'),
                      'name' => 'country_id',
                      'placeholder' => 'Select country',
                      'records' => $countries,
                      'liveSearch' => 'true',
                      'mobileNativeMenu' => false,
                      'required' => false,
                ])
            </div>

            <div class="col-12">
                @include('laravel-form-partials::textarea-plain', [
                      'title' =>  __('laravel-events-calendar::teacher.bio'),
                      'name' => 'bio',
                      'required' => true,
                ])
            </div>
            <div class="col-12">
                @include('laravel-form-partials::input', [
                      'title' => __('laravel-events-calendar::teacher.year_of_starting_to_practice'),
                      'name' => 'year_starting_practice',
                      'placeholder' => 'AAAA',
                      'value' => '',
                      'required' => true,
                ])
            </div>
            <div class="col-12">
                @include('laravel-form-partials::input', [
                      'title' => __('laravel-events-calendar::teacher.year_of_starting_to_teach'),
                      'name' => 'year_starting_teach',
                      'placeholder' => 'AAAA',
                      'value' => '',
                      'required' => true,
                ])
            </div>
            <div class="col-12">
                @include('laravel-form-partials::textarea-plain', [
                      'title' =>  __('laravel-events-calendar::teacher.significant_teachers'),
                      'name' => 'significant_teachers',
                      'required' => true,
                ])
            </div>
            <div class="col-12">
                @include('laravel-form-partials::input', [
                      'title' => __('laravel-events-calendar::teacher.facebook_profile'),
                      'name' => 'facebook',
                      'placeholder' => 'https://...',
                      'value' => '',
                      'required' => false,
                ])
            </div>
            <div class="col-12">
                @include('laravel-form-partials::input', [
                      'title' => __('laravel-events-calendar::general.website'),
                      'name' => 'website',
                      'placeholder' => 'https://...',
                      'value' => '',
                      'required' => false,
                ])
            </div>
            
            @include('laravel-form-partials::upload-image', [
                  'title' => __('laravel-events-calendar::teacher.upload_profile_picture'), 
                  'name' => 'profile_picture',
                  'folder' => 'teachers_profile',
                  'value' => ''
            ])
        </div>

        <div class="row mt-5">
            <div class="col-6 pull-left">
                <button type="button" class="btn btn-primary" data-dismiss="modal">@lang('laravel-events-calendar::general.close')</button>
            </div>
            <div class="col-6 pull-right">
              <button id="save" type="submit" class="btn btn-primary float-right">@lang('laravel-events-calendar::general.submit')</button>
            </div>
        </div>

    </form>

@endsection
