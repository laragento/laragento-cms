<form novalidate class="blocktype-form" method="{{$method}}" action="{{$action}}">
    <div class="form-title">
        <h1>{{ $title }}</h1>
    </div>
    @csrf
    {{ $additionalTopFields }}
    <label>{{ trans('cms::block.title') }}
        <input type="text" name="meta[title]" autofocus placeholder=""
               value="{{ old('meta[title]') ? old('meta[title]') : !empty($block) ? $block->title : '' }}">
        @if ($errors->has('meta[title]'))
            <span class="form-error is-visible">{{ $errors->first('meta[title]') }}</span>
        @endif
    </label>
    <input type="hidden" name='meta[type_id]' value="{{$type->id}}">
    @foreach($type->elements as $element)
       @include('cms::elements.'.$element->elementType->title, compact('element','block'))
    @endforeach
    {{ $additionalBottomFields }}
    <div class="form-row action-row">
            <span class="text-left">
                <button class="button" type="submit">{{ $buttonTitle }}</button>
            </span>
    </div>
</form>