<?php

namespace App\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Hadith;
use App\Models\HIndex;
use App\Models\HadithHIndex;

class HadithRepositories implements HadithRepositoriesInterface
{

    public function allIndex($langId)
    {
        return HIndex::where('lang_id', '=', $langId)
        ->whereNull('parent_id')->orderBy('name', 'asc')->get();
    }

    public function insertIndex($insert)
    {
        HIndex::create($insert);
    }

    public function updateIndex($id, $update)
    {
        HIndex::find($id)->update($update);
    }

    public function existsHadithHIndexBy($column, $value)
    {
        return HadithHIndex::where($column, '=', $value)->exists();
    }

    public function existsIndexchildren($value)
    {
        return HIndex::where('parent_id', '=', $value)->exists();
    }

    public function deleteIndex($id)
    {
        $removeItem = HIndex::find($id);
        $removeItem->delete();
    }

    public function paginate($langId, $first, $rows, $filters, $sortField, $sortOrder)
    {
        $hadithesQuery = Hadith::with('children')->with('hindexes:name')
            ->where('lang_id', '=', $langId)->whereNull('parent_id')
        ->orderBy('id', 'desc');

//        if (sizeof($filters) > 0) {
            if (isset($filters['hadith'])) {
                $hadithesQuery->where('hadith_text', 'LIKE', '%' . $filters['hadith']['value'] . '%');
            }
//            if (isset($filters['date'])) {
//                if (isset($filters['date']['value'][0]) && isset($filters['date']['value'][1])) {
//                    $from = Carbon::parse($filters['date']['value'][0])->format('Y-m-d');
//                    $to = Carbon::parse($filters['date']['value'][1])->format('Y-m-d');
//                    $hadithesQuery->whereBetween('articles.created_at', [$from, $to]);
//                }
//            }
//
//            if (isset($filters['category'])) {
//                $categoryId = $filters['category']['value'];
//                $category = Category::select('id', 'name')->where('id', $categoryId)->first();
//                $ids = $this->getChildCategoryIds($category, []);
//
//                $hadithesQuery->whereHas('category', function ($query) use ($categoryId, $ids) {
//                    $query->where('id', $categoryId);
//                    foreach ($ids as $id) {
//                        $query->orWhere('id', $id);
//                    }
//                });
//            }
//        }
//        if ($sortField == 'name') {
//            if (intval($sortOrder) === 1) {
//                $hadithesQuery->orderBy('articles.name', 'asc');
//            } else {
//                $hadithesQuery->orderBy('articles.name', 'desc');
//            }
//        }
//
//        if ($sortField == 'date') {
//            if (intval($sortOrder) === 1) {
//                $hadithesQuery->orderBy('articles.created_at');
//            } else {
//                $hadithesQuery->orderBy('articles.created_at', 'desc');
//            }
//        }
//
//        if ($sortField == null) {
//            $hadithesQuery->orderBy('articles.id', 'desc');
//        }
        $count = $hadithesQuery->count();
//        $hadithesQuery->groupBy("articles.id");

        $hadithes= $hadithesQuery->offset($first)->limit($rows)->get();
        return [
            'data' => $hadithes,
            'total' => $count
        ];
    }

    public function insert($hadith)
    {
        $createdHadith = Hadith::create($hadith);

        if(isset($hadith['children'])){
            if (sizeof($hadith['children']) > 0) {
                foreach ($hadith['children'] as $childHadith) {
                    $childHadith['parent_id'] = $createdHadith->id;
                    $createdChildHadith = Hadith::create($childHadith);
                    $hIndexList = [];
                    foreach ($childHadith["hIndexNodes"] as $hIndexNode) {
                        array_push($hIndexList, ['hadith_id' => $createdChildHadith->id, 'hindex_id' => $hIndexNode['key']]);
                    }
                    HadithHIndex::insert($hIndexList);
                }
            }
        }

        if (sizeof($hadith["hIndexNodes"]) > 0) {
            $hIndexList = [];
            foreach ($hadith["hIndexNodes"] as $hIndexNode) {
                array_push($hIndexList, ['hadith_id' => $createdHadith->id, 'hindex_id' => $hIndexNode['key']]);
            }
            HadithHIndex::insert($hIndexList);
        }
    }

    public function edit($id) {
        Hadith::find($id);
    }

}
