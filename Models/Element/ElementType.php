<?php

namespace Laragento\Cms\Models\Element;

use Illuminate\Database\Eloquent\Model;
use Laragento\Cms\Models\BlockType;

class ElementType extends Model
{
    protected $table = 'lg_cms_element_types';

    public function elements()
    {
        return $this->hasMany(Element::class);
    }

    public function elementFields()
    {
        return $this->hasMany(ElementField::class);
    }
}
