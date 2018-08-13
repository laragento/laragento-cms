<h2>{{ $element->title }}</h2>
<div>
    <?php $contentField = $element->fieldByTitle('content');
    $fieldValue = !empty($block) ? $element->valueByField($contentField, $block) : null;
    ?>
        <label>{{trans('cms::cms.element_heading.content')}}
        <input type="text" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : (!empty($fieldValue) ? $fieldValue->value : '')}}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>
<div>
    <?php
    $hValue = !empty($block) ? $element->valueByField($element->fieldByTitle('format'), $block) : null;
    ?>
        <label>{{trans('cms::cms.element_heading.format')}} ({{trans('cms::cms.element_heading.format_between')}})

        <input type="number" min="2" max="4" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : (!empty($hValue) ? $hValue->value : '')}}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>