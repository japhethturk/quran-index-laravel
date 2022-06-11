<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Article;
use App\Models\Category;

class ArticleRepositories implements ArticleRepositoriesInterface
{

    public function existsBy($column, $value)
    {
        return Article::where($column, '=', $value)->exists();
    }


    public function paginate($langId, $first, $rows, $filters, $sortField, $sortOrder)
    {
        $articlesQuery = Article::select('articles.id', 'articles.name', 'articles.slug', 'articles.image', 'articles.category_id', 'articles.category_id', 'articles.created_at')
        ->with('category:id,name,slug')
        ->where('lang_id', '=', $langId);

        if (sizeof($filters) > 0) {
            if (isset($filters['name'])) {
                $articlesQuery->where('articles.name', 'LIKE', '%' . $filters['name']['value'] . '%');
            }
            if (isset($filters['date'])) {
                if (isset($filters['date']['value'][0]) && isset($filters['date']['value'][1])) {
                    $from = Carbon::parse($filters['date']['value'][0])->format('Y-m-d');
                    $to = Carbon::parse($filters['date']['value'][1])->format('Y-m-d');
                    $articlesQuery->whereBetween('articles.created_at', [$from, $to]);
                }
            }

            if (isset($filters['category'])) {
                $categoryId = $filters['category']['value'];
                $category = Category::select('id', 'name')->where('id', $categoryId)->first();
                $ids = $this->getChildCategoryIds($category, []);

                $articlesQuery->whereHas('category', function ($query) use ($categoryId, $ids) {
                    $query->where('id', $categoryId);
                    foreach ($ids as $id) {
                        $query->orWhere('id', $id);
                    }
                });
            }
        }
        if ($sortField == 'name') {
            if (intval($sortOrder) === 1) {
                $articlesQuery->orderBy('articles.name', 'asc');
            } else {
                $articlesQuery->orderBy('articles.name', 'desc');
            }
        }

        if ($sortField == 'date') {
            if (intval($sortOrder) === 1) {
                $articlesQuery->orderBy('articles.created_at');
            } else {
                $articlesQuery->orderBy('articles.created_at', 'desc');
            }
        }

        if ($sortField == null) {
            $articlesQuery->orderBy('articles.id', 'desc');
        }

        $count = $articlesQuery->count();
        $articlesQuery->groupBy("articles.id");

        if ($sortField == 'view') {
            if (intval($sortOrder) === 1) {
                $articlesQuery->orderBy('view', 'asc');
            } else {
                $articlesQuery->orderBy('view', 'desc');
            }
        }
        $products = $articlesQuery->offset($first)->limit($rows)->get();
        return [
            'data' => $products,
            'total' => $count
        ];
    }

    public function insert($insert)
    {
        return Article::create($insert);
    }

    public function find($id)
    {
        return Article::find($id);
    }

    public function update($id, $update)
    {
        $post = Article::find($id);
        $old_image = $post->image;
        $post->update($update);
        return $old_image;
    }


    private function getChildCategoryIds($category, $idArray){
        array_push($idArray, $category->id);
        if ($category->children !== null) {
            foreach ($category->children as $child) {
                $idArray = $this->getChildCategoryIds($child, $idArray);
            }
        }
        return $idArray;
    }

}
