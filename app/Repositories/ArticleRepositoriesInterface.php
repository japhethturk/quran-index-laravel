<?php

namespace App\Repositories;

interface ArticleRepositoriesInterface
{

    public function existsBy($column, $value);

    public function paginate($langId, $first, $rows, $filters, $sortField, $sortOrder);

    public function insert($insert);

    public function find($id);

    public function update($id, $update);

}
