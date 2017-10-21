<?php namespace App\Modules;

use App\Modules\Model;

interface ModelRepositoryInterface
{
    const limit = 10;
    const max_limit = 100;

    const order_by = 'created_at';
    const order_dir = 'desc';

    public function findAll($orderColumn = null, $orderDir = null);

    public function search($filters = [], $relations = array(), $page = false,
                           $limit = self::limit, $sorting=[self::order_by=>self::order_dir]);

    public function create($attributes = []);

    public function update(Model $model, $attributes = []);

    public function createOrUpdate($attributes = []);

    public function findOrNotFound($id);

    public function find($id);

    public function remove(Model $model);

    public function findWithRelations($id, $relations = array());

    public function findWithWheresAndRelations($relations = array(),
                                               $orderColumn = null, $orderDir = null, $perPage = null,array $wheres = []);

    public function getModel();

    public function getTotal();

    public function getNew();

    public function findAllWithPaginateAndRelations($relations = array(),
                                                    $orderColumn = null, $orderDir = null, $perPage = null,array $wheres = []);

    public function findAllWithRelations($relations = array(),
                                         $orderColumn = null, $orderDir = null);

    function getQueryResult($query, $page, $limit, $sorting);

}