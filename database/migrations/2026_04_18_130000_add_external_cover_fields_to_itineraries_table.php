<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->string('cover_image_provider')->nullable()->after('cover_image');
            $table->text('cover_image_remote_url')->nullable()->after('cover_image_provider');
            $table->string('cover_image_author_name')->nullable()->after('cover_image_remote_url');
            $table->text('cover_image_author_url')->nullable()->after('cover_image_author_name');
            $table->text('cover_image_source_url')->nullable()->after('cover_image_author_url');
        });
    }

    public function down(): void
    {
        Schema::table('itineraries', function (Blueprint $table) {
            $table->dropColumn([
                'cover_image_provider',
                'cover_image_remote_url',
                'cover_image_author_name',
                'cover_image_author_url',
                'cover_image_source_url',
            ]);
        });
    }
};
