<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\AfterHoursRule;
use App\Models\CallTranscript;
use App\Models\CrmSync;
use App\Models\JobBooking;
use App\Models\Lead;
use App\Models\LeadQualification;
use App\Models\ServiceCatalogItem;
use App\Models\User;
use App\Models\VoiceAgentRun;
use App\Models\VoiceCall;
use App\Services\ModuleManager;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(ModuleManager::class)->syncFromConfig();

        $users = [
            ['name' => 'Super Admin', 'email' => 'superadmin@voiceserve-ai.test', 'role' => UserRole::SuperAdmin],
            ['name' => 'Ops Admin', 'email' => 'admin@voiceserve-ai.test', 'role' => UserRole::Admin],
            ['name' => 'Dispatch Manager', 'email' => 'manager@voiceserve-ai.test', 'role' => UserRole::Manager],
            ['name' => 'Field Staff', 'email' => 'staff@voiceserve-ai.test', 'role' => UserRole::Staff],
            ['name' => 'Client Owner', 'email' => 'client@voiceserve-ai.test', 'role' => UserRole::Client],
            ['name' => 'Demo User', 'email' => 'demo@voiceserve-ai.test', 'role' => UserRole::Demo],
        ];

        $created = [];
        foreach ($users as $data) {
            $created[$data['role']->value] = User::query()->updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => $data['role']->value,
                    'phone' => '+1-555-0142',
                    'organization_name' => 'Greenline Home Services',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $staff = $created[UserRole::Staff->value];
        $manager = $created[UserRole::Manager->value];
        $demo = $created[UserRole::Demo->value];

        AfterHoursRule::query()->updateOrCreate(
            ['name' => 'Default Midwest hours'],
            [
                'timezone' => 'America/Chicago',
                'business_open' => '08:00:00',
                'business_close' => '17:00:00',
                'weekdays' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'ai_answers' => true,
                'allow_emergency_booking' => true,
                'escalate_to_phone' => '+1-555-0199',
                'greeting_script' => 'Thanks for calling Greenline. Our AI agent can book a tech or take your details now.',
                'is_active' => true,
            ]
        );

        $catalog = [
            ['sku' => 'HVAC-NOHEAT', 'name' => 'No-heat diagnostic', 'trade' => 'hvac', 'base_price' => 129],
            ['sku' => 'PLUMB-LEAK', 'name' => 'Emergency leak stop', 'trade' => 'plumbing', 'base_price' => 189],
            ['sku' => 'ELEC-PANEL', 'name' => 'Panel / breaker check', 'trade' => 'electrical', 'base_price' => 149],
            ['sku' => 'HVAC-AC', 'name' => 'AC not cooling', 'trade' => 'hvac', 'base_price' => 139],
        ];

        $items = [];
        foreach ($catalog as $row) {
            $items[] = ServiceCatalogItem::query()->updateOrCreate(
                ['sku' => $row['sku']],
                [
                    'name' => $row['name'],
                    'trade' => $row['trade'],
                    'slug' => strtolower($row['sku']),
                    'description' => $row['name'].' available for AI booking.',
                    'base_price' => $row['base_price'],
                    'typical_duration_minutes' => 90,
                    'bookable_after_hours' => true,
                    'is_active' => true,
                    'tags' => [$row['trade'], 'demo'],
                ]
            );
        }

        $leadDefs = [
            ['code' => 'LD-2026-0001', 'name' => 'James Torres', 'phone' => '+1-555-867-5309', 'interest' => 'Plumbing leak', 'city' => 'Austin'],
            ['code' => 'LD-2026-0002', 'name' => 'Robert Miles', 'phone' => '+1-555-201-4488', 'interest' => 'No heat', 'city' => 'Dallas'],
            ['code' => 'LD-2026-0003', 'name' => 'Priya Shah', 'phone' => '+1-555-773-9910', 'interest' => 'Breaker trip', 'city' => 'Houston'],
            ['code' => 'LD-2026-0004', 'name' => 'Elena Vargas', 'phone' => '+1-555-442-1180', 'interest' => 'AC not cooling', 'city' => 'San Antonio'],
            ['code' => 'LD-2026-0005', 'name' => 'Chris Nguyen', 'phone' => '+1-555-330-7744', 'interest' => 'Water heater', 'city' => 'Fort Worth'],
        ];

        foreach ($leadDefs as $index => $def) {
            $lead = Lead::query()->updateOrCreate(
                ['code' => $def['code']],
                [
                    'name' => $def['name'],
                    'phone' => $def['phone'],
                    'email' => strtolower(str_replace(' ', '.', $def['name'])).'@example.com',
                    'company' => null,
                    'source' => 'inbound_call',
                    'status' => $index < 3 ? 'qualified' : 'new',
                    'owner_id' => $index % 2 === 0 ? $manager->id : $staff->id,
                    'service_interest' => $def['interest'],
                    'address_line' => (100 + $index).' Service Rd',
                    'city' => $def['city'],
                    'region' => 'TX',
                    'score' => 60 + ($index * 7),
                    'crm_synced' => $index < 3,
                    'metadata' => ['seed' => true],
                ]
            );

            $afterHours = $index % 2 === 0;
            $call = VoiceCall::query()->updateOrCreate(
                ['call_sid' => 'CA-DEMO-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'lead_id' => $lead->id,
                    'handled_by' => $demo->id,
                    'direction' => 'inbound',
                    'from_number' => $def['phone'],
                    'to_number' => '+1-555-0100',
                    'status' => 'completed',
                    'after_hours' => $afterHours,
                    'answered_by_ai' => true,
                    'duration_seconds' => 180 + ($index * 35),
                    'disposition' => $index < 3 ? 'booked' : 'qualified',
                    'recording_url' => 'https://recordings.example/'.$def['code'],
                    'ai_summary' => "Caller needs {$def['interest']}. AI captured address and preferred window.",
                    'job_booked' => $index < 3,
                    'lead_qualified' => true,
                    'crm_synced' => $index < 3,
                    'started_at' => now()->subHours(5 - $index),
                    'ended_at' => now()->subHours(5 - $index)->addMinutes(4),
                    'tags' => ['demo', $afterHours ? 'after_hours' : 'business_hours'],
                ]
            );

            LeadQualification::query()->updateOrCreate(
                ['lead_id' => $lead->id, 'voice_call_id' => $call->id],
                [
                    'score' => $lead->score,
                    'urgency' => $afterHours ? 'high' : 'medium',
                    'budget_fit' => $lead->score >= 75 ? 'strong' : 'fair',
                    'service_fit' => 'strong',
                    'decision_maker' => true,
                    'notes' => 'Qualified by VoiceServe AI during the call.',
                    'signals' => ['after_hours' => $afterHours, 'interest' => $def['interest']],
                    'qualified_at' => now()->subHours(4 - $index),
                ]
            );

            CallTranscript::query()->updateOrCreate(
                ['voice_call_id' => $call->id, 'sequence' => 1],
                [
                    'speaker' => 'agent',
                    'start_ms' => 0,
                    'end_ms' => 8000,
                    'content' => 'Thanks for calling Greenline. I can book a technician for you tonight.',
                    'intent' => 'greeting',
                    'sentiment' => 'positive',
                    'ai_highlighted' => false,
                    'entities' => [],
                ]
            );

            CallTranscript::query()->updateOrCreate(
                ['voice_call_id' => $call->id, 'sequence' => 2],
                [
                    'speaker' => 'caller',
                    'start_ms' => 8000,
                    'end_ms' => 18000,
                    'content' => "I have a {$def['interest']} at my place and need someone soon.",
                    'intent' => strtolower(explode(' ', $def['interest'])[0]),
                    'sentiment' => $afterHours ? 'urgent' : 'neutral',
                    'ai_highlighted' => true,
                    'entities' => [$def['interest'], $def['city']],
                ]
            );

            $item = $items[$index % count($items)];
            $booking = null;
            if ($index < 3) {
                $booking = JobBooking::query()->updateOrCreate(
                    ['code' => 'JOB-2026-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                    [
                        'lead_id' => $lead->id,
                        'voice_call_id' => $call->id,
                        'service_catalog_item_id' => $item->id,
                        'assigned_to' => $staff->id,
                        'title' => $def['interest'].' — AI booked',
                        'trade' => $item->trade,
                        'status' => 'scheduled',
                        'priority' => $afterHours ? 'emergency' : 'normal',
                        'window_start' => now()->addHours(2 + $index),
                        'window_end' => now()->addHours(4 + $index),
                        'address_line' => $lead->address_line,
                        'city' => $lead->city,
                        'customer_notes' => $call->ai_summary,
                        'tech_notes' => 'Bring diagnostic kit.',
                        'quoted_amount' => $item->base_price,
                        'booked_by_ai' => true,
                        'crm_synced' => true,
                        'metadata' => ['seed' => true],
                    ]
                );
            }

            if ($index < 4) {
                CrmSync::query()->updateOrCreate(
                    ['voice_call_id' => $call->id, 'object_type' => $booking ? 'job' : 'lead'],
                    [
                        'lead_id' => $lead->id,
                        'job_booking_id' => $booking?->id,
                        'crm_provider' => ['hubspot', 'jobber', 'servicetitan', 'salesforce'][$index % 4],
                        'external_id' => 'VS-'.$call->call_sid,
                        'title' => $booking?->title ?? ($lead->name.' inbound lead'),
                        'status' => 'synced',
                        'synced_at' => now()->subHours(3),
                        'payload' => ['score' => $lead->score],
                    ]
                );
            }

            VoiceAgentRun::query()->updateOrCreate(
                ['voice_call_id' => $call->id, 'run_type' => $afterHours ? 'after_hours_intake' : 'full_intake'],
                [
                    'triggered_by' => $demo->id,
                    'status' => 'completed',
                    'steps' => $booking ? 5 : 3,
                    'actions' => [
                        ['step' => 1, 'action' => 'Answer call'],
                        ['step' => 2, 'action' => 'Capture intent'],
                        ['step' => 3, 'action' => 'Qualify lead'],
                        ['step' => 4, 'action' => $booking ? 'Book job' : 'Schedule callback'],
                        ['step' => 5, 'action' => 'Sync CRM'],
                    ],
                    'recommendation' => $afterHours
                        ? 'Confirm emergency window and SMS the on-call tech.'
                        : 'Confirm booking window and owner in CRM.',
                    'started_at' => $call->started_at,
                    'finished_at' => $call->ended_at,
                ]
            );
        }
    }
}
