@extends('eventSearch.layout')

@section('javascript-document-ready')
    @parent

    {{-- Clear filters on click reset button --}}
    $("#resetButton").click(function(){
        $("input#keywords").val("");
        $('#category option').prop("selected", false).trigger('change');
        $('#teacher option').prop("selected", false).trigger('change');
        $('#country option').prop("selected", false).trigger('change');
        $('#continent option').prop("selected", false).trigger('change');
        $('form#searchForm').submit();
    });

@stop


@section('content')
    <div class="row eventFormTitle">
        <div class="col-lg-12 text-center">
            <h1>Contact Improvisation</h1>
            <h2 style="color: rgb(240, 142, 13);">- Global calendar -</h2>
            <p class="subtitle">
                Find information about Contact Improvisation events worldwide (classes, jams, workshops, festivals and more)<br>WE ARE UNDER CONSTRUCTION, calendar is still in beta testing phase, we plan to fully operate starting from January 2019 on
            </p>
            <p class="searchHere">
                Search here with one criteria or more
            </p>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    {{-- Search form --}}
    <form id="searchForm" action="{{ route('eventSearch.index') }}" method="GET">
        @csrf

        {{--<div class="row mt-3">
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <input type="text" name="keywords" id="keywords" class="form-control" placeholder="Search by event name" value="{{ $searchKeywords }}">
            </div>
        </div>--}}

        <div class="row">
            <div class="col-md-4">
                <p><strong>What</strong></p>
                @include('partials.forms.event-search.select-category')

                <p class="mt-3"><strong>Who</strong></p>
                @include('partials.forms.event-search.select-teacher')
            </div>
            <div class="col-md-4">
                <p><strong>Where</strong></p>
                @include('partials.forms.event-search.select-continent')
                @include('partials.forms.event-search.select-country')
                <p class="mt-3"><strong>Search by name of venue only</strong></p>
            </div>
            <div class="col-md-4">
                <p><strong>When</strong></p>
                @include('partials.forms.event-search.input-date-start')
                @include('partials.forms.event-search.input-date-end')
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-sm-10 mt-3">
                <a id="resetButton" class="btn btn-info float-right ml-2" href="#">Reset</a>
                <input type="submit" value="Search" class="btn btn-primary float-right">
            </div>
        </div>
    </form>

    {{-- List of events --}}
    <table class="table table-bordered mt-4">
        <tr>
            <th>Title</th>
            <th>Teachers</th>
            <th>Category</th>
            <th>Venue</th>
        </tr>
        @foreach ($events as $event)
        <tr>
            <td><a href="{{ route('eventSearch.show',$event->id) }}">{{ $event->title }}</a></td>
            <td></td>
            <td>{{ $eventCategories[$event->category_id] }}</td>
            {{--<td>{{ $countries[$event->venue] }}</td>--}}
            {{--<td>{{ $countries[$event->eventVenues] }}</td>--}}
            <td>
                {{ $event->sc_venue_name }}<br />
                {{ $event->sc_city_name }},
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
