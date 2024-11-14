<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Author;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $count = 0;
        $authors = Author::orderBy('full_name', 'asc')->get();
        foreach($authors as $author)
        {
            $authors[$count]->ratings = $author->users()->select('userables.*')->get();
            $count++;
        }
        return response()->json($authors);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|max:75|unique:authors,full_name',
            'birth_date' => 'date|date_format:Y-m-d',
            'country' => 'max:75',
            'image' => 'nullable|sometimes|image'
        ]);

        try
        {
            $author = new Author();
            $author->full_name = $request->full_name;
            $author->birth_date = $request->birth_date;
            $author->country = $request->country;
            $author->save();
            $image_name = $this->loadImage($request);
            if ($image_name != '')
            {
                $author->image()->create(['url' => 'images/'.$image_name]);
            }
            return response()->json(['status' => true, 'message' => 'El autor '.$author->full_name.' fue creado exitosamente.']);
        }
        catch(\Exception $exc)
        {
            return response()->json(['status' => false, 'meesage' => 'Error al crear el registro']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //$author = Author::find($id);
        $author = Author::with(['profile'])->where('id', '=', $id)->first();
        $image = null;
        if ($author->image)
        {
            $image = Storage::url($author->image['url']);
        }
        //return response()->json($author);
        return response()->json(['author' => $author, 'image' => $image]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'full_name' => 'required|max:75',
            'birth_date' => 'date|date_format:Y-m-d',
            'country' => 'max:75',
            'image' => 'nullable|sometimes|image'
        ]);

        try
        {
            $author = Author::findOrFail($id);
            $author->full_name = $request->full_name;
            $author->birth_date = $request->birth_date;
            $author->country = $request->country;
            $author->save();
            $image_name = $this->loadImage($request);
            if ($image_name != '')
            {
                $author->image()->update(['url' => 'images/'.$image_name]);
            }
            return response()->json(['status' => true, 'message' => 'El autor '.$author->full_name.' fue actualizado exitosamente']);
        }
        catch (\Exception $exc)
        {
            return response()->json(['status' => false, 'message' => 'Error al editar el registro']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try
        {
            $author = Author::findOrFail($id);
            $author->delete();
            if ($author->image())
            {
                $author->image()->delete();
            }
            return response()->json(['status' => true, 'message' => 'El autor '.$author->full_name.' fue eliinado exitosamente']);
        }
        catch (\Exception $exc)
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
