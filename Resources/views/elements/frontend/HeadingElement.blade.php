<div class="heading-element">
    <?php
    $hValue = !empty($block) ? $element->valueByField($element->fieldByTitle('format'), $block) : null;
    $contentValue = !empty($block) ? $element->valueByField($element->fieldByTitle('content'), $block) : null;
    ?>
    @if($contentValue)
        <h{{$hValue ? $hValue->value : '2'}}>
            {{$contentValue->value}}
        </h{{$hValue ? $hValue->value : '2'}}>
    @endif
</div>