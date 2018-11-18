@extends('events.layout')

@section('javascript')
    @parent
    javascript-create-view

@stop

{{--
@section('javascript')


        <script type="text/javascript">
            $(document).ready(function(){





                // Event Repeat
                    $("input[name='repeatControl']").change(function(){
                        var radioVal = $("input[name='repeatControl']:checked").val();
                        alert(radioVal);
                    });


            });
        </script>

@endsection
--}}

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New event</h2>
            </div>
        </div>
    </div>

    @include('partials.forms.error-management')

    <form action="{{ route('events.store') }}" method="POST">
        @csrf

         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Title:</strong>
                    <input type="text" name="title" class="form-control" placeholder="Event title">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                @include('partials.forms.select-event-category')
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                @include('partials.forms.select-event-status')
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                @include('partials.forms.textarea-event')
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                @include('partials.forms.select-event-teacher')
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                @include('partials.forms.select-event-organizer')
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                @include('partials.forms.select-event-venue')
            </div>

        </div>

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <legend>Start, End, Duration</legend>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                @include('partials.forms.input-date-start')
            </div>

            <div class="col-xs-6 col-sm-6 col-md-6">
                @include('partials.forms.input-time-start')
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
                @include('partials.forms.input-date-end')
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
                @include('partials.forms.input-time-end')
            </div>
        </div>

        @include('partials.repeat-event')

        @include('partials.forms.image-event')

        <div class="row mt-5">
            <div class="col-xs-6 col-sm-6 col-md-6 pull-left">
                <a class="btn btn-primary" href="{{ route('events.index') }}"> Back</a>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 pull-right">
              <button type="submit" class="btn btn-primary float-right">Submit</button>
            </div>
        </div>


        <!--<input type="hidden" name="author_id" value="1">
        <input type="hidden" name="image" value="3">-->


    </form>

@endsection
