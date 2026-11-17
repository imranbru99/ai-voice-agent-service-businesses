@extends('layouts.marketing')

@section('title', 'Pricing')
@section('meta_description', 'VoiceServe AI pricing tiers for solo trades, multi-crew service companies, and franchise / multi-location operators.')

@section('content')
<section class="section">
    <span class="pill">Simple plans</span>
    <h2>Price by crew size, not seat chaos</h2>
    <p class="muted">Start with after-hours answering. Add qualification, booking, and CRM sync when you are ready.</p>
    <div class="grid" style="margin-top:1.5rem;">
        <article class="panel">
            <span class="pill">Starter</span>
            <h3 style="font-family:'Space Grotesk',sans-serif;margin:0.6rem 0;">$149 / mo</h3>
            <p class="muted">1 line, AI answering, lead capture, after-hours rules. Ideal for solo plumbers / HVAC techs.</p>
        </article>
        <article class="panel">
            <span class="pill">Growth</span>
            <h3 style="font-family:'Space Grotesk',sans-serif;margin:0.6rem 0;">$399 / mo</h3>
            <p class="muted">Qualify + book + CRM sync, multi-trade catalog, dispatch manager seats. Best for 3–15 techs.</p>
        </article>
        <article class="panel">
            <span class="pill">Enterprise</span>
            <h3 style="font-family:'Space Grotesk',sans-serif;margin:0.6rem 0;">Custom</h3>
            <p class="muted">Multi-location, custom CRM bridges, attendance hub, SLA, and white-label agent scripts.</p>
        </article>
    </div>
</section>
@endsection
