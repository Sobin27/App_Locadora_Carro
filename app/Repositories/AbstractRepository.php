<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function selectAtribrutosRegistrosRelacionados(string $atributos)
    {
        $this->model->with($atributos);
    }

    public function filtros($filtros)
    {
        $filtro = explode(';', $filtros);

        foreach ($filtro as $key => $condicao)
        {
            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0],$c[1],$c[2]);
        }
    }

    public function selectAtributos($atributos)
    {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado()
    {
        return $this->model->get();
    }

    public function getResultadosPagination($perPage)
    {
        return $this->model->paginate($perPage);
    }
}
