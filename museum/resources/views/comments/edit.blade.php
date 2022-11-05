@extends('layouts.app')
@section('title', 'Edit comment')

@section('content')
<div class="container">
    <h1>Edit comment</h1>
    <div class="mb-4">
        <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
        <br>
        <a href="{{ route('items.show', $item) }}"><i class="fas fa-long-arrow-alt-left"></i> Back to item's page</a>
    </div>

    <h2>Editing comment for {{$comment->item->name}} as {{$user->name}}</h2>

    <form action="{{ route('comments.update', $comment) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group row mb-3">
            <label for="text" class="col-sm-2 col-form-label">Description*</label>
            <div class="col-sm-10">
                <textarea rows="5" class="form-control @error('text') is-invalid @enderror" id="text" name="text">{{ old('text', $comment->text) }}</textarea>

                @error('text')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>

    </form>
</div>
@endsection
