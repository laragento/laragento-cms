<?php

namespace Laragento\Cms\Models\Element;

use Illuminate\Database\Eloquent\Model;

class ElementField extends Model
{
    protected $table = 'lg_cms_element_fields';

    protected $guarded = [];

    public function elementType()
    {
        return $this->belongsTo(ElementType::class);
    }
}
