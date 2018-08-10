<?php

namespace Laragento\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Laragento\Cms\Models\Element\Element;
use Laragento\Cms\Models\Element\ElementField;
use Laragento\Cms\Models\Element\ElementFieldValue;

class Block extends Model
{
    protected $table = 'lg_cms_blocks';

    protected $fillable = [
        'title',
        'classes',
        'page_id',
        'type_id',
        'sortnr'
    ];

    /*protected static function boot() {
        parent::boot();

        static::deleting(function($block) {
            $block->link()->delete();
            $block->heading()->delete();
            $block->text()->delete();
            $block->files()->delete();
        });
    }

    public function link()
    {
        return $this->hasOne(ElementLink::class);
    }

    public function files()
    {
        return $this->hasMany(ElementFile::class);
    }

    public function heading()
    {
        return $this->hasOne(ElementTitle::class);
    }

    public function text()
    {
        return $this->hasOne(ElementText::class);
    }*/

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function blockType()
    {
        return $this->belongsTo(BlockType::class, 'type_id');
    }

    public function values()
    {
        return $this->hasMany(ElementFieldValue::class);
    }


    public function owner()
    {
        return $this->page->user;
    }

    public function elements()
    {
        return $this->blockType->elements()->orderBy('sort_nr')->get();
    }
}
