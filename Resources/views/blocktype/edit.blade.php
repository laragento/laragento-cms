@extends('admin::layouts.master')

@section('meta')
    <meta property="og:url" content="{{ url()->current() }}"/>
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="{{trans('cms::cms.edit_blocktype')}}"/>
    <title>{{trans('cms::cms.edit_blocktype')}}</title>
@endsection

@section('content')
    @component('cms::blocktype.component_blocktype_form', ['blocktype' => $blocktype])
        @slot('method')POST @endslot
        @slot('action'){{route('cms.blocktype.update', ['blockType' => $blocktype->id])}}@endslot
        @slot('title'){{trans('cms::cms.edit_blocktype')}}@endslot
        @slot('buttonTitle'){{trans('cms::admin.edit')}}@endslot
        @slot('additionalTopFields')
            @method('PATCH')
        @endslot
        @slot('additionalBottomFields')

        @endslot
    @endcomponent
    <div class="blocktype-element-new">
        @if(count($elementtypes) > 0)
            <form method="post" action="{{route('cms.blocktype.element.add', ['blocktype' => $blocktype->id])}}">
                @csrf
                <label>
                    <select name="element_type_id" id="element_type_id">
                        <option value="">Typ wählen...</option>
                        @foreach($elementtypes as $type)
                            <option value="{{$type->id}}">{{$type->title}}</option>
                        @endforeach
                    </select>
                </label>
                <label>
                    <input type="text" name="title"/>
                </label>
                <button class="button" type="submit">Hinzufügen</button>
            </form>
        @else
            <p>Keine Elementtypen vorhanden</p>
        @endif
    </div>
    <div class="blocktype-element-list">
        <span>Elemente</span>

        @if(count($blocktype->elements) > 0)
            <div class="pseudo-table table-columns-4">
                @foreach($blocktype->elements as $element)
                    <div class="table-body">
                        <div data-content="" class="table-cell">
                            {{$element->elementType->title}}
                        </div>
                        <div data-content="" class="table-cell">
                            <form id="element-{{$element->id}}-edit-form"
                                  action="{{route('cms.blocktype.element.update',['element' => $element->id])}}"
                                  method="post">
                                @csrf
                                @method('patch')
                                <label>{{ trans('cms::blocktype.elementtitle') }}
                                    <input type="text" name="title" required autofocus placeholder=""
                                           value="{{ old('title') ? old('title') : !empty($element) ? $element->title : '' }}">
                                    @if ($errors->has('title'))
                                        <span class="form-error is-visible">{{ $errors->first('title') }}</span>
                                    @endif
                                </label>

                                <label>{{ trans('cms::blocktype.blocktypeposition') }}
                                    <input type="number" min="0" name="sort_nr" required autofocus placeholder=""
                                           value="{{ old('sort_nr') ? old('sort_nr') : !empty($element) ? $element->sort_nr : '' }}">
                                    @if ($errors->has('sort_nr'))
                                        <span class="form-error is-visible">{{ $errors->first('sort_nr') }}</span>
                                    @endif
                                </label>
                                <button type="submit" class="pseudo-link">Ändern</button>
                            </form>
                        </div>


                        <div data-content="" class="table-cell">
                            <form id="element-{{$element->id}}-delete-form"
                                  action="{{route('cms.blocktype.element.remove',['element' => $element->id])}}"
                                  method="post">
                                @csrf
                                @method('delete')
                                <button class="pseudo-link">Löschen</button>
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
@section('scripts')
    <script>
        let submitDeleteForm = function (id) {
            let form = document.querySelector('#cms' + id + '-delete-form');
            form.submit();
        };
    </script>
@endsection