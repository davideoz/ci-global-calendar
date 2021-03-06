@extends('laravel-quick-menus::menuItems.layout')

@section('javascript-document-ready')
    @parent
    
    {{-- ON LOAD --}}
        hideShowsControls();

    {{-- ON CHANGE --}}
        $("select[name='type']").change(function(){
            hideShowsControls();
         });
         
     {{-- SHOW/HIDE elements relating with the selected menu item TYPE  --}}
         function hideShowsControls(){
             switch($("select[name='type']").val()) {
                 case "1":
                     $(".form-group.url").hide();
                     $(".routeFields").show();
                     $(".routeFields").fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                 break;
                 case "2":
                     $(".routeFields").hide();
                     $(".form-group.url").show();
                     $(".form-group.url").fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                 break; 
                 case "3":
                     $(".routeFields").hide();
                     $(".form-group.url").hide();
                 break;    
             }
         }
     
@stop


@section('content')    
    <div class="row">
        <div class="col-12 col-sm-6">
            <h2>@lang('laravel-quick-menus::menuItem.edit_menu_item')</h2>
        </div>
        <div class="col-12 col-sm-6 text-right">
            <span class="badge badge-secondary">English</span>
        </div>
    </div>

    @include('laravel-quick-menus::partials.error-management', [
      'style' => 'alert-danger',
    ])

    <form action="{{ route('menuItems.update',$menuItem->id) }}" method="POST">
        @csrf
        @method('PUT')

         <div class="row">
            <div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => __('laravel-quick-menus::menuItem.name'),
                      'name' => 'name',
                      'placeholder' => 'Menu item name',
                      'value' => $menuItem->translate('en')->name,
                      'required' => true,
                ])
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.select', [
                    'title' => __('laravel-quick-menus::menuItem.menu_id'),
                    'name' => 'menu_id',
                    'placeholder' => __('laravel-quick-menus::menuItem.menu_id'),
                    'records' => $menu,
                    'selected' => $menuItem->menu_id,
                    'liveSearch' => 'false',
                    'mobileNativeMenu' => true,
                    'required' => true,
                ])
            </div>
            
            <div class="col-12">
                @include('laravel-quick-menus::partials.select-menu-items-parent', [
                    'title' => __('laravel-quick-menus::menuItem.parent_menu_item'),
                    'name' => 'parent_item_id',
                    'placeholder' => __('laravel-quick-menus::menuItem.parent_menu_item'),
                    'records' => $menuItemsTree,
                    'selected' => $menuItem->parent_item_id,
                    'liveSearch' => 'false',
                    'mobileNativeMenu' => true,
                    'item_id' => $menuItem->id,
                    'required' => false,
                ])
            </div>
            
            <div class="col-12">
                <div class="form-group">
                    <strong>@lang('laravel-quick-menus::menuItem.menu_item_type')</strong>
                    <select name="type" class="selectpicker" title="Route or Url">
                        <option value="1" @if(empty($menuItem->type)) {{'selected'}} @endif @if(!empty($menuItem->type)) {{  $menuItem->type == '1' ? 'selected' : '' }} @endif>Route</option>
                        <option value="2" @if(!empty($menuItem->type)) {{  $menuItem->type == '2' ? 'selected' : '' }} @endif>Url</option>
                        <option value="3" @if(!empty($menuItem->type)) {{  $menuItem->type == '3' ? 'selected' : '' }} @endif>System - User Profile</option>
                        <option value="4" @if(!empty($menuItem->type)) {{  $menuItem->type == '4' ? 'selected' : '' }} @endif>System - Logout</option>    
                    </select>
                </div>
            </div>
            
            {{--<div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => __('laravel-quick-menus::menuItem.menu_item_route'),
                      'name' => 'route',
                      'placeholder' => 'Route',
                      'value' => $menuItem->route,
                      'required' => false,
                ])
            </div>--}}
            <div class="col-12">
                @include('laravel-quick-menus::partials.select-menu-items-route', [
                    'title' => __('views.menu_item_route'),
                    'name' => 'route',
                    'placeholder' => __('views.menu_item_route'),
                    'records' => $routeNames,
                    'liveSearch' => 'true',
                    'selected' => $menuItem->route,
                    'mobileNativeMenu' => false,
                    'required' => false,
                    'route_param_name_1' => $menuItem->route_param_name_1,
                    'route_param_name_2' => $menuItem->route_param_name_2,
                    'route_param_name_3' => $menuItem->route_param_name_3,
                    'route_param_value_1' => $menuItem->route_param_value_1,
                    'route_param_value_2' => $menuItem->route_param_value_2,
                    'route_param_value_3' => $menuItem->route_param_value_3,
                ])
            </div>
            
            <div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => 'Url',
                      'name' => 'url',
                      'placeholder' => 'The relative url - eg: /post/about',
                      'value' => $menuItem->url,
                      'hide' => true,
                      'required' => false,
                ])
            </div>
            <div class="col-12">
                <div class="form-group">
                    <strong>@lang('laravel-quick-menus::menuItem.menu_item_access')</strong>
                    <select name="access" class="selectpicker" title="Access">
                        <option value="1" @if(empty($menuItem->access)) {{'selected'}} @endif @if(!empty($menuItem->access)) {{  $menuItem->access == '1' ? 'selected' : '' }} @endif>Public</option>
                        <option value="2" @if(!empty($menuItem->access)) {{  $menuItem->access == '2' ? 'selected' : '' }} @endif>Guest</option>
                        <option value="3" @if(!empty($menuItem->access)) {{  $menuItem->access == '3' ? 'selected' : '' }} @endif>Manager</option>
                        <option value="4" @if(!empty($menuItem->access)) {{  $menuItem->access == '4' ? 'selected' : '' }} @endif>Administrator</option>
                        <option value="5" @if(!empty($menuItem->access)) {{  $menuItem->access == '5' ? 'selected' : '' }} @endif>Super Administrator</option>   
                    </select>
                </div>
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.select-menu-items-order', [
                    'title' => __('laravel-quick-menus::menuItem.menu_item_order'),
                    'name' => 'order',
                    'placeholder' => __('laravel-quick-menus::menuItem.menu_item_order'),
                    'records' => $menuItemsSameMenuAndLevel,
                    'selected' => $menuItem->id,
                    'tooltip' => "The menu item will be placed in the menu after the selected menu item.",
                    'liveSearch' => 'false',
                    'mobileNativeMenu' => true,
                    'required' => false,
                ])
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => __('laravel-quick-menus::menuItem.menu_item_font_awesome_class'),
                      'name' => 'font_awesome_class',
                      'placeholder' => __('laravel-quick-menus::menuItem.menu_item_font_awesome_class'),
                      'value' => $menuItem->font_awesome_class,
                      'required' => false,
                ])
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.checkbox', [
                      'name' => 'hide_name',
                      'description' => __('laravel-quick-menus::menuItem.menu_item_hide_name'),
                      'value' => $menuItem->hide_name,
                      'required' => false,
                ])
            </div>
        </div>

        @include('laravel-quick-menus::partials.buttons-back-submit', [
            'route' => 'menuItemsIndex',
            'routeParameter'  => $menuItem->menu_id,
        ])

    </form>

@endsection
