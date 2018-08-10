<div>
    <label for="formated-text-field-{{$element->id}}">{{ $element->title }}</label>
        <?php $contentField = $element->fieldByTitle('content');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block)->value : null;
        ?>

        <input id="formated-text-field-{{$element->id}}" type="hidden" name="fields[{{$element->id}}][{{$contentField->id}}]" value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : !empty($fieldValue) && $fieldValue ? $fieldValue : '' }}"/>
        <trix-editor input="formated-text-field-{{$element->id}}" autofocus></trix-editor>
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
</div>
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.12.0/trix.css">
@endsection
@section('topscripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/0.12.0/trix.js"></script>
@endsection