<?php

namespace App\Domain\Repositories\Eloquent;

use App\Domain\Repositories\Contracts\AbstractRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements AbstractRepositoryInterface
{
    protected $model;

    public function save(array $attributes): Model
    {
        $model = new $this->model();
        $model->fill($attributes);
        $model->save();

        return $model;
    }

    public function update(Model $model, array $attributes): Model
    {
        $model->update($attributes);

        return $model;
    }
}
