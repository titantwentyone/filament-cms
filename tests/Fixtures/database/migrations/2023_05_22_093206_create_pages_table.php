<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->longText('content')->nullable();
            $table->string('title')->required();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_root')->default(false);
            $table->boolean('uses_route_parameters')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }
};