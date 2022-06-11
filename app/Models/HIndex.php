<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HIndex extends Model
{
    use HasFactory;

    protected $table = 'hindexes';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function children()
    {
        return $this->hasMany('App\Models\HIndex', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\HIndex', 'parent_id');
    }

    public function hadithes()
    {
        return $this->belongsToMany(Hadith::class, 'hadithes_hindexes');
    }

}
