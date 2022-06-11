<?php

namespace App\Repositories;

interface CategoryRepositoriesInterface
{

    public function existsBy($column, $value);

    public function all($langId);

    public function allWithoutThis($id, $langId);

    public function find($id);

    public function insert($insert);

    public function update($id, $update);

    public function delete($id);

}
