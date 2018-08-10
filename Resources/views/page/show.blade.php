@extends('cms::layouts.frontend')

@section('content')
    <h1>{{$page->title}}</h1>
    @if($blocks and count($blocks) > 0)
        @foreach($blocks as $block)
            <div class="{{$block->classes}}">
                @foreach($block->elements() as $element)
                    @include('cms::elements.frontend.'.$element->elementType->title, compact('element','block'))
                @endforeach
            </div>


        @endforeach
    @endif
@endsection
