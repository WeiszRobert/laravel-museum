@extends('layouts.app')
@section('title', 'Show label ' . $label->name)

@section('content')
<div class="container">

    @if (Session::has('label_created'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_created') }}) successfully created!
        </div>
    @endif

    @if (Session::has('label_updated'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_updated') }}) successfully updated!
        </div>
    @endif

    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>Items for <span class="badge" style="background-color: {{ $label->color }}">{{$label->name}}</span></h1>
        </div>
        <div class="col-12 col-md-4">
            <div class="float-lg-end">

                @can('update', $label)
                <a href="{{ route('labels.edit', $label) }}" role="button" class="btn btn-sm btn-primary">
                    <i class="far fa-edit"></i> Edit label
                </a>
                @endcan

                @can('delete', $label)
                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#delete-confirm-modal">
                    <i class="far fa-trash-alt"></i> Delete label
                </button>
                @endcan

            </div>


        </div>
        <a href="{{ route('items.index')}}"><i class="fas fa-long-arrow-alt-left"></i> Back to the homepage</a>
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
                    Are you sure you want to delete label <strong>{{$label->name}}</strong>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button
                        type="button"
                        class="btn btn-danger"
                        onclick="document.getElementById('delete-label-form').submit();"
                    >
                        Yes, delete this label
                    </button>

                    <form id="delete-label-form" action="#" method="POST" class="d-none">
                        <form id="delete-label-form" action="{{ route('labels.destroy', $label) }}" method="POST" class="d-none">
                            @method('DELETE')
                            @csrf
                        </form>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12 col-lg-12">
            <div class="row">
                @forelse (($items) as $item)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100">
                            <img
                                src="{{ asset('images/default_post_cover.jpg') }}"
                                class="card-img-top"
                                alt="Item cover"
                            >
                            <div class="card-body">
                                <h5 class="card-title mb-0">{{ $item->name }}</h5>
                                <p class="small mb-0">
                                    <span class="me-2">
                                        <i class="fas fa-user"></i>
                                        <span>By {{$item->User::find($item->user_id)->name}}</span>
                                    </span>

                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        <span>{{ $item->obtained}}</span>
                                    </span>
                                </p>

                                @foreach ($item->labels as $label)
                                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                        <span class="badge" style="background-color: {{ $label->color }}">{{ $label->name }}</span>
                                    </a>
                                @endforeach

                                <p class="card-text mt-1">{{ Str::limit($item->description, 40)}}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('items.show', $item) }}" class="btn btn-primary">
                                    <span>View item</span> <i class="fas fa-angle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-warning" role="alert">
                            No items found!
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
