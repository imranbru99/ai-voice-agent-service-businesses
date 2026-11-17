@extends('layouts.marketing')

@section('title', 'Features')
@section('meta_description', 'VoiceServe AI features: call handling, after-hours rules, lead qualification, job booking, transcripts, CRM sync, and modular Filament admin.')

@section('content')
<section class="section">
    <span class="pill">Product capabilities</span>
    <h2>Everything a service business needs after hours</h2>
    <p class="muted">A complete Laravel + Filament platform — not a demo widget — with real models, policies, seeders, and toggleable modules.</p>
    <div class="grid" style="margin-top:1.5rem;">
        <article class="panel">
            <h3 style="margin-top:0;font-family:'Space Grotesk',sans-serif;">AI call answering</h3>
            <p class="muted">Branded greetings, intent capture, emergency vs routine routing, and full call SID history.</p>
        </article>
        <article class="panel">
            <h3 style="margin-top:0;font-family:'Space Grotesk',sans-serif;">Lead qualification</h3>
            <p class="muted">Score urgency, budget fit, service fit, and decision-maker signals from the live conversation.</p>
        </article>
        <article class="panel">
            <h3 style="margin-top:0;font-family:'Space Grotesk',sans-serif;">Job booking</h3>
            <p class="muted">Book windows against your service catalog — HVAC, plumbing, electrical — with tech notes.</p>
        </article>
        <article class="panel">
            <h3 style="margin-top:0;font-family:'Space Grotesk',sans-serif;">CRM sync</h3>
            <p class="muted">Push leads and jobs to HubSpot, Jobber, ServiceTitan, or Salesforce with sync status tracking.</p>
        </article>
        <article class="panel">
            <h3 style="margin-top:0;font-family:'Space Grotesk',sans-serif;">After-hours rules</h3>
            <p class="muted">Business calendars, escalation phones, emergency booking flags, and AI greeting scripts.</p>
        </article>
        <article class="panel">
            <h3 style="margin-top:0;font-family:'Space Grotesk',sans-serif;">Transcripts & agent runs</h3>
            <p class="muted">Searchable speaker turns with intent highlights plus auditable AI agent run histories.</p>
        </article>
    </div>
</section>
@endsection
