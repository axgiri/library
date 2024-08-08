<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**      
     * @var Model      
     */
    protected $model;

    /**      
     * BaseRepository constructor.      
     *      
     * @param Model $model      
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    public function update($id, $attributes)
    {
        return $this->model->where('id', $id)->update($attributes);
    }

    public function getAll(){
        return $this->model->all();
    }

    public function delete($id){
        return $this->model->where('id', $id)->delete();
    }
}
