<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('link_id')->constrained()->cascadeOnDelete();
            $table->string('ip_address', 45);
            $table->timestamp('clicked_at');
            $table->timestamps();

            $table->index(['link_id', 'clicked_at']);
            $table->index('clicked_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('link_clicks');
    }
};
