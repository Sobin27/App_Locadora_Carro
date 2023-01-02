<?php

namespace App\Http\Controllers;

use App\Models\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo)
    {
        $this->modelo = $modelo;
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
           return $this->modelo->selectRaw($request->atributos)->with('marca')->get();
        }
        return response()->json($this->modelo->with('marca')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        return response()->json($modelo, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $modelo = $this->modelo->with('marca')->find($id);
        if($modelo === null)
        {
            return response()->json(['error' => 'Recursos pesquisado não existe'], 404);
        }
        return response()->json($modelo, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null)
        {
            return response()->json(['erro' => 'Impossível realizar essa atualização. O recurso solicitado não existe'], 404);
        }
//        if ($request->method() === 'PATCH')
//        {
//            $regrasDinamicas = [];
//
//            foreach($modelo->rules() as $input => $regra) {
//                if (array_key_exists($input, $request->all())) {
//                    $regrasDinamicas[$input] = $regra;
//                }
//            }
//            $request->validate($regrasDinamicas, );
//            $modelo->update($request->all());
//            return response()->json($modelo, 200);
//        }

        //remove a imagem antiga caso tenha um novo arquivo
        if ($request->file('imagem'))
        {
            Storage::disk('public')->delete($modelo->imagem);
        }
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');
        $request->validate($this->modelo->rules());
        $modelo->update([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);
        return response()->json($modelo, 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Modelo  $modelo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $modelo = $this->modelo->find($id);
        if($modelo === null)
        {
            return response()->json(['erro' => 'Impossível realizar essa remoção. O recurso solicitado não existe'], 404);
        }

        //Remove a imagem antiga
        Storage::disk('public')->delete($modelo->imagem);

        $modelo->delete();
        return response()->json(['msg' => 'O modelo deletada com sucesso!'], 200);
    }
}
