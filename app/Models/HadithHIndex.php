<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HadithHIndex extends Model
{
    use HasFactory;

    protected $table = 'hadithes_hindexes';

    public $timestamps = false;
    
    protected $guarded = ['id'];

}
