@extends('layouts.app')
{{-- TODO: Post title --}}
@section('title', 'View item: ' . $item->name)

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
                <span>{{$item->User::find($item->user_id)->name}}</span>
            </p>
            <p class="small text-secondary mb-0">
                <i class="far fa-calendar-alt"></i>
                {{-- TODO: Date --}}
                <span>{{$item->obtained}}</span>
            </p>

            <div class="mb-2">
                {{-- TODO: Read post categories from DB --}}
                @foreach ($item->labels as $label)
                @if ($label->display)
                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                        <span class="badge" style="background-color: {{ $label->color }}">{{ $label->name }}</span>
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

    <section style="background-color: #517d81;">
        <div class="container my-5 py-5">
          <div class="row d-flex justify-content-center">
            <div class="col-md-12 col-lg-10">
              <div class="card text-dark">

                  <div class="card-body p-4">

                    <h4 class="mb-0">Recent comments</h4>
                    <p class="fw-light">Latest Comments for item: {{$item->name}}</p>
                    <hr class="my-0" style="height: 1px;" />

                    @forelse ($item->comments as $comment)
                    <div class="card-body p-4">
                        <div class="d-flex flex-start">
                            <div>
                            <h6 class="fw-bold mb-1">{{$users->get($comment->user_id-1)->name}}</h6>

                            {{--get users name with id of $comment->user_id--}}


                            <div class="d-flex align-items-center mb-3">
                                <p class="mb-0">
                                    {{$comment->updated_at}}
                                </p>
                                <a href="#!" class="link-muted"><i class="fas fa-pencil-alt ms-2"></i></a>
                                <a href="#!" class="link-danger"><i class="fas fa-trash-alt ms-2"></i></a>
                            </div>
                            <p class="mb-0">
                                {{$comment->text}}
                            </p>
                            </div>
                        </div>
                    </div>
                    <hr class="my-0" style="height: 1px;" />
                    @empty
                    <div>
                        <h6 class="fw-bold mb-1">No comments on this item yet.</h6>
                    </div>
                </div>

                @endforelse

                    <form  action="/items/{{ $item->id }}/comments" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card-body p-4">
                        <div class="d-flex flex-start w-100">
                            <div class="w-100">
                            <label for="comment"><h5>Add a comment as {{ $user->name }}</h5></label>
                            <div class="form-outline">
                                <textarea class="form-control" @error('text') is-invalid @enderror id="text" name="text" rows="4"></textarea>
                                @error('text')
                                    <div >
                                        Error: {{ $message }}
                                    </div>
                                @enderror
                            </div>


                            <div class="d-flex justify-content-between mt-3">
                                <button type="submit" class="btn btn-success">
                                    Send <i class="fas fa-long-arrow-alt-right ms-3"></i>
                                </button>
                            </div>
                            </div>
                        </div>
                        </div>
                    </form>
              </div>
            </div>
          </div>
      </section>
    </div>
</div>
@endsection
