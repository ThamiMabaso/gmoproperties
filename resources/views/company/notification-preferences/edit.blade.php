@extends('layouts.company')

@section('title', 'Notification preferences')
@section('page-title', 'Notification preferences')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <p class="text-sm text-gray-600">Choose which categories may send you <strong>email</strong> in addition to in-app notifications. In-app notifications are always available in your notification center.</p>

    <form method="post" action="{{ route('company.notification-preferences.update', $company) }}" class="bg-white rounded-lg shadow p-6 space-y-5">
        @csrf
        @method('PUT')

        @php
            $fields = [
                'email_applications' => 'Applications (submissions, decisions)',
                'email_contracts' => 'Contracts (signatures, status)',
                'email_invoices' => 'Invoices',
                'email_maintenance' => 'Maintenance tickets',
                'email_announcements' => 'Announcements',
            ];
        @endphp

        @foreach ($fields as $name => $label)
            <div class="flex items-start justify-between gap-4 border-b border-gray-100 pb-4 last:border-0 last:pb-0">
                <div>
                    <p class="font-medium text-gray-900">{{ $label }}</p>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <input type="hidden" name="{{ $name }}" value="0">
                    <input type="checkbox" name="{{ $name }}" value="1" class="rounded border-gray-300 text-gmo-gold focus:ring-gmo-gold h-5 w-5"
                        @checked(old($name, $preference->$name))>
                </div>
            </div>
        @endforeach

        <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90">Save preferences</button>
    </form>
</div>
@endsection
