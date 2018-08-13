<h2>{{ $element->title }}</h2>

<div>
    <label>{{ trans('cms::element_image.image') }}
        <?php $contentField = $element->fieldByTitle('path');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block)->value : null;
        ?>
        <input type="file" name="fields[{{$element->id}}][{{$contentField->id}}]"
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : null }}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>
<div>
    <label>{{ trans('cms::element_image.name') }}
        <?php $contentField = $element->fieldByTitle('name');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block) : null;
        ?>
        <input type="text" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : (!empty($fieldValue) ? $fieldValue->value : '') }}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>
<div>
    <label>{{ trans('cms::element_image.alt') }}
        <?php $contentField = $element->fieldByTitle('alt_txt');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block) : null;
        ?>
        <input type="text" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : (!empty($fieldValue) ? $fieldValue->value : '') }}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>
<div>
    <label>{{ trans('cms::element_image.width') }}
        <?php $contentField = $element->fieldByTitle('width');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block) : null;
        ?>
        <input type="number" min="0" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : (!empty($fieldValue) ? $fieldValue->value : '') }}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>
<div>
    <label>{{ trans('cms::element_image.height') }}
        <?php $contentField = $element->fieldByTitle('height');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block) : null;
        ?>
        <input type="number" min="0" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : (!empty($fieldValue) ? $fieldValue->value : '')}}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>