@extends('layouts.admin')

@section('content')
    <h1>Edit {{ $item->name }}</h1>

    <div class="row">
        <div class="col-md-4">
            <img src="https://image.tmdb.org/t/p/w300{{ $item->poster_path }}" class="img-fluid rounded mb-4" alt="{{ $item->name }}">
            <p>{{ $item->overview }}</p>
        </div>
        <div class="col-md-8">
            <form action="{{ route('admin.media.update', $item->id) }}" method="POST">
                @csrf
                @method('PUT')

                <h3>Media Sources (m3u8/mpd)</h3>
                <div id="sources-container">
                    @foreach($item->mediaSources as $i => $source)
                        <div class="mb-3 border p-3 rounded">
                            <label class="form-label">URL</label>
                            <input type="text" name="urls[{{ $i }}][url]" class="form-control mb-2" value="{{ $source->url }}">
                            <label class="form-label">Audio URL (Optional)</label>
                            <input type="text" name="urls[{{ $i }}][audio_url]" class="form-control mb-2" value="{{ $source->audio_url }}">
                            <label class="form-label">Type</label>
                            <select name="urls[{{ $i }}][type]" class="form-select">
                                <option value="m3u8" {{ $source->type == 'm3u8' ? 'selected' : '' }}>m3u8</option>
                                <option value="mpd" {{ $source->type == 'mpd' ? 'selected' : '' }}>mpd</option>
                            </select>
                        </div>
                    @endforeach
                    <div class="mb-3 border p-3 rounded">
                        <label class="form-label">New URL</label>
                        <input type="text" name="urls[{{ count($item->mediaSources) }}][url]" class="form-control mb-2">
                        <label class="form-label">New Audio URL (Optional)</label>
                        <input type="text" name="urls[{{ count($item->mediaSources) }}][audio_url]" class="form-control mb-2">
                        <label class="form-label">New Type</label>
                        <select name="urls[{{ count($item->mediaSources) }}][type]" class="form-select">
                            <option value="m3u8">m3u8</option>
                            <option value="mpd">mpd</option>
                        </select>
                    </div>
                </div>

                <h3>Subtitles</h3>
                <div id="subtitles-container">
                    @foreach($item->subtitles as $i => $sub)
                        <div class="mb-3 border p-3 rounded">
                            <label class="form-label">Subtitle URL</label>
                            <input type="text" name="subtitles[{{ $i }}][url]" class="form-control mb-2" value="{{ $sub->url }}">
                            <label class="form-label">Language</label>
                            <input type="text" name="subtitles[{{ $i }}][language]" class="form-control mb-2" value="{{ $sub->language }}">
                        </div>
                    @endforeach
                    <div class="mb-3 border p-3 rounded">
                        <label class="form-label">New Subtitle URL</label>
                        <input type="text" name="subtitles[{{ count($item->subtitles) }}][url]" class="form-control mb-2">
                        <label class="form-label">New Language</label>
                        <input type="text" name="subtitles[{{ count($item->subtitles) }}][language]" class="form-control mb-2" value="eng">
                    </div>
                </div>

                <button class="btn btn-primary btn-lg mt-4">Save Changes</button>
            </form>
        </div>
    </div>
@endsection
