<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->enum('role', ['admin', 'user'])->default('user');
            $table->string('password');
            $table->timestamps();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');;
        });

        $this->seedInitialUsers();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }

    private function seedInitialUsers(): void
    {   
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@telkomsel.co.id',
                'role' => 'admin',
                'password' => app('hash')->make('admin123'),
                'category_id' => 1,
            ],
        ]);
    }
};
