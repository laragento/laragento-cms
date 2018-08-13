@extends('admin::layouts.master')

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{trans('cms::cms.page.new')}}"/>
    <title>{{trans('cms::cms.page.new')}}</title>
@endsection

@section('content')
    @component('cms::page.component_page_form',['blocktypes' => $blocktypes])
        @slot('method')
            POST
        @endslot
        @slot('action'){{route('cms.page.store')}}@endslot
        @slot('title'){{trans('cms::cms.page.new')}}@endslot
        @slot('buttonTitle'){{trans('admin::admin.add')}}@endslot
        @slot('additionalTopFields')@endslot
        @slot('additionalMiddleFields')@endslot
        @slot('additionalBottomFields')@endslot
    @endcomponent
@endsection