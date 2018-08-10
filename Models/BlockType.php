<?php

namespace Laragento\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementField;
use Laragento\Cms\Models\Element\ElementType;

class BlockType extends Model
{
    protected $table = 'lg_cms_block_types';
    protected $fillable = [
        'title',
    ];
    public $timestamps = false;

    public function pages()
    {
        return $this->belongsToMany(Page::class,'lg_cms_page_block_type');
    }

    public function blocks()
    {
        return $this->hasMany(Block::class,'type_id');
    }

    public function elements()
    {
        return $this->hasMany(Element::class);
    }
}
