<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Arr;

abstract class BaseRepository
{
    protected $entity;

    public function __construct($entity)
    {
        $this->entity = $entity;
    }

    /**
     *
     * General function to handle select cases
     *
     * @param select Array Pass array of columns to be selected
     * @param with String or Array of Relations to be selectd
     * @param withCount String or Array of Related count to be selected
     * @param join String or Array of table to be joined
     * eg $join = 'countries,countries.id,=,salary_ranges.country_id'
     * @param id return a single record
     * @param where key value array to add condition to query
     * @param order_by sorting on column name
     * @param order asc or desc
     * @param per_page if passed will return paginated response or all records
     *
     */
    public function getByParams($params = [])
    {

        $records = [];

        $query = $this->entity::whereRaw('1=1');
        if (isset($params['select'])) {
            $query->select($params['select']);
        }

        // add relation to main object
        if (isset($params['with'])) {
            $withes = Arr::wrap($params['with']);
            foreach ($withes as $with) {
                $query->with($with);
            }
        }

        if (isset($params['withCount'])) {

            $withCounts = Arr::wrap($params['withCount']);
            foreach ($withCounts as $withCount) {
                $query->withCount($withCount);
            }
        }

        // add joins to main object
        if (isset($params['join'])) {
            $joins = Arr::wrap($params['join']);
            foreach ($joins as $join) {
                $parts = explode(',', $join);
                $query->leftJoin($parts[0], $parts[1], $parts[2], $parts[3]);
            }
        }

        // add joins to main object
        if (isset($params['leftjoin'])) {
            $joins = Arr::wrap($params['leftjoin']);
            foreach ($joins as $join) {
                $parts = explode(',', $join);
                $query->leftjoin($parts[0], $parts[1], $parts[2], $parts[3]);
            }
        }
        // return if single object is needed
        if (isset($params['id'])) {
            $query->where('id', $params['id']);
            $records = $query->first();
            return $records;
        }

        if (isset($params['where'])) {

            foreach ($params['where'] as $key => $value) {
                $query->where($key, $value);
            }
        }
        if (isset($params['between'])) {

            foreach ($params['between'] as $key => $value) {
                $query->whereBetween($key, $value);
            }
        }
        if (isset($params['whereNot'])) {
            foreach ($params['whereNot'] as $key => $value) {
                $query->where($key, '!=', $value);
            }
        }

        if (isset($params['in'])) {
            foreach ($params['in'] as $key => $value) {
                $query->whereIn($key, $value);
            }
        }

        if (isset($params['not_in'])) {
            foreach ($params['not_in'] as $key => $value) {
                $query->whereNotIn($key, $value);
            }
        }

        if (isset($params['whereNull'])) {
            $query->whereNull($params['whereNull']);
        }

        if (isset($params['whereNotNull'])) {
            $query->whereNotNull($params['whereNotNull']);
        }

        if (isset($params['like'])) {

            foreach ($params['like'] as $key => $value) {
                $query->where($key, 'like', $value);
            }
        }

        if (isset($params['scope'])) {

            foreach ($params['scope'] as $key => $value) {
                $query->$value();
            }
        }

        if (isset($params['ofType'])) {

            foreach ($params['ofType'] as $key => $value) {
                $query->ofType($value);
            }
        }

        if (isset($params['date_range'])) {
            $index = 0;
            foreach ($params['date_range'] as $key => $value) {
                if ($index == '1') {
                    $query->orWhereBetween($key, $value);
                } else {
                    $query->whereBetween($key, $value);
                }
                $index++;
            }
        }

        if (isset($params['count'])) {
            $records = $query->count();
            return $records;
        }

        if (isset($params['order_by'])) {

            $order = isset($params['order']) ? $params['order'] : 'asc';
            $query->orderBy($params['order_by'], $order);
        }

        if (isset($params['return'])) {
            if ($params['return'] == 'qb') {
                return $query;
            }
        }
        if (isset($params['per_page']) && is_numeric($params['per_page'])) {
            $records = $query->paginate($params['per_page']);
        } else {
            $records = $query->get();
        }

        return $records;
    }

    public function delete($id)
    {
        if (!is_numeric($id))
            abort(500);

        $entity = $this->entity->find($id);
        if (!$entity)
            abort(404);

        try {
            $entity->delete();
            return true;
        } catch (Exception $e) {
            dd($e->getMessage());
            return false;
        }
    }
    public function save($params)
    {
        if (isset($params['id'])) {
            $entity = $this->entity->where(['id' => $params['id']])->first();
            $entity->update($params);
            return $entity;
        } else {
            $entity = $this->entity->create($params);
            return $entity;
        }
    }

    public function saveMany($params)
    {
        if (isset($params['id'])) {
            $entityParams = collect($params)->except('id')->toArray();
            $entity = $this->entity->whereIn('id', $params['id'])->update($entityParams);
            return $entity;
        } else {
            $entity = $this->entity->create($params);
            return $entity;
        }
    }

    public function getById($id)
    {

        return $this->entity->find($id);
    }
}
