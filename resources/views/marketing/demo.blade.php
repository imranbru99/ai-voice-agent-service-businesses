@extends('layouts.marketing')

@section('title', 'Demo')
@section('meta_description', 'Demo credentials for VoiceServe AI Filament admin — explore calls, bookings, leads, CRM sync, and after-hours modules.')

@section('content')
<section class="section">
    <span class="pill">Live demo</span>
    <h2>Explore the seeded Call Command Center</h2>
    <p class="muted">Use the demo user to browse Filament resources, module toggles, and after-hours voice workflows.</p>
    <div class="panel" style="margin-top:1.5rem;">
        <p><strong>Admin URL:</strong> <a href="/admin">/admin</a></p>
        <p><strong>Email:</strong> <code>demo@voiceserve-ai.test</code></p>
        <p><strong>Password:</strong> <code>password</code></p>
        <p class="muted">Also seeded: Super Admin, Admin, Manager, Staff, and Client roles under the same password.</p>
        <div class="cta-row" style="margin-top:1rem;">
            <a class="btn" href="/admin">Open Filament admin</a>
            <a class="btn btn-outline" href="{{ route('modules') }}">View modules</a>
        </div>
    </div>
</section>
@endsection
