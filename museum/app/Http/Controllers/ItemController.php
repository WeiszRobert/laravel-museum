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
        return view('items.index', [
            'items' => Item::orderBy('obtained', 'desc')->paginate(9),
            'labels' => Label::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Item::class);
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
        $this->authorize('create', Item::class);
        $validated = $request->validate(
            [
                'name' => ['required'],
                'obtained' => ['required', 'date', 'before_or_equal:now'],
                'description' => ['required'],
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
            'user' => Auth::user(),
            'users' => User::all(),
            'comments' => $item->comments()->orderBy('updated_at', 'asc')->get()
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
        $this->authorize('update', $item);

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
        $this->authorize('update', $item);

        $validated = $request->validate(
            [
                'name' => ['required'],
                'obtained' => ['required', 'date', 'before_or_equal:now'],
                'description' => ['required'],
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

        if ($image !== $item->image && $item->image !== null) {
            Storage::disk('public')->delete($item->image);
        }

        $item->name = $validated['name'];
        $item->description = $validated['description'];
        $item->image = $image;
        $item->obtained = $validated['obtained'];
        $item->save();

        if (isset($validated['labels'])) {
            $item->labels()->sync($validated['labels']);
        } else {
            $item->labels()->detach();
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
        $this->authorize('delete', $item);

        $item->delete();

        return Redirect::route('items.index')->with('item_deleted', $item->name);
    }
}
