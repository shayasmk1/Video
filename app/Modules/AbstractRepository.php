<?php namespace App\Modules;

use App\Modules\ModelRepositoryInterface;
use App\Modules\Model;


/**
 * Class AbstractRepository
 * @package App\Repositories
 */
abstract class AbstractRepository implements ModelRepositoryInterface
{
    /**
     * The model to execute queries on.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    protected $orderBy = self::order_by;
    protected $orderDir = self::order_dir;
    protected $limit = self::limit;
    protected $max_limit = 100;

    /**
     * Create a new repository instance.
     *
     * @param \Illuminate\Database\Eloquent\Model $model The model to execute queries on
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get total records
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->model->count();
    }

    /**
     * Get a new instance of the model.
     *
     * @param  array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getNew(array $attributes = array())
    {
        return $this->model->newInstance($attributes);
    }

    public function findWithRelations($id, $relations = array())
    {
        $query = $this->model
            ->where('id', $id)
            ->with($relations);
        return $query->first();
    }

    /**
     * @param array $relations
     * @param null $orderColumn
     * @param null $orderDir
     *
     * @return mixed
     */
    public function findAllWithRelations($relations = array(),
                                         $orderColumn = null, $orderDir = null)
    {
        $models = $this->model
            ->with($relations)
            ->order($orderColumn, $orderDir)
            ->get();

        return $models;
    }

    /**
     * @param array $relations
     * @param null $orderColumn
     * @param null $orderDir
     *
     * @return mixed
     */
    public function findAllWithPaginateAndRelations($relations = array(),
                                                    $orderColumn = null, $orderDir = null,
                                                    $perPage = null, array $wheres = [])
    {
        if (!is_null($perPage)) {

            $models = $this->model
                ->with($relations)->where(function ($query) use ($wheres) {
                    foreach ($wheres as $where) {
                        if (is_array($where)) {

                            if (sizeof($where) == 3) {
                                $query->where($where[0], $where[1], $where[2]);
                            }

                            if (sizeof($where) == 2) {
                                $query->where($where[0], "=", $where[1]);
                            }

                        }
                    }
                })->order($orderColumn, $orderDir)->paginate($perPage);

        } else {
            $models = $this->model
                ->with($relations)->where(function ($query) use ($wheres) {
                    foreach ($wheres as $where) {
                        if (is_array($where)) {

                            if (sizeof($where) == 3) {
                                $query->where($where[0], $where[1], $where[2]);
                            }

                            if (sizeof($where) == 2) {
                                $query->where($where[0], "=", $where[1]);
                            }

                        }
                    }
                })->order($orderColumn, $orderDir)->get();

        }

        return $models;
    }

    /**
     * @param array $relations
     * @param null $orderColumn
     * @param null $orderDir
     *
     * @return mixed
     */
    public function findWithWheresAndRelations($relations = array(),
                                               $orderColumn = null, $orderDir = null, $perPage = null, array $wheres = [])
    {
        if (!is_null($perPage)) {

            $models = $this->model
                ->with($relations)->where(function ($query) use ($wheres) {
                    foreach ($wheres as $where) {
                        if (is_array($where)) {

                            if (sizeof($where) == 3) {
                                $query->where($where[0], $where[1], $where[2]);
                            }

                            if (sizeof($where) == 2) {
                                $query->where($where[0], "=", $where[2]);
                            }

                        }
                    }
                })->order($orderColumn, $orderDir)->paginate($perPage);

        } else {
            $models = $this->model
                ->with($relations)->where(function ($query) use ($wheres) {
                    foreach ($wheres as $where) {
                        if (is_array($where)) {

                            if (sizeof($where) == 3) {
                                $query->where($where[0], $where[1], $where[2]);
                            }

                            if (sizeof($where) == 2) {
                                $query->where($where[0], "=", $where[2]);
                            }

                        }
                    }
                })->order($orderColumn, $orderDir)->first();

        }

        return $models;
    }

    /**
     * {@inheritDoc}
     */
    public function findAll($orderColumn = null, $orderDir = null)
    {
        $models = $this->model
            ->order($orderColumn, $orderDir)
            ->get();

        return $models;
    }


    /**
     * {@inheritDoc}
     */
    public function search($filters = [], $relations = array(), $page = false,
                           $limit = self::limit, $sorting = [self::order_by => self::order_dir])
    {
        $limit = min(self::max_limit, $limit);
        $query = $this->model->with($relations);
        foreach ($this->model->getFilters() as $filter) {
            if (!empty($filters[$filter])) {
                $withFilter = "with" . ucfirst($filter);
                $query = $query->$withFilter($filters[$filter]);
            }
        }

        return $this->getQueryResult($query, $page, $limit, $sorting);
    }

    /**
     * {@inheritDoc}
     */
    public function create($attributes = array())
    {
        return $this->model->create($attributes);
    }

    /**
     * {@inheritDoc}
     */
    public function update(Model $model, $attributes = array())
    {
        return $model->update($attributes);
    }


    public function createOrUpdate($attributes = [])
    {
        $model = null;

        if (!$attributes) {
            return $model;
        }

        if (isset($attributes['id'])) {
            $model = $this->model->find($attributes['id']);
           
            $this->update($model, $attributes);
         
            return $model;
        }

        return $this->create($attributes);
    }
    public function findOrCreate($attributes = [])
    {
        $model = null;

        if (!$attributes) {
            return $model;
        }

        $record = $this->model->where($attributes)->first();
        if ($record) 
        {
            return $record;
        }
        else 
        {
            return $this->create($attributes);
        }

        
    }

    /**
     * {@inheritDoc}
     */
    public function findOrNotFound($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * {@inheritDoc}
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Model $model)
    {
        return $model->delete();
    }

    public function getQueryResult($query, $page, $limit, $sorting)
    {
        if (is_array($sorting) && !empty($sorting)) {
            $query = $query->order(key($sorting), reset($sorting));
        }

        if ($page) {
            return $query->paginate($limit);
        }
        return $query->take($limit)->get();
    }
}