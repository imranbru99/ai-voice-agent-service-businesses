<?php

namespace Tests\Feature;

use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    public function test_home_page_loads(): void
    {
        $this->get('/')->assertOk()->assertSee('VoiceServe AI');
    }

    public function test_features_page_loads(): void
    {
        $this->get('/features')->assertOk();
    }

    public function test_modules_page_loads(): void
    {
        $this->get('/modules')->assertOk();
    }

    public function test_demo_page_loads(): void
    {
        $this->get('/demo')->assertOk()->assertSee('demo@voiceserve-ai.test');
    }
}
