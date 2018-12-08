@extends('countries.layout')


@section('content')
    <div class="row">
        <div class="col-12">
            <h2>@lang('views.countries_management')</h2>
        </div>
        <div class="col-12 mt-4 mt-sm-0 text-right">
            <a class="btn btn-success" href="{{ route('countries.create') }}">@lang('views.create_new_country')</a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-4">
            <p>{{ $message }}</p>
        </div>
    @endif

    <form class="row mt-3" action="{{ route('countries.index') }}" method="GET">
        @csrf
        <div class="form-group col-8 col-sm-10 col-md-10 col-lg-10">
            <input type="text" name="keywords" id="keywords" class="form-control" placeholder="@lang('views.search_by_country_name')" value="{{ $searchKeywords }}">
        </div>
        <div class="col-4 col-sm-2 col-md-2 col-lg-2">
            <input type="submit" value="Search" class="btn btn-primary float-sm-right">
        </div>
    </form>


    {{-- List of countries --}}
    <div class="countriesList my-4">
        @foreach ($countries as $country)
            <div class="row p-1 {{ $loop->index % 2 ? 'bg-light': 'bg-white' }}">
                <div class="col-12 col-sm-6 col-lg-7 py-3 title">
                    <a href="{{ route('countries.edit',$country->id) }}">{{ $country->name }}</a>
                </div>
                <div class="col-6 col-sm-3 col-lg-2 py-3 code">
                    <i data-toggle="tooltip" data-placement="top" title="" class="far fa-barcode-alt mr-2" data-original-title="@lang('general.code')"></i>
                    {{ $country->code }} 
                </div>
                <div class="col-6 col-sm-3 col-lg-3 py-3 continent">
                    <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-globe-americas mr-2" data-original-title="@lang('general.continent')"></i>
                    {{ $continents[$country->continent_id] }}
                </div>
                
                <div class="col-12 pb-2 action">
                    <form action="{{ route('countries.destroy',$country->id) }}" method="POST">

                        <a class="btn btn-info mr-2" href="{{ route('countries.show',$country->id) }}">@lang('views.view')</a>
                        <a class="btn btn-primary" href="{{ route('countries.edit',$country->id) }}">@lang('views.edit')</a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger float-right">@lang('views.delete')</button>
                    </form>
                </div>

            </div>
        @endforeach    
    </div>

    {{-- List of countries --}}
    {{--<table class="table table-bordered mt-2">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th width="140">Code</th>
            <th width="180">Continent</th>
            <th width="280">Action</th>
        </tr>
        @foreach ($countries as $country)
        <tr>
            <td>{{ $country->id }}</td>
            <td>{{ $country->name }}</td>
            <td>{{ $country->code }}</td>
            <td>{{ $continents[$country->continent_id] }}</td>
            <td>
                <form action="{{ route('countries.destroy',$country->id) }}" method="POST">


                    <a class="btn btn-info" href="{{ route('countries.show',$country->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('countries.edit',$country->id) }}">Edit</a>


                    @csrf
                    @method('DELETE')


                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </table>--}}



    {!! $countries->links() !!}


@endsection
