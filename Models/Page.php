<?php

namespace Laragento\Cms\Models;

use Illuminate\Database\Eloquent\Model;
use Laragento\Customer\Models\Customer;

class Page extends Model
{
    protected $table = 'lg_cms_pages';

    protected $hidden = ['blocktypes'];

    protected $fillable = [
        'title',
        'slug',
        'url_path',
        'user_id',
        'is_active'
    ];

    protected static function boot() {
        parent::boot();

        static::deleting(function($page) {
            $page->blocks()->delete();
        });
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public function blockTypes()
    {
        return $this->belongsToMany(BlockType::class,'lg_cms_page_block_type');
    }

    public function user()
    {
        return $this->belongsTo(Customer::class, 'user_id','entity_id');
    }
}
