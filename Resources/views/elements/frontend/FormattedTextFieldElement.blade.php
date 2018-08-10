<div class="formatted-text-field-element">
    <?php $contentField = $element->fieldByTitle('content');
    $fieldValue = $element->valueByField($contentField, $block)->value; ?>
    @if($fieldValue)
        <div>{!! nl2br($fieldValue) !!}</div>
    @endif
</div>