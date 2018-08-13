@extends('admin::layouts.master')

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{trans('cms::cms.blocktype.new')}}"/>
    <title>{{trans('cms::cms.blocktype.new')}}</title>
@endsection

@section('content')
    @component('cms::blocktype.component_blocktype_form')
        @slot('method')POST @endslot
        @slot('action'){{route('cms.blocktype.store')}}@endslot
        @slot('title'){{trans('cms::cms.blocktype.new')}}@endslot
        @slot('buttonTitle'){{trans('admin::admin.add')}}@endslot
        @slot('additionalTopFields')@endslot
        @slot('additionalMiddleFields')@endslot
        @slot('additionalBottomFields')@endslot
    @endcomponent
@endsection