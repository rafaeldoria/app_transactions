<?php

namespace App\Repositories;

use App\Repositories\Interfaces\IBaseRepository;

class BaseRepository implements IBaseRepository
{
    public function index(){}
    public function show(int $entityId){}
    public function store(array $data){}
    public function update($model, array $data){}
    public function destroy($model){}
}
