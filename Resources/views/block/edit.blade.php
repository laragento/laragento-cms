@extends('admin::layouts.master')

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{trans('cms::cms.edit_block')}}"/>
    <title>{{trans('cms::cms.edit_block')}}</title>
@endsection

@section('content')
    @component('cms::block.component_block_form', compact('page','type','block'))
        @slot('method')POST @endslot
        @slot('action'){{route('cms.block.update', ['block' => $block->id, 'page' => $page->id])}}@endslot
        @slot('title'){{trans('cms::cms.edit_block')}}@endslot
        @slot('buttonTitle'){{trans('cms::admin.update')}}@endslot
        @slot('additionalTopFields')
        @method('PATCH')
        @endslot
        @slot('additionalMiddleFields')@endslot
        @slot('additionalBottomFields')@endslot
    @endcomponent
@endsection