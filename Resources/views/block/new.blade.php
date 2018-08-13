@extends('admin::layouts.master')

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{trans('cms::cms.block.new')}}"/>
    <title>{{trans('cms::cms.block.new')}}</title>
@endsection

@section('content')
    @component('cms::block.component_block_form', compact('page','type'))
        @slot('method')POST @endslot
        @slot('action'){{route('cms.block.store', ['page' => $page->id])}}@endslot
        @slot('title'){{trans('cms::cms.block.new')}}@endslot
        @slot('buttonTitle'){{trans('admin::admin.add')}}@endslot
        @slot('additionalTopFields')@endslot
        @slot('additionalMiddleFields')@endslot
        @slot('additionalBottomFields')@endslot
    @endcomponent
@endsection