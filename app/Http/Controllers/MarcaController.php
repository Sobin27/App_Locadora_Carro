<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Integer;

class MarcaController extends Controller
{
    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('atributos'))
        {
            return $this->marca->selectRaw($request->atributos)->with('modelos')->get();
        }
        return response()->json($this->marca->with('modelos')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return response()->json($marca, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $marca = $this->marca->with('modelos')->find($id);
        if($marca === null)
        {
            return response()->json(['error' => 'Recursos pesquisado não existe'], 404);
        }
        return response()->json($marca, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        //$marca->update($request->all());
        $marca = $this->marca->find($id);
        if($marca === null)
        {
            return response()->json(['erro' => 'Impossível realizar essa atualização. O recurso solicitado não existe'], 404);
        }
//        if ($request->method() === 'PATCH')
//        {
//            $regrasDinamicas = [];
//
//            foreach($marca->rules() as $input => $regra) {
//                if (array_key_exists($input, $request->all())) {
//                    $regrasDinamicas[$input] = $regra;
//                }
//            }
//            $request->validate($regrasDinamicas, $this->marca->feedback());
//            if ($request->file('imagem'))
//            {
//                Storage::disk('public')->delete($marca->imagem);
//            }
//            $imagem = $request->file('imagem');
//            $imagem_urn = $imagem->store('imagens', 'public');
//
//            $marca->update([
//                    'nome' => $request->nome,
//                    'imagem' => $imagem_urn
//            ]);
//            return response()->json($marca, 200);
//        }

        //remove a imagem antiga caso tenha um novo arquivo
        if ($request->file('imagem'))
        {
            Storage::disk('public')->delete($marca->imagem);
        }
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);
        return response()->json($marca, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Integer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$marca->delete();
        $marca = $this->marca->find($id);
        if($marca === null)
        {
            return response()->json(['erro' => 'Impossível realizar essa remoção. O recurso solicitado não existe'], 404);
        }

        //Remove a imagem antiga
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['msg' => 'Marca deletada com sucesso!'], 200);
    }
}
