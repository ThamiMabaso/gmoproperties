@extends('layouts.company')

@section('title', 'New Message')
@section('page-title', 'New Message')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-lg shadow p-6">
        <form method="POST" action="{{ route('company.messages.store', $company) }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label for="recipient_id" class="block text-sm font-medium text-gray-700">To *</label>
                    <select name="recipient_id" id="recipient_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        <option value="">Select recipient</option>
                        @foreach($recipients as $recipient)
                            <option value="{{ $recipient->id }}" {{ old('recipient_id') == $recipient->id ? 'selected' : '' }}>
                                {{ $recipient->name }} ({{ ucfirst(str_replace('_', ' ', $recipient->type)) }})
                            </option>
                        @endforeach
                    </select>
                    @error('recipient_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="body" class="block text-sm font-medium text-gray-700">Message *</label>
                    <textarea name="body" id="body" rows="8" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-4">
                <a href="{{ route('company.messages.index', $company) }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
