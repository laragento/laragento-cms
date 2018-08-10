<div>
    <label>{{ $element->title }}
        <?php $contentField = $element->fieldByTitle('content');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block)->value : null;
        ?>
        <textarea name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus cols="30" rows="10">{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : $fieldValue ? $fieldValue : '' }}</textarea>
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>