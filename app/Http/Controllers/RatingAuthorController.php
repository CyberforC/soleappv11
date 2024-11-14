<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class RatingAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'number_star' => 'required|integer',
            'author.id' => 'required|integer|exists:authors,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try
        {
            $author = Author::findOrFail($request->author['id']);
            $author->users()->attach($request->user['id'], ['number_star' => $request->number_star]);
            return response()->json(['status' => true, 'message' => 'La puntuación del autor '.$author->full_name.' fue creada exitosamente']);
        }
        catch (\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al crear el registro. '.$exc]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $author = Author::findOrFail($id);
        return response()->json(['author' => $author, "rating" => $author->users()->where('user_id', '=', auth()->user()->id)->select('userables.*')->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'number_star' => 'required|integer',
            'author.id' => 'required|integer|exists:authors,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try
        {
            $author = Author::findOrFail($request->author['id']);
            $author->users()->updateExistingPivot($request->user['id'], ['number_star' => $request->number_star]);
            return response()->json(['status' => true, 'message' => 'La puntuación del autor '.$author->full_name.' fue actualizada exitosamente']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al actualizar el registro. '.$exc]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
