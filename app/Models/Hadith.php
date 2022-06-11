<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hadith extends Model
{
    use HasFactory;

    protected $table = 'hadithes';

    public $timestamps = false;

    protected $guarded = ['id'];

    public function index()
    {
        return $this->belongsTo(HadithHIndex::class);
    }

//    public function hindexes()
//    {
//        return $this->belongsToMany(HIndex::class, 'hadithes_hindexes');
//    }

    public function hindexes()
    {
        return $this->belongsToMany(HIndex::class, 'hadithes_hindexes', 'hadith_id','hindex_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\Hadith', 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Hadith', 'parent_id');
    }

}
