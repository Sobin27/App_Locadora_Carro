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
            'imagem' => 'required'
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
            'nome.unique' => 'A marca ja foi registrada'
        ];
    }
}
