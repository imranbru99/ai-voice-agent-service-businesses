<?php

namespace Tests\Feature;

use App\Services\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_voice_calls_module_defaults_enabled(): void
    {
        $manager = app(ModuleManager::class);

        $this->assertTrue($manager->enabled('voice_calls'));
        $this->assertTrue($manager->enabled('voice_agent'));
        $this->assertFalse($manager->enabled('attendance'));
    }

    public function test_sync_from_config_persists_settings(): void
    {
        $manager = app(ModuleManager::class);
        $manager->syncFromConfig();

        $this->assertContains('job_bookings', $manager->enabledKeys());
        $manager->set('job_bookings', false);
        $this->assertFalse($manager->enabled('job_bookings'));
    }
}
