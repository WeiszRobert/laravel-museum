@extends('layouts.app')
@section('title', 'Edit label ' . $label->name)

@section('content')
<div class="container">
    <h1>Edit label</h1>
    <div class="mb-4">
        {{-- TODO: Link --}}
        <a href="{{ route('labels.show', $label) }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the <span class="badge" style="background-color: {{ $label->color }}">{{$label->name}}</span> labels</a>
            <br>
        <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    {{-- TODO: Session flashes --}}

    {{-- TODO: action, method --}}
    <form action="{{ route('labels.update', $label) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $label->name) }}">
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="color" class="col-sm-2 col-form-label">Color*</label>
            <div class="col-sm-10">
                <input type="color" class="form-control @error('color') is-invalid @enderror" id="color" name="color" value="{{ old('color', $label->color) }}">

                @error('color')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="display" class="col-sm-2 col-form-label">Is it visible?</label>
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="display" name="display" value="1" {{ old('display', $label->display) == 1 ? 'checked' : '' }}>
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>

    </form>
</div>
@endsection
