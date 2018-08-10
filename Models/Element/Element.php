<?php

namespace Laragento\Cms\Models\Element;

use Illuminate\Database\Eloquent\Model;
use Laragento\Cms\Models\BlockType;

class Element extends Model
{
    protected $table = 'lg_cms_elements';

    protected $fillable = [
        'title',
        'sort_nr',
        'element_type_id',
        'block_type_id'
    ];

    public function blockType()
    {
        return $this->belongsTo(BlockType::class);
    }

    public function values()
    {
        return $this->hasMany(ElementFieldValue::class);
    }

    public function elementType()
    {
        return $this->belongsTo(ElementType::class);
    }

    public function fields()
    {
        return $this->elementType->elementFields;
    }

    public function fieldByTitle($title)
    {
        return $this->elementType->elementFields()->where('title',$title)->where('element_type_id',$this->elementType->id)->first();
    }

    public function valueByField($field,$block)
    {
        return $this->values()
            ->where('element_field_id', $field->id)
            ->where('block_id',$block->id)
            ->first();
    }
}
