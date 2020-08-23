<?php

namespace App\Domain\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface AbstractRepositoryInterface
{
    public function save(array $attributes): Model;
    public function update(Model $model, array $attributes): Model;
}
