@extends('layouts.app')
@section('title', 'Items')

@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-12 col-md-8">
            <h1>All items</h1>
        </div>
        <div class="col-12 col-md-4">
            <div class="float-lg-end">
                {{-- TODO: Links, policy --}}

                @can('create', App\Models\Item::class)
                <a href="{{ route('items.create')}}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Add item</a>
                @endcan

                @can('create', App\Models\Label::class)
                <a href="{{ route('labels.create')}}" role="button" class="btn btn-sm btn-success mb-1"><i class="fas fa-plus-circle"></i> Add label</a>
                @endcan

            </div>
        </div>
    </div>

    {{-- TODO: Session flashes --}}
    @if (Session::has('item_deleted'))
        <div class="alert alert-success" role="alert">
            Item ({{ Session::get('item_deleted') }}) successfully deleted!
        </div>
    @endif

    @if (Session::has('label_deleted'))
        <div class="alert alert-success" role="alert">
            Label ({{ Session::get('label_deleted') }}) successfully deleted!
        </div>
    @endif

    <div class="row mt-3">
        <div class="col-12 col-lg-12">
            <div class="row">
                {{-- TODO: Read posts from DB --}}

                @forelse ( ($items) as $item)
                    <div class="col-12 col-md-6 col-lg-4 mb-3 d-flex align-self-stretch">
                        <div class="card w-100">
                            <img
                                src="{{
                                    asset(
                                        $item->image
                                            ? 'storage/' . $item->image
                                            : 'images/default_post_cover.jpg'
                                    )
                                }}"
                                class="card-img-top"
                                alt="Item cover"
                            >
                            <div class="card-body">
                                {{-- TODO: Title --}}
                                <h5 class="card-title mb-0">{{ $item->name }}</h5>
                                <p class="small mb-0">
                                    <span class="me-2">
                                        <i class="fas fa-user"></i>
                                        {{-- TODO: Author --}}
                                        <span>By Author</span>
                                    </span>

                                    <span>
                                        <i class="far fa-calendar-alt"></i>
                                        {{-- TODO: Date --}}
                                        <span>{{ $item->obtained}}</span>
                                    </span>
                                </p>

                                {{-- TODO: Read post categories from DB --}}
                                @foreach ($item->labels as $label)
                                    <a href="{{ route('labels.show', $label) }}" class="text-decoration-none">
                                        <span class="badge" style="background-color: {{ $label->color }}">{{ $label->name }}</span>
                                    </a>
                                @endforeach

                                {{-- TODO: Short desc --}}
                                <p class="card-text mt-1">{{ Str::limit($item->description, 40) . "..."}}</p>
                            </div>
                            <div class="card-footer">
                                {{-- TODO: Link --}}
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
                {{-- TODO: Pagination --}}
                {{ $items->links() }}
            </div>
{{--
        </div>
        <div class="col-12 col-lg-3">
            <div class="row">
                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Categories
                        </div>
                        <div class="card-body">

                            @foreach (['primary', 'secondary','danger', 'warning', 'info', 'dark'] as $category)
                                <a href="#" class="text-decoration-none">
                                    <span class="badge bg-{{ $category }}">{{ $category }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-3">
                    <div class="card bg-light">
                        <div class="card-header">
                            Statistics
                        </div>
                        <div class="card-body">
                            <div class="small">
                                <ul class="fa-ul">

                                    <li><span class="fa-li"><i class="fas fa-user"></i></span>Users: N/A</li>
                                    <li><span class="fa-li"><i class="fas fa-layer-group"></i></span>Categories: N/A</li>
                                    <li><span class="fa-li"><i class="fas fa-file-alt"></i></span>Posts: N/A</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
--}}
        </div>
    </div>
</div>
@endsection
