<div class="text-line-element">
    <?php $contentField = $element->fieldByTitle('content');
    $fieldValue = $element->valueByField($contentField, $block); ?>
    @if($fieldValue)
        <p>{{$fieldValue->value}}</p>
    @endif
</div>