<?php

namespace App\Repositories;

interface HadithRepositoriesInterface
{

    public function allIndex($langId);

    public function insertIndex($insert);

    public function updateIndex($id, $update);

    public function existsHadithHIndexBy($column, $value);

    public function existsIndexChildren($value);

    public function deleteIndex($id);

    public function paginate($langId, $first, $rows, $filters, $sortField, $sortOrder);

    public function insert($hadith);

    public function edit($id);

}
