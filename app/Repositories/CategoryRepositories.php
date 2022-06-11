<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\Category;

class CategoryRepositories implements CategoryRepositoriesInterface
{


    public function existsBy($column, $value)
    {
        return Category::where($column, '=', $value)->exists();
    }

    public function all($langId)
    {
        return Category::select('id', 'name', 'slug', 'publish', 'created_at')
        ->whereNull('parent_id')->orderBy('created_at', 'desc')
        ->where('lang_id', '=', $langId)
        ->get();
    }


    public function allWithoutThis($id, $langId)
    {
        return Category::select('id', 'name', 'slug', 'publish')
        ->where('id', '!=', intval($id))->whereNull('parent_id')
        ->orderBy('created_at', 'desc')
        ->where('lang_id', '=', $langId)
        ->get();
    }

    public function find($id)
    {
        return Category::find($id);
    }

    public function insert($insert)
    {
        Category::create($insert);
    }

    public function update($id, $update)
    {
        Category::find($id)->update($update);
    }

    public function delete($id)
    {
        $removeItem = Category::find($id);
        $removeItem->delete();
    }


}
