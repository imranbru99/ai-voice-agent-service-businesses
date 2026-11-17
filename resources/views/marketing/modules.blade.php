@extends('layouts.marketing')

@section('title', 'Modules')
@section('meta_description', 'Toggle VoiceServe AI modules: voice calls, leads, qualification, job bookings, transcripts, CRM sync, service catalog, after-hours, and attendance.')

@section('content')
<section class="section">
    <span class="pill">Module control plane</span>
    <h2>Enable only the domains you need</h2>
    <p class="muted">Config defaults + Filament Modules resource. Disabled modules hide from navigation and refuse service work.</p>
    <div class="panel" style="margin-top:1.5rem; overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Key</th>
                    <th>Status</th>
                    <th>Purpose</th>
                </tr>
            </thead>
            <tbody>
                @foreach($modules as $module)
                    <tr>
                        <td>{{ $module['name'] }}</td>
                        <td><code>{{ $module['key'] }}</code></td>
                        <td>{{ $module['enabled'] ? 'ON' : 'OFF' }}</td>
                        <td>{{ $module['description'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
