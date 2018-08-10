<div>
    <label>{{ $element->title }}
        <?php $contentField = $element->fieldByTitle('content');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block)->value : null;
        ?>
        <input type="text" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : !empty($fieldValue) && $fieldValue ? $fieldValue : '' }}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>