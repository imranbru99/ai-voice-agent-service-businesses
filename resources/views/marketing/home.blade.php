@extends('layouts.marketing')

@section('title', 'AI Voice Agent for Service Businesses')
@section('meta_description', 'AI voice agent for service businesses that answers calls, books jobs, qualifies leads, and syncs CRM so owners never miss revenue after hours.')

@section('content')
<section class="hero">
    <span class="pill">HVAC · Plumbing · Electrical · Modular Laravel + Filament</span>
    <h1>VoiceServe AI</h1>
    <p>
        Never miss after-hours revenue again. An AI voice agent answers calls, qualifies leads, books jobs,
        and syncs your CRM — with every module toggleable for your service business.
    </p>
    <div class="cta-row">
        <a class="btn" href="{{ route('demo') }}">Try demo credentials</a>
        <a class="btn btn-outline" href="{{ route('features') }}">Explore features</a>
    </div>
</section>

<section class="section">
    <img class="dash-shot" src="{{ asset('docs/screenshots/dashboard.png') }}" alt="VoiceServe AI green after-hours call command center dashboard">
    <p class="muted" style="margin-top:0.75rem;">After-hours Call Command Center — live call monitor, job booking queue, lead scores, and CRM sync.</p>
</section>

<section class="section">
    <h2>Toggle only what your trade needs</h2>
    <p class="muted">Run lean call logging or full qualify → book → CRM sync. Modules turn on and off without rewriting the platform.</p>
    <div class="grid" style="margin-top:1.25rem;">
        @foreach($modules as $module)
            <article class="panel">
                <span class="pill">{{ $module['enabled'] ? 'Enabled' : 'Optional' }}</span>
                <h3 style="margin:0.6rem 0 0.35rem;font-family:'Space Grotesk',sans-serif;">{{ $module['name'] }}</h3>
                <p class="muted" style="margin:0;">{{ $module['description'] }}</p>
            </article>
        @endforeach
    </div>
</section>
@endsection
