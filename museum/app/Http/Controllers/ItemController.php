<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\User;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('items.index', [
            //'items' => Item::all()
            //'items' => Item::orderBy('obtained', 'desc')->get()
            'items' => Item::orderBy('obtained', 'desc')->paginate(5)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('items.create', [
            'item' => new Item(),
            'labels' => Label::all(),
            'users' => User::orderBy('name')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required',
                'obtained' => 'required|date|before_or_equal:now',
                'description' => 'required',
                'labels' => ['nullable', 'array'],
                'labels.*' => ['nullable', 'integer', 'exists:labels,id'],
                'image' => ['nullable', 'file', 'image', 'max:4096']
            ],
            [
                'name.required' => 'The name is required',
                'obtained.required' => 'The obtained date is required',
                'obtained.date' => 'The obtained date must be a valid date',
                'obtained.before_or_equal' => 'The obtained date must be today or earlier',
                'labels.*.exists' => 'The selected label is invalid',
                'image.file' => 'The image must be a file',
                'image.image' => 'The image must be an image',
                'image.max' => 'The image must be less than 4MB'
            ]
        );
        // filename
        $fn = null;

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            $fn = 'ci_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($fn, $file->get());
        }

        $item = new Item();
        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->image = $fn;
        $item->obtained = $validated['obtained'];
        $item->user()->associate(Auth::user());
        $item->save();

        if (isset($validated['labels'])) {
            $item->labels()->sync($validated['labels']);
        }

        Session::flash("item_created", $validated['name']);

        // redirect()->route(...)
        return Redirect::route('items.show', $item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return view('items.show', [
            'item' => $item,
            //'user' => User::find($item->user_id, 'id')
            //get name field from previous result
            'user' => User::find($item->user_id, 'id')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        // Jogosultságkezelés
        //$this->authorize('update');

        return view('items.edit', [
            'item' => $item,
            'labels' => Label::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        // Jogosultságkezelés
        //$this->authorize('update');

        $validated = $request->validate(
            [
                'name' => 'required',
                'obtained' => 'required|date|before_or_equal:now',
                'description' => 'required',
                'labels' => ['nullable', 'array'],
                'labels.*' => ['nullable', 'integer', 'exists:labels,id'],
                'image' => ['nullable', 'file', 'image', 'max:4096'],
                'remove_cover_image' => ['nullable', 'boolean']
            ],
            [
                'name.required' => 'The name is required',
                'obtained.required' => 'The obtained date is required',
                'obtained.date' => 'The obtained date must be a valid date',
                'obtained.before_or_equal' => 'The obtained date must be today or earlier',
                'labels.*.exists' => 'The selected label is invalid',
                'image.file' => 'The image must be a file',
                'image.image' => 'The image must be an image',
                'image.max' => 'The image must be less than 4MB'
            ]
        );

        // filename
        $image = $item->image;
        $remove_image = isset($validated['remove_cover_image']);

        if ($request->hasFile('image') && !$remove_image) {
            $file = $request->file('image');

            $image = 'ci_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

            Storage::disk('public')->put($image, $file->get());
        }

        if ($remove_image) {
            $image = null;
        }

        // Ha volt korábban kép, és töröltük v felülírtuk, akkor a szemétként ottmaradt fájlt töröljük ki
        if ($image !== $item->image && $item->image !== null) {
            // Előző fájl törlése (ezen a ponton a post még nem frissült ugye)
            Storage::disk('public')->delete($item->image);
        }

        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->image = $image;
        $item->obtained = $validated['obtained'];
        $item->save();

        if (isset($validated['labels'])) {
            $item->labels()->sync($validated['labels']);
        }

        Session::flash("item_updated", $validated['name']);

        return Redirect::route('items.show', $item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //TODO: ez amúgy kell ide
        //$this->authorize('delete');

        // Kitörli a itemot az adatbázisból
        $item->delete();

        Session::flash("item_deleted", $item->title);

        return Redirect::route('items.index');
    }
}
