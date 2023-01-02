<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules()
    {
        return [
            'nome' => 'required|string|unique:marcas,nome,'.$this->id.'',
            'imagem' => 'required|file|mimes:png,jpeg'
        ];

        /*
         * 1) Tabela
         * 2) nome da coluna pesquisada
         * 3) id do registo que serpa desconsiderado
         * */
    }

    public function feedback()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'A marca ja foi registrada',
            'imagem.mimes' => 'O arquivo deve ser do tipo PNG'
        ];
    }

    public function modelos()
    {
        return $this->hasMany('App\Models\Modelo');
    }
}
