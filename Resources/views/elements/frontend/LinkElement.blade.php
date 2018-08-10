<div>
    <?php
    $urlValue = $element->valueByField( $element->fieldByTitle('link_url'), $block)->value;
    $titleValue = $element->valueByField( $element->fieldByTitle('link_title'), $block)->value;
    $blankValue = $element->valueByField( $element->fieldByTitle('open_blank'), $block)->value; ?>
    @if($titleValue && $urlValue)
        <a href="{{$urlValue}}" {{ $blankValue && $blankValue == 1 ? ' target="_blank"' : ''}}>{{$titleValue}}</a>
    @endif
</div>