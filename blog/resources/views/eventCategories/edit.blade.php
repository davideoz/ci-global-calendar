@extends('eventCategories.layout')


@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>@lang('views.edit_event_category')</h2>
            </div>
        </div>
    </div>

    @include('partials.forms.error-management', [
      'style' => 'alert-danger',
    ])

    <form action="{{ route('eventCategories.update', $eventCategory->id) }}" method="POST">
        @csrf
        @method('PUT')

         <div class="row">
            <div class="col-12">
                @include('partials.forms.input', [
                      'title' => __('general.name'),
                      'name' => 'name',
                      'placeholder' => 'Event category name',
                      'value' => $eventCategory->name
                ])
            </div>
        </div>

        @include('partials.forms.buttons-back-submit', [
            'route' => 'eventCategories.index'  
        ])

    </form>


@endsection
