<div class="image-element">
    <?php
    $srcValue = $element->valueByField( $element->fieldByTitle('path'), $block);
    $nameValue = $element->valueByField( $element->fieldByTitle('name'), $block);
    $altValue = $element->valueByField( $element->fieldByTitle('alt_txt'), $block);
    $widthValue = $element->valueByField( $element->fieldByTitle('width'), $block);
    $heightValue = $element->valueByField( $element->fieldByTitle('height'), $block);
    ?>
    @if($srcValue)
        <img
                src="/storage/cms/block/images{{$srcValue->value}}"
                alt="{{$altValue ? $altValue->value : ''}}"
                @if($nameValue) name="{{$nameValue->value}}" @endif
                @if($widthValue) width="{{$widthValue->value}}" @endif
                @if($heightValue) height="{{$heightValue->value}}" @endif
        />
    @endif
</div>