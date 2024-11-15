<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class RatingBookController extends Controller
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
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try
        {
            $book = Book::findOrFail($request->book['id']);
            $book->users()->attach($request->user['id'], ['number_star' => $request->number_star]);
            return response()->json(['status' => true, 'message' => 'La puntuación del libro '.$book->title.' fue creado exitosamente']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al crear el registro. '.$exc]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::findOrFail($id);
        return response()->json(['book' => $book, 'rating' => $book->users()->where('user_id', '=', auth()->user()->id)->select('userables.*')->get()]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'number_star' => 'required|integer',
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try
        {
            $book = Book::findOrFail($request->book['id']);
            $book->users()->updateExistingPivot($request->user['id'], ['number_star' => $request->number_star]);
            return response()->json(['status' => true, 'message' => 'La puntuación del libro '.$book->title.' fue creada exitosamente']);
        }
        catch (\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al crear el registro. '.$exc]);
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
