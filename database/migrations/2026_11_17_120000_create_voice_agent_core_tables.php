<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('module_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('service_catalog_items', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->string('trade')->default('general');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->unsignedSmallInteger('typical_duration_minutes')->default(60);
            $table->boolean('bookable_after_hours')->default(true);
            $table->boolean('is_active')->default(true);
            $table->json('tags')->nullable();
            $table->timestamps();
        });

        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('company')->nullable();
            $table->string('source')->default('inbound_call');
            $table->string('status')->default('new');
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('service_interest')->nullable();
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->unsignedTinyInteger('score')->default(0);
            $table->boolean('crm_synced')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('voice_calls', function (Blueprint $table) {
            $table->id();
            $table->string('call_sid')->unique();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('handled_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('direction')->default('inbound');
            $table->string('from_number');
            $table->string('to_number')->nullable();
            $table->string('status')->default('ringing');
            $table->boolean('after_hours')->default(false);
            $table->boolean('answered_by_ai')->default(true);
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->string('disposition')->nullable();
            $table->string('recording_url')->nullable();
            $table->text('ai_summary')->nullable();
            $table->boolean('job_booked')->default(false);
            $table->boolean('lead_qualified')->default(false);
            $table->boolean('crm_synced')->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->json('tags')->nullable();
            $table->timestamps();
        });

        Schema::create('lead_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->cascadeOnDelete();
            $table->foreignId('voice_call_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('score')->default(0);
            $table->string('urgency')->default('medium');
            $table->string('budget_fit')->default('unknown');
            $table->string('service_fit')->default('unknown');
            $table->boolean('decision_maker')->default(false);
            $table->text('notes')->nullable();
            $table->json('signals')->nullable();
            $table->timestamp('qualified_at')->nullable();
            $table->timestamps();
        });

        Schema::create('job_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('voice_call_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('service_catalog_item_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->string('trade')->default('general');
            $table->string('status')->default('scheduled');
            $table->string('priority')->default('normal');
            $table->timestamp('window_start')->nullable();
            $table->timestamp('window_end')->nullable();
            $table->string('address_line')->nullable();
            $table->string('city')->nullable();
            $table->text('customer_notes')->nullable();
            $table->text('tech_notes')->nullable();
            $table->decimal('quoted_amount', 10, 2)->nullable();
            $table->boolean('booked_by_ai')->default(true);
            $table->boolean('crm_synced')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('call_transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voice_call_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('sequence')->default(1);
            $table->string('speaker')->default('caller');
            $table->unsignedInteger('start_ms')->default(0);
            $table->unsignedInteger('end_ms')->default(0);
            $table->text('content');
            $table->string('intent')->nullable();
            $table->string('sentiment')->nullable();
            $table->boolean('ai_highlighted')->default(false);
            $table->json('entities')->nullable();
            $table->timestamps();
        });

        Schema::create('crm_syncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('job_booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('voice_call_id')->nullable()->constrained()->nullOnDelete();
            $table->string('crm_provider')->default('hubspot');
            $table->string('external_id')->nullable();
            $table->string('object_type')->default('lead');
            $table->string('title');
            $table->string('status')->default('pending');
            $table->timestamp('synced_at')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();
        });

        Schema::create('after_hours_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('timezone')->default('America/Chicago');
            $table->time('business_open')->default('08:00:00');
            $table->time('business_close')->default('17:00:00');
            $table->json('weekdays')->nullable();
            $table->boolean('ai_answers')->default(true);
            $table->boolean('allow_emergency_booking')->default(true);
            $table->string('escalate_to_phone')->nullable();
            $table->string('greeting_script')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('voice_agent_runs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voice_call_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('run_type')->default('full_intake');
            $table->string('status')->default('queued');
            $table->unsignedTinyInteger('steps')->default(0);
            $table->json('actions')->nullable();
            $table->text('recommendation')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voice_agent_runs');
        Schema::dropIfExists('after_hours_rules');
        Schema::dropIfExists('crm_syncs');
        Schema::dropIfExists('call_transcripts');
        Schema::dropIfExists('job_bookings');
        Schema::dropIfExists('lead_qualifications');
        Schema::dropIfExists('voice_calls');
        Schema::dropIfExists('leads');
        Schema::dropIfExists('service_catalog_items');
        Schema::dropIfExists('module_settings');
    }
};
