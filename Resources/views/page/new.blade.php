@extends('admin::layouts.master')

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{trans('cms::cms.new_page')}}"/>
    <title>{{trans('cms::cms.new_page')}}</title>
@endsection

@section('content')
    @component('cms::page.component_page_form',['blocktypes' => $blocktypes])
        @slot('method')
            POST
        @endslot
        @slot('action'){{route('cms.page.store')}}@endslot
        @slot('title'){{trans('cms::cms.new_page')}}@endslot
        @slot('buttonTitle'){{trans('cms::admin.add')}}@endslot
        @slot('additionalTopFields')@endslot
        @slot('additionalMiddleFields')@endslot
        @slot('additionalBottomFields')@endslot
    @endcomponent
@endsection