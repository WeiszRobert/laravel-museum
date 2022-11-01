<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('labels.create', [
            'labels' => Label::all()
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
                'name' => ['required'],
                'color' => [
                    'required',
                    'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/',
                ],
                'display' => ['nullable', 'boolean'],
            ],
            [
                'name.required' => 'The label name is required.',
                'color.required' => 'The label color is required.',
                'color.regex' => 'The label color must be a valid hex color code.',
                'display.boolean' => 'The label visibility must be a boolean value.',
            ]
        );

        //check if the label already exists
        $label = Label::where('name', $validated['name'])->first();
        if ($label) {
            return redirect()->route('labels.create')->with('error', 'The label already exists.');
        }

        $label = new Label();
        $label->name = $validated['name'];
        $label->color = $validated['color'];
        $label->display = $validated['display'] ?? false;
        $label->save();

        Session::flash("item_created", $validated['name']);

        return Redirect::route('items.index', $label);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function show(Label $label)
    {
        return view('labels.show', [
            'label' => $label,
            'items' => $label->items()->orderBy('obtained', 'desc')->where('label_id', $label->id)->paginate(5)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function edit(Label $label)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Label $label)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Label  $label
     * @return \Illuminate\Http\Response
     */
    public function destroy(Label $label)
    {
        //todo: ez még kell
        //$this->authorize('delete');

        //remove the label from all items
        $label->items()->detach();

        $label->delete();

        Session::flash("label_deleted", $label->name);

        return Redirect::route('items.index');
    }
}
