<?php

use App\Enums\RegistrationStatus;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignIdFor(Event::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('email');
            $table->enum('status', RegistrationStatus::cases())->default(RegistrationStatus::PENDING);

            $table->unique(['event_id', 'email']);
            $table->unique(['event_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
