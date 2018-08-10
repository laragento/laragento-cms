@extends('laragentoadmin::layouts.master')

@section('content')
    <h1>Typenübersicht</h1>
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
        <p>Es wurden noch keine Blocktypen erfasst.</p>
    @endif
@endsection
