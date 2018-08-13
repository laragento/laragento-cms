@extends('admin::layouts.master')

@section('content')
    <h1>{{trans('cms::cms.blocktype.overview')}}</h1>
    @if($blocktypes and count($blocktypes) > 0)
        @component('cms::components.overview_table',
            ['entities' => $blocktypes,
            'tableheader' => ['Typname','Elementanzahl','Letzte Änderung'],
            'properties' => ['$entity->title','count($entity->elements)','$entity->updated_at'],
            'id' => 'blocktype-overview','slug' => 'cms-blocktype',
            'editRoute' => ['url' => 'cms.blocktype.edit', 'param' => 'blockType'],
            'deleteRoute' => ['url' => 'cms.blocktype.destroy', 'param' => 'blockType'],
            ])
            @slot('additionalActions')@endslot
            @slot('additionalScripts')@endslot
        @endcomponent
    @else
        <p>{{trans('cms::cms.blocktype.no_blocktypes')}}</p>
    @endif
@endsection
