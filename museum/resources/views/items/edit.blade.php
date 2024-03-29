@extends('layouts.app')
@section('title', 'Edit item' . $item->name)

@section('content')
<div class="container">
    <h1>Edit item</h1>

    <div class="mb-4">
        <a href="{{ route('items.show', $item) }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the item</a>
    </div>

    <div class="mb-4">
        <a href="{{ route('items.index') }}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
    </div>

    <form  action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="form-group row mb-3">
            <label for="name" class="col-sm-2 col-form-label">Name*</label>
            <div class="col-sm-10">
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $item->name) }}">

                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="description" class="col-sm-2 col-form-label">Description*</label>
            <div class="col-sm-10">
                <textarea rows="5" class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $item->description) }}</textarea>

                @error('description')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="obtained" class="col-sm-2 col-form-label">Obtained*</label>
            <div class="col-sm-10">
                <input type="date" class="form-control @error('obtained') is-invalid @enderror" id="obtained" name="obtained" value="{{ old('obtained', $item->obtained) }}">

                @error('obtained')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>

        <div class="form-group row mb-3">
            <label for="labels" class="col-sm-2 col-form-label py-0">Labels</label>
            <div class="col-sm-10">
                @forelse ($labels as $label)
                    <div class="form-check">
                        <input
                            type="checkbox"
                            class="form-check-input"
                            value="{{ $label->id }}"
                            id="label{{ $label->id }}"
                            name="labels[]"
                            @checked(
                                in_array(
                                    $label->id,
                                    old('labels', $item->labels->pluck('id')->toArray())
                                )
                            )
                        >
                        <label for="label{{ $label->id }}" class="form-check-label">
                            <span class="badge" style="background: {{$label->color }}">
                                {{ $label->name }}
                            </span>
                        </label>
                    </div>
                @empty
                    <p>No labels found</p>
                @endforelse
            </div>
        </div>

        <div class="form-group row mb-3" id="remove_cover_image_settings">
            <label class="col-sm-2 col-form-label">Settings</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="form-check">
                        {{-- TODO: Checked --}}
                        <input type="checkbox" class="form-check-input" value="1" id="remove_cover_image" name="remove_cover_image">
                        <label for="remove_cover_image" class="form-check-label">Remove cover image</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row mb-3" id="cover_image_section">
            <label for="image" class="col-sm-2 col-form-label">Cover image</label>
            <div class="col-sm-10">
                <div class="form-group">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <input type="file" class="form-control-file" id="image" name="image">
                        </div>
                        <div id="cover_preview" class="col-12">
                            <p>Cover preview </p>
                            <img id="cover_preview_image" src="{{
                                asset(
                                    $item->image
                                        ? 'storage/' . $item->image
                                        : 'images/default_post_cover.jpg'
                                )
                            }}" alt="Cover preview">
                        </div>
                    </div>
                </div>
            </div>

            @error('image')
                <p class="text-danger">
                    <small>
                        {{ $message }}
                    </small>
                </p>
            @enderror
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Store</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const removeCoverInput = document.querySelector('input#remove_cover_image');
    const coverImageSection = document.querySelector('#cover_image_section');
    const coverImageInput = document.querySelector('input#image');
    const coverPreviewContainer = document.querySelector('#cover_preview');
    const coverPreviewImage = document.querySelector('img#cover_preview_image');
    const removeCoverImageSettings = document.querySelector('#remove_cover_image_settings');
    // Render Blade to JS code:
    // TODO: Use attached image
    const defaultCover = `{{ asset('images/default_post_cover.jpg') }}`;

    removeCoverInput.onchange = event => {
        if (removeCoverInput.checked) {
            coverImageSection.classList.add('d-none');
        } else {
            coverImageSection.classList.remove('d-none');
        }
    }

    coverImageInput.onchange = event => {
        const [file] = coverImageInput.files;
        if (file) {
            coverPreviewImage.src = URL.createObjectURL(file);
            removeCoverImageSettings.classList.remove('d-none');
        } else {
            coverPreviewImage.src = defaultCover;
        }
    }

    //if cover image is the default one, hide the remove cover image setting
    if (coverPreviewImage.src === defaultCover) {
        removeCoverImageSettings.classList.add('d-none');
    }
</script>
@endsection
