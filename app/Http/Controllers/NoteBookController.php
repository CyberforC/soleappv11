<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $book = Book::findOrFail($id);
        return response()->json(['book' => $book, 'notes' => $book->note()->where('user_id', '=', auth()->user()->id)->get()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|min:5',
            'writing_date' => 'date|date_format:Y-m-d',
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try
        {
            $book = Book::findOrFail($request->book['id']);
            $book->note()->create(['description' => $request->description, 'writing_date' => $request->writing_date, 'user_id' => $request->user['id']]);
            return response()->json(['status' => true, 'message' => 'La nota del libro '.$book->title.' fue creada exitosamente']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al crear el registro. '.$exc]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $note = Note::find($id);
        /*$this->authorize('MyNote', $note);*/
        return response()->json($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'description' => 'required|min:5',
            'writing_date' => 'date|date_format:Y-m-d',
            'book.id' => 'required|integer|exists:books,id',
            'user.id' => 'required|integer|exists:users,id'
        ]);

        try
        {
            $note = Note::findOrFail($id);
            $note->description = $request->description;
            $note->writing_date = $request->writing_date;
            $note->save();
            //return response()->json(['status' => true, 'message' => 'La nota del libro '.$request->book['title'].' fue actualizado exitosamente']);
            return response()->json(['status' => true, 'message' => 'La nota del libro '.$request->book['id'].' fue actualizada exitosamente']);
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
            $note = Note::findOrFail($id);
            $note->delete();
            return response()->json(['status' => true, 'message' => 'La nota fue eliminada exitosamente']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al eliminar el registro. '.$exc]);
        }
    }
}
