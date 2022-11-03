@extends('layouts.app')
{{-- TODO: Post title --}}
@section('title', 'View post: ')

@section('content')
<div class="container">

    {{-- TODO: Session flashes --}}
    @if (Session::has('item_created'))
        <div class="alert alert-success" role="alert">
            Item ({{ Session::get('item_created') }}) successfully created!
        </div>
    @endif

    @if (Session::has('item_updated'))
        <div class="alert alert-success" role="alert">
            Item ({{ Session::get('item_updated') }}) successfully updated!
        </div>
    @endif

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            {{-- TODO: Title --}}
            <h1>{{$item->name}}</h1>

            <p class="small text-secondary mb-0">
                <i class="fas fa-user"></i>
                {{-- TODO: Author --}}
                <span>{{$item->user->name}}</span>
            </p>
            <p class="small text-secondary mb-0">
                <i class="far fa-calendar-alt"></i>
                {{-- TODO: Date --}}
                <span>{{$item->obtained}}</span>
            </p>

            <div class="mb-2">
                {{-- TODO: Read post categories from DB --}}
                @foreach ($item->labels as $category)
                @if ($category->display)
                    <a href="{{ route('labels.show', $category) }}" class="text-decoration-none">
                        <span class="badge" style="background-color: {{ $category->color }}">{{ $category->name }}</span>
                    </a>
                @endif
                @endforeach
            </div>

            {{-- TODO: Link --}}
            <a href="{{ route('items.index')}}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>

        </div>

        <div class="col-12 col-md-4">
            <div class="float-lg-end">

                {{-- TODO: Links, policy --}}
                @can('update', $item)
                <a role="button" class="btn btn-sm btn-primary" href="{{ route('items.edit', $item) }}"><i class="far fa-edit"></i> Edit item</a>
                @endcan

                @can('delete', $item)
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal"><i class="far fa-trash-alt">
                    <span></i> Delete item</span>
                </button>
                @endcan

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="delete-confirm-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Confirm delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- TODO: Title --}}
                    Are you sure you want to delete item <strong>{{$item->name}}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-post-form').submit();"
                    >
                        Yes, delete this item
                    </button>

                    {{-- TODO: Route, directives --}}
                    <form id="delete-post-form" action="{{ route('items.destroy', $item) }}" method="POST" class="d-none">
                        @method('DELETE')
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <img
        id="cover_preview_image"
        {{-- TODO: Cover --}}
        src="{{
            asset(
                $item->image
                    ? 'storage/' . $item->image
                    : 'images/default_post_cover.jpg'
            )
        }}"
        alt="Image preview"
        width="350px"
        class="my-3"
    >

    <div class="mt-3">
        {{-- TODO: Post paragraphs --}}
        {!! nl2br(e($item->description)) !!}
    </div>
</div>
@endsection
