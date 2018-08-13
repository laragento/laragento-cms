@extends('admin::layouts.master')

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{$page->title}}"/>
    <title>{{$page->title}}</title>
@endsection

@section('content')
    @component('cms::page.component_page_form',['page' => $page, 'blocktypes' => $blocktypes])
        @slot('method')
            POST
        @endslot
        @slot('action'){{route('cms.page.update', ['page' => $page->id])}}@endslot
        @slot('title'){{trans('cms::cms.page.edit')}}@endslot
        @slot('buttonTitle'){{trans('admin::admin.edit')}}@endslot
        @slot('additionalTopFields')
            @method('PATCH')
        @endslot
        @slot('additionalMiddleFields')
            <div class="form-row">
                <label class="sub-label">{{ trans('cms::page.is_active') }}
                    <input type="checkbox" name="is_active" {{$page->is_active === 1 ?  ' checked' : ''}}>
                </label>
            </div>
        @endslot
        @slot('additionalBottomFields')@endslot
    @endcomponent
    <div class="page-block-new">
        @if(count($blocktypes) > 0)
            <form method="post" action="{{route('cms.block.add', ['page' => $page->id])}}">
                @csrf
                <label>
                    <select name="block_type" id="block_type">
                        <option value="">Block wählen...</option>
                        @foreach($allowedTypes as $type)
                            <option value="{{$type->id}}">{{$type->title}}</option>
                        @endforeach
                    </select>
                </label>
                <button class="button" type="submit">Hinzufügen</button>
            </form>
        @else
            <p>Keine Blocktypen erfasst</p>
        @endif
    </div>
    <div class="page-block-list">
        <span>Blöcke</span>
        @if(count($page->blocks) > 0)
            <div class="pseudo-table table-columns-3">
                @foreach($page->blocks as $block)
                    <div class="table-body">
                        <div data-content="" class="table-cell">
                            {{$block->blockType->title}}
                        </div>
                        <div data-content="" class="table-cell">
                            {{$block->title}}
                        </div>

                        <div data-content="" class="table-cell">
                            <a href="{{route('cms.block.update',['page' => $page->id, 'block' => $block->id])}}">{{trans('admin::admin.edit')}}</a>
                            <form id="block-{{$block->id}}-delete-form"
                                  action="{{route('cms.block.destroy',['page' => $page->id,'block' => $block->id])}}"
                                  method="post">
                                @csrf
                                @method('delete')
                                <button class="pseudo-link">{{trans('admin::admin.delete')}}</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Keine Elemente vorhanden</p>
        @endif
    </div>
@endsection