<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['genre', 'publisher', 'authors'])->orderBy('title', 'asc')->get();
        $count = 0;
        foreach($books as $book)
        {
            $books[$count]->ratings = $book->users()->select('userables.*')->get();
            $count++;
        }
        return response()->json($books);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:75|unique:books',
            'subtitle' => 'min:3|max:250',
            'language' => 'min:3|max:35',
            'page' => 'integer|digits_between:2,4',
            'published' => 'date|date_format:Y-m-d',
            'genre_id' => 'required|integer|exists:genres,id',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'authors' => 'required|array',
            'image' => 'nullable|sometimes|image'
        ]);

        try
        {
            $book = new Book();
            $book->title = $request->title;
            $book->subtitle = $request->subtitle;
            $book->language = $request->language;
            $book->page = $request->page;
            $book->published = $request->published;
            $book->description = $request->description;
            $book->genre_id = $request->genre_id;
            $book->publisher_id = $request->publisher_id;
            $book->save();
            foreach($request->authors as $author)
            {
                $book->authors()->attach($author);
            }
            $image_name = $this->loadImage($request);
            if($image_name != '')
            {
                $book->image()->create(['url' => 'images/'.$image_name]);
            }
            return response()->json(['status' => true, 'message' => 'El libro '.$book->title.' fue creado exitosamente']);
            //return $request;
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
        $book = Book::with(['genre', 'publisher', 'authors'])->where('id', '=', $id)->first();
        $image = null;
        if($book->image)
        {
            $image = Storage::url($book->image['url']);
        }
        return response()->json(['book' => $book, 'image' => $image]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|max:75|unique:books,title,'.$id,
            'subtitle' => 'min:3|max:250',
            'language' => 'min:3|max:35',
            'page' => 'integer|digits_between:2,4',
            'published' => 'date|date_format:Y-m-d',
            'genre_id' => 'required|integer|exists:genres,id',
            'publisher_id' => 'required|integer|exists:publishers,id',
            'authors' => 'required|array',
            'image' => 'nullable|sometimes|image'
        ]);

        try
        {
            $book = Book::findOrFail($id);
            $book->title = $request->title;
            $book->subtitle = $request->subtitle;
            $book->language = $request->language;
            $book->page = $request->page;
            $book->published = $request->published;
            $book->description = $request->description;
            $book->genre_id = $request->genre_id;
            $book->publisher_id = $request->publisher_id;
            $book->save();
            $book->authors()->detach();
            foreach($request->authors as $author)
            {
                $book->authors()->attach($author);
            }
            $image_name = $this->loadImage($request);
            if($image_name != '')
            {
                if($book->image != null)
                {
                    $book->image()->update(['url' => 'images/'.$image_name]);
                }
                else
                {
                    $book->image()->create(['url' => 'images/'.$image_name]);
                }
            }
            return response()->json(['status' => true, 'message' => 'El libro '.$book->title.' fue actualizado exitosamente']);
        }
        catch (\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al crear el registro. '.$exc]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try
        {
            $book = Book::findOrFail($id);
            $book->delete();
            return response()->json(['status' => true, 'message' => 'El libro '.$book->title.' fue eliminado exitosamente']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al eliminar el registro. '.$exc]);
        }
    }

    public function loadImage($request)
    {
        $image_name = '';
        if ($request->hasFile('image'))
        {
            $destination_path = 'public/images';
            $image = $request->file('image');
            $image_name = time().'_'.$image->getClientOriginalName();
            $request->file('image')->storeAs($destination_path, $image_name);
        }
        return $image_name;
    }
}
