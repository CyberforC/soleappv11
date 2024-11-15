<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::orderBy('name', 'asc')->get();
        return response()->json($genres);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //return response()->json(['request' => 'Solicitud recibida: '.$request]);
        $validated = $request->validate([
            'name' => 'required|max:70|unique:genres'
        ]);

        try
        {
            $genre = new Genre();
            $genre->name = $request->name;
            $genre->description = $request->description;
            $genre->save();
            return response()->json(['status' => true, "message" => 'El genero literario '.$genre->name.' fue creado exitosamente']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, "message" => 'Error al crear el registro. '.$exc]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $genre = Genre::find($id);
        return response()->json($genre);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|max:70|unique:genres,name,'.$id
        ]);

        try
        {
            $genre = Genre::findOrFail($id);
            $genre->name = $request->name;
            $genre->description = $request->description;
            $genre->save();
            return response()->json(['status' => true, 'message' => 'El genre '.$genre->name.' fue actualizado exitosamente']);
            //return response()->json(['request' => 'Solicitud recibida: '.$request.', id: '.$id]);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al editar el registro. '.$exc]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try
        {
            $genre = Genre::findOrFail($id);
            $genre->delete();
            return response()->json(['status' => true, 'message' => 'El género '.$genre->name.' fue eliminado exitosamente']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al eliminar el registro']);
        }
    }
}
