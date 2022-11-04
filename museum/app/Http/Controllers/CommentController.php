<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

use App\Models\Item;
use App\Models\User;
use App\Models\Label;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CommentController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Item $item)
    {
        $this->authorize('create', $item);

        $request = request();

        $validated = $request->validate(
            [
                'text' => 'required'
            ],
            [
                'text.required' => 'Please enter a comment'
            ]
        );

        $iiiiiitem = Item::find($item->id);

         //   return Redirect::route('items.index');
        $comment = new Comment();
        $comment->text = $validated['text'];
        $comment->user()->associate(Auth::user());
        $comment->item()->associate($iiiiiitem);
        $comment->save();

        Session::flash("comment_message", "Comment added");

        return Redirect::route('items.show', $item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        $this->authorize('update', $comment);

        return view('comments.edit', [
            'comment' => $comment,
            'item' => $comment->item,
            'user' => Auth::user()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        $request = request();

        $validated = $request->validate(
            [
                'text' => 'required'
            ],
            [
                'text.required' => 'Please enter a comment'
            ]
        );

      //  $iiiiiitem = Item::find($item->id);

        $item = $comment->item;

         //   return Redirect::route('items.index');
        //$comment = new Comment();
        $comment->text = $validated['text'];
        $comment->user()->associate(Auth::user());
        //$comment->item()->associate($item);
        $comment->save();

        Session::flash("comment_message", "Comment added");

        return Redirect::route('items.show', $item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment, Item $item)
    {
        $this->authorize('delete', $comment);

        // Kitörli a itemot az adatbázisból
        $comment->delete();

        Session::flash("comment_deleted", $comment->title);

        return Redirect::route('items.show', request()->get('item'))/*->with('item_deleted', 'Item deleted successfully')*/;
    }
}
