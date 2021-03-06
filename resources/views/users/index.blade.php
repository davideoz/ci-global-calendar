@extends('users.layout')

@section('javascript-document-ready')
    @parent
    {{--  Clear filters on click reset button --}}
        $("#resetButton").click(function(){
            $("input[name=keywords]").val("");
            $("select[name=country_id] option").prop("selected", false).trigger('change');
            $('form.searchForm').submit();
        });
@stop

@section('content')
    <div class="container max-w-md px-0">
        <div class="row">
            <div class="col-12 col-sm-7">
                <h4>@lang('views.users_management')</h4>
            </div>
            <div class="col-12 col-sm-5 mt-4 mt-sm-0 text-right">
                <a class="btn btn-success create-new" href="{{ route('users.create') }}"><i class="fa fas fa-plus-circle"></i> @lang('views.create_new_user')</a>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success mt-4">
                <p>{{ $message }}</p>
            </div>
        @endif

        {{-- Search form --}}
        <form class="mt-3 searchForm" action="{{ route('users.index') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-12 col-sm-6 pr-sm-2">
                    @include('laravel-form-partials::input', [
                        'name' => 'keywords',
                        'placeholder' => __('views.search_by_user_name'),
                        'value' => $searchKeywords
                    ])
                </div>
                <div class="col-12 col-sm-6">
                    @include('laravel-form-partials::select', [
                        'name' => 'country_id',
                        'placeholder' => __('views.filter_by_country'),
                        'records' => $countries,
                        'selected' => $searchCountry,
                        'liveSearch' => 'true',
                        'mobileNativeMenu' => false,
                    ])
                </div>
                <div class="col-12">
                    <input type="submit" value="@lang('general.search')" class="btn btn-primary float-right ml-2">
                    <a id="resetButton" class="btn btn-outline-primary float-right" href="#">@lang('general.reset')</a>
                </div>
            </div>
        </form>

        {{-- List of users --}}
        <div class="usersList my-4">
            @foreach ($users as $user)
                <div class="row bg-white shadow-1 rounded mb-3 pb-2 pt-3 mx-1">
                    <div class="col-12 py-1 order-1">
                        <div class="row">
                            <div class="col-5">
                                <h5 class="darkest-gray">{{ $user->name }}</h5>
                            </div>
                            <div class="col-7 pt-1">
                                @if(empty($user->status)){!! '<span class="badge badge-secondary float-right">'.__('views.disabled').'</span>' !!}@else{!!'<span class="badge badge-success float-right">'.__('views.enabled').'</span>'!!}@endif
                                @if(empty($user->status)){!! '<a class="btn btn-success button-rounded float-right mr-2" style="padding: 0px 10px" href="/activate-user-from-backend/'!!}{{$user->id}}{!!'">Enable</a> '!!}@endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4 order-2">
                        <div class="row">
                            <div class="col-12 col-sm-6">
                                <i data-toggle="tooltip" data-placement="top" title="" class="far fa-globe-americas mr-1 dark-gray" data-original-title="@lang('general.country')"></i>
                                @if(!empty($user->country_id)){{ $countries[$user->country_id] }}@endif    
                                <i data-toggle="tooltip" data-placement="top" title="" class="fas fa-key mr-1 ml-4 dark-gray" data-original-title="@lang('general.country')"></i>
                                {{ App\User::getUserGroupString($user->group)}}    
                            </div>
                            <div class="col-12 col-sm-6 mt-3 mt-sm-0 dark-gray text-right">
                                <i class="far fa-envelope mr-1 dark-gray"></i>{{$user->email}}
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pb-2 action order-3">
                        <form action="{{ route('users.destroy',$user->id) }}" method="POST">

                            <a class="btn btn-primary float-right" href="{{ route('users.edit',$user->id) }}">@lang('views.edit')</a>
                            <a class="btn btn-outline-primary mr-2 float-right" href="{{ route('users.show',$user->id) }}">@lang('views.view')</a>
                            
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-link pl-0">@lang('views.delete')</button>
                        </form>
                    </div>
                </div>
                
                {{--<div class="row p-1 {{ $loop->index % 2 ? 'bg-light': 'bg-white' }}">
                    <div class="col-12 col-sm-5 py-3 name">
                        <a href="{{ route('users.edit',$user->id) }}">{{ $user->name }}</a>
                    </div>
                    <div class="col-6 col-sm-4 py-3 country">
                        <i data-toggle="tooltip" data-placement="top" title="" class="far fa-globe-americas mr-2" data-original-title="@lang('general.country')"></i>
                        @if(!empty($user->country_id)){{ $countries[$user->country_id] }}@endif
                    </div>
                    <div class="col-6 col-sm-3 py-3 status text-right">
                        @if(!empty($user->status)){!! '<span class="badge badge-success">'.__('views.enabled').'</span>' !!}@else{!!'<span class="badge badge-secondary">'.__('views.disabled').'</span>'!!}@endif
                    </div>
                    <div class="col-12 pb-2 action">
                        <form action="{{ route('users.destroy',$user->id) }}" method="POST">

                            <a class="btn btn-info mr-2" href="{{ route('users.show',$user->id) }}">@lang('views.view')</a>
                            <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">@lang('views.edit')</a>

                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-danger float-right">@lang('views.delete')</button>
                        </form>
                    </div>
                </div>--}}
            @endforeach 
        </div>
        
        {!! $users->appends([
            'country_id' => $searchCountry,
            'keywords' => $searchKeywords,
        ])->links() !!}
    </div>

@endsection
