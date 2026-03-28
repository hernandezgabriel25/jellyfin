<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // movies, tvshows, mixed
            $table->timestamps();
        });

        Schema::create('media_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('library_id')->constrained()->onDelete('cascade');
            $table->uuid('parent_id')->nullable(); // For seasons and episodes
            $table->string('type'); // movie, series, season, episode
            $table->string('name');
            $table->text('overview')->nullable();
            $table->string('tmdb_id')->nullable();
            $table->string('imdb_id')->nullable();
            $table->integer('production_year')->nullable();
            $table->string('poster_path')->nullable();
            $table->string('backdrop_path')->nullable();
            $table->integer('index_number')->nullable(); // Season number or episode number
            $table->integer('parent_index_number')->nullable(); // For episodes: season number
            $table->timestamps();
        });

        Schema::create('media_sources', function (Blueprint $table) {
            $table->id();
            $table->uuid('media_item_id');
            $table->string('url');
            $table->string('type'); // m3u8, mpd
            $table->string('audio_url')->nullable();
            $table->timestamps();
            $table->foreign('media_item_id')->references('id')->on('media_items')->onDelete('cascade');
        });

        Schema::create('subtitles', function (Blueprint $table) {
            $table->id();
            $table->uuid('media_item_id');
            $table->string('url');
            $table->string('language')->default('eng');
            $table->string('format')->default('vtt');
            $table->timestamps();
            $table->foreign('media_item_id')->references('id')->on('media_items')->onDelete('cascade');
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('subtitles');
        Schema::dropIfExists('media_sources');
        Schema::dropIfExists('media_items');
        Schema::dropIfExists('libraries');
    }
};
