@extends('admin::layouts.master')

@section('content')
    <h1>Seitenübersicht</h1>
    @if($pages and count($pages) > 0)
        @component('cms::components.overview_table',
           ['entities' => $pages,
           'tableheader' => ['Seitentitel','Pfad','Letzte Änderung'],
           'properties' => ['$entity->title','$entity->slug','$entity->updated_at'],
           'id' => 'page-overview','slug' => 'cms-page',
           'editRoute' => ['url' => 'cms.page.edit', 'param' => 'page'],
           'deleteRoute' => ['url' => 'cms.page.destroy', 'param' => 'page'],
           ])
            @slot('additionalActions')@endslot
            @slot('additionalScripts')@endslot
        @endcomponent
    @else
        <p>Es wurden noch keine statischen Seiten erfasst.</p>
    @endif
@endsection
