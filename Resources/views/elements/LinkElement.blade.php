<h2>{{ $element->title }}</h2>

<div>
    <label>{{ trans('cms::element_link.url') }}
        <?php $contentField = $element->fieldByTitle('link_url');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block)->value : null;
        ?>
        <input type="text" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") :  $fieldValue ? $fieldValue : '' }}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>
<div>
    <label>{{ trans('cms::element_link.title') }}
        <?php $contentField = $element->fieldByTitle('link_title');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block)->value : null;
        ?>
        <input type="text" name="fields[{{$element->id}}][{{$contentField->id}}]" autofocus placeholder=""
               value="{{ old("fields[$element->id][$contentField->id]") ? old("fields[$element->id][$contentField->id]") : $fieldValue ? $fieldValue : '' }}">
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>
<div>
    <label>{{ trans('cms::element_link.open_blank') }}
        <?php $contentField = $element->fieldByTitle('open_blank');
        $fieldValue = !empty($block) ? $element->valueByField($contentField, $block)->value : null;
        ?>
        <input type="checkbox" name="fields[{{$element->id}}][{{$contentField->id}}]"
                {{ old("fields[$element->id][$contentField->id]") &&  old("fields[$element->id][$contentField->id]") == 1 ? ' checked' :
                $fieldValue == 1 ? ' checked' : '' }}>
        @if ($errors->has("fields[$element->id][$contentField->id]"))
            <span class="form-error is-visible">{{ $errors->first("fields[$element->id][$contentField->id]") }}</span>
        @endif
    </label>
</div>