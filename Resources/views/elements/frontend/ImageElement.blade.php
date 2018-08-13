<div>
    <?php
    $srcValue = $element->valueByField( $element->fieldByTitle('path'), $block)->value;
    $nameValue = $element->valueByField( $element->fieldByTitle('name'), $block)->value;
    $altValue = $element->valueByField( $element->fieldByTitle('alt'), $block)->value;
    $widthValue = $element->valueByField( $element->fieldByTitle('width'), $block)->value;
    $heightValue = $element->valueByField( $element->fieldByTitle('height'), $block)->value;
    @if($srcValue)
        <img src="{{$srcValue}}" alt="{{$altValue}}" name="{{$nameValue}}" width="{{$widthValue}}" height="{{$heightValue}}"/>
    @endif
</div>