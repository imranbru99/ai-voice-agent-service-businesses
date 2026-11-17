<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Toggleable voice-agent modules
    |--------------------------------------------------------------------------
    | Service businesses enable only the telephony & CRM domains they need.
    */
    'voice_calls' => [
        'enabled' => (bool) env('MODULE_VOICE_CALLS', true),
        'name' => 'Voice Calls',
        'description' => 'Inbound/outbound call log with after-hours capture, duration, and disposition.',
    ],
    'leads' => [
        'enabled' => (bool) env('MODULE_LEADS', true),
        'name' => 'Leads',
        'description' => 'Caller profiles with phone, service interest, source, and owner assignment.',
    ],
    'lead_qualification' => [
        'enabled' => (bool) env('MODULE_LEAD_QUALIFICATION', true),
        'name' => 'Lead Qualification',
        'description' => 'AI scoring, urgency, budget fit, and service-fit tags from live calls.',
    ],
    'job_bookings' => [
        'enabled' => (bool) env('MODULE_JOB_BOOKINGS', true),
        'name' => 'Job Bookings',
        'description' => 'Schedule jobs from answered calls — window, trade, address, tech notes.',
    ],
    'call_transcripts' => [
        'enabled' => (bool) env('MODULE_CALL_TRANSCRIPTS', true),
        'name' => 'Call Transcripts',
        'description' => 'Searchable AI transcripts with intent highlights and sentiment.',
    ],
    'crm_sync' => [
        'enabled' => (bool) env('MODULE_CRM_SYNC', true),
        'name' => 'CRM Sync',
        'description' => 'Push qualified leads and booked jobs into HubSpot, Jobber, ServiceTitan, or Salesforce.',
    ],
    'service_catalog' => [
        'enabled' => (bool) env('MODULE_SERVICE_CATALOG', true),
        'name' => 'Service Catalog',
        'description' => 'Trades and SKUs the voice agent can quote, qualify, and book.',
    ],
    'after_hours' => [
        'enabled' => (bool) env('MODULE_AFTER_HOURS', true),
        'name' => 'After-Hours Rules',
        'description' => 'Business-hours calendars, escalation paths, and emergency call routing.',
    ],
    'voice_agent' => [
        'enabled' => (bool) env('MODULE_VOICE_AGENT', true),
        'name' => 'AI Voice Agent',
        'description' => 'Agent runs that answer, qualify, book, and sync CRM without a human on the line.',
    ],
    'attendance' => [
        'enabled' => (bool) env('MODULE_ATTENDANCE', false),
        'name' => 'Staff Attendance',
        'description' => 'Optional biometric/time-clock hub for dispatchers and field techs.',
    ],
];
