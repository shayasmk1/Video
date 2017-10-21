<?php namespace App\Modules;

use Illuminate\Database\Eloquent\Model as Eloquent;

/**
 * Class Model
 * @package App\Models
 */
 abstract class Model extends Eloquent
{
    protected $filters = ['name'];

    /**
     * Order recipes
     *
     * @param $query
     * @param $orderBy
     * @param $orderDir
     *
     * @return mixed
     */
    public function scopeOrder($query, $orderBy, $orderDir)
    {
        if (null === $orderBy) {
            return $query;
        }

        return $query->orderBy($orderBy, $orderDir);
    }

    /**
     * Skip
     *
     * @param $query
     * @param $offset
     *
     * @return mixed
     */
    public function scopeOffset($query, $offset)
    {
        if (null === $offset) {
            return $query;
        }

        return $query->skip(intval($offset));
    }

    /**
     * Take
     *
     * @param $query
     * @param $limit
     *
     * @return mixed
     */
    public function scopeLimit($query, $limit)
    {
        if (null === $limit) {
            return $query;
        }

        return $query->take(intval($limit));
    }

    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Search by name
     *
     * @param $query
     * @param $name
     *
     * @return mixed
     */
    public function scopeWithName($query, $value)
    {
        if (in_array('name', $this->fillable)) {
            return $query->orWhere('name', 'like', "%{$value}%");
        }
        return $query;

    }

    public function scopeWithText($query, $value)
    {
        if (!empty($this->fillable['text'])) {
            return $query->orWhere('text', 'like', "%{$value}%");
        }
        return $query;
    }

    public function scopeWithSlug($query, $value)
    {
        if (!empty($this->fillable['slug'])) {
            return $query->orWhere('slug', 'like', $value);
        }
        return $query;
    }

     public function isValid(){
         return true;
     }
} 