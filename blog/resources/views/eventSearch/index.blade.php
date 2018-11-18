@extends('eventSearch.layout')

@section('javascript')
    <script type="text/javascript">
        $(document).ready(function() {
            // Clear filters on click reset button
                $("#resetButton").click(function(){
                    $("input#keywords").val("");
                    $('#category option').prop("selected", false).trigger('change');
                    $('#country option').prop("selected", false).trigger('change');
                    $('form#searchForm').submit();
                });
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Event search interface </h2>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    {{-- Search form --}}
    <form class="row mt-3" id="searchForm" action="{{ route('eventSearch.index') }}" method="GET">
        @csrf
        <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <input type="text" name="keywords" id="keywords" class="form-control" placeholder="Search by event name" value="{{ $searchKeywords }}">
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
            @include('partials.forms.event-search.select-category')
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 mt-sm-0 mt-3">
            @include('partials.forms.event-search.select-country')
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 mt-sm-0 mt-3">
            <a id="resetButton" class="btn btn-info" href="#">Reset</a>
            <input type="submit" value="Search" class="btn btn-primary">
        </div>
    </form>

    {{-- List of events --}}
    <table class="table table-bordered mt-4">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Category</th>
            <th>Country</th>
        </tr>
        @foreach ($events as $event)
        <tr>
            <td>{{ $event->id }}</td>
            <td><a href="{{ route('eventSearch.show',$event->id) }}">{{ $event->title }}</a></td>
            <td>{{ $eventCategories[$event->category_id] }}</td>
            {{--<td>{{ $countries[$event->venue] }}</td>--}}
            {{--<td>{{ $countries[$event->eventVenues] }}</td>--}}
            <td>
                {{ $event->sc_country_name }}
                {{-- @foreach ($event->eventVenues as $venue)
                    <div>{{ $countries[$venue->country_id] }}</div>
                @endforeach--}}
            </td>
        </tr>
        @endforeach
    </table>


    {!! $events->links() !!}




@endsection
