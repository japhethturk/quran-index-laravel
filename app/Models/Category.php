<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }
    
    public function parent()
    {
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
    
}
