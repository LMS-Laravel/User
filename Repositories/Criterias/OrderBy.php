<?php

namespace Modules\User\Repositories\Criterias;


use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

class OrderBy implements CriteriaInterface
{
    /**
     * @var
     */
    private $by;
    /**
     * @var
     */
    private $order;

    public function __construct($by, $order = 'DESC')
    {

        $this->by = $by;
        $this->order = $order;
    }

    public function apply($model, RepositoryInterface $repository)
    {
        $model = $model->orderBy($this->by, $this->order);
        return $model;
    }

}