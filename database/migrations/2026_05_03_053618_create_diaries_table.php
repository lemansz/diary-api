<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('diaries', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');

            $table->string('title')->nullable();
            $table->longText('content');
            $table->date('entry_date');

            $table->string('mood')->nullable();
            $table->string('weather')->nullable();
            $table->boolean('is_favourite')->default(false);

            $table->timestamps();

            $table->unique(['user_id', 'entry_date']);
            $table->index('entry_date');
            $table->index('is_favourite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diaries');
    }
};
