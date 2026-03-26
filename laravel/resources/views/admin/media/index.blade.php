@extends('layouts.admin')

@section('content')
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.libraries.index') }}">Libraries</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.media.index', $library->id) }}">{{ $library->name }}</a></li>
        @if($parent)
            <li class="breadcrumb-item active" aria-current="page">{{ $parent->name }}</li>
        @endif
      </ol>
    </nav>

    <h1>{{ $parent ? $parent->name : $library->name }} Items</h1>

    @if(!$parent)
    <div class="row">
        <div class="col-md-4">
            <h3>Search TMDb</h3>
            <div class="input-group mb-3">
                <input type="text" id="search-query" class="form-control" placeholder="Search...">
                <button class="btn btn-outline-secondary" id="search-btn">Search</button>
            </div>
            <div id="search-results" class="list-group mb-4"></div>
        </div>
        <div class="col-md-8">
            <h3>Existing Items</h3>
            <div class="row">
                @foreach($items as $item)
                    <div class="col-md-3 mb-4 text-center">
                        @if($item->poster_path)
                            <img src="https://image.tmdb.org/t/p/w200{{ $item->poster_path }}" class="img-fluid rounded mb-2" alt="{{ $item->name }}">
                        @else
                            <div class="bg-secondary text-white rounded mb-2 d-flex align-items-center justify-content-center" style="height: 250px;">No Image</div>
                        @endif
                        <h6>{{ $item->name }}</h6>
                        @if($item->type == 'series')
                            <a href="{{ route('admin.media.index', [$library->id, $item->id]) }}" class="btn btn-sm btn-info">Seasons</a>
                        @endif
                        <a href="{{ route('admin.media.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-md-4">
            <h3>Add {{ $parent->type == 'series' ? 'Season' : 'Episode' }}</h3>
            <form action="{{ route('admin.media.store', [$library->id, $parent->id]) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ $parent->type == 'series' ? 'season' : 'episode' }}">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="{{ $parent->type == 'series' ? 'Season 1' : 'Episode 1' }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Number</label>
                    <input type="number" name="index_number" class="form-control" required>
                </div>
                <button class="btn btn-primary">Add</button>
            </form>
        </div>
        <div class="col-md-8">
            <h3>Items</h3>
            <div class="list-group">
                @foreach($items as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ $item->index_number }}. {{ $item->name }}</strong>
                        </div>
                        <div>
                            @if($item->type == 'season')
                                <a href="{{ route('admin.media.index', [$library->id, $item->id]) }}" class="btn btn-sm btn-info">Episodes</a>
                            @endif
                            <a href="{{ route('admin.media.edit', $item->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
@endsection

@section('scripts')
    @if(!$parent)
    <script>
        document.getElementById('search-btn').addEventListener('click', function() {
            const query = document.getElementById('search-query').value;
            const type = '{{ $library->type == "tvshows" ? "tv" : "movie" }}';
            fetch(`/admin/media/search?query=${query}&type=${type}`)
                .then(r => r.json())
                .then(results => {
                    const container = document.getElementById('search-results');
                    container.innerHTML = '';
                    results.forEach(res => {
                        const btn = document.createElement('form');
                        btn.action = '{{ route('admin.media.store', $library->id) }}';
                        btn.method = 'POST';
                        btn.innerHTML = `
                            @csrf
                            <input type="hidden" name="tmdb_id" value="${res.id}">
                            <input type="hidden" name="type" value="${type == 'movie' ? 'movie' : 'series'}">
                            <button type="submit" class="list-group-item list-group-item-action">
                                ${res.title || res.name} (${(res.release_date || res.first_air_date || '').substring(0, 4)})
                            </button>
                        `;
                        container.appendChild(btn);
                    });
                });
        });
    </script>
    @endif
@endsection
