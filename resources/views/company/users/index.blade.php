@extends('layouts.company')

@section('title', 'Team & users')
@section('page-title', 'Team & users')

@section('content')
<div class="space-y-8 max-w-6xl">
    <p class="text-sm text-gray-600">
        View active tenants and staff. Assign property managers to a building so they can handle maintenance tickets, applications, and contracts for that property only.
    </p>

    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add property manager</h3>
        <form method="POST" action="{{ route('company.users.managers.store', $company) }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full name *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                    <input type="password" name="password" id="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold" autocomplete="new-password">
                    @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm password *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold" autocomplete="new-password">
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="building_id" class="block text-sm font-medium text-gray-700">Assigned building</label>
                    <select name="building_id" id="building_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-gmo-gold focus:ring-gmo-gold">
                        <option value="">— Not set (assign later) —</option>
                        @foreach ($buildings as $b)
                            <option value="{{ $b->id }}" {{ (string) old('building_id') === (string) $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                    @error('building_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-gmo-gold text-black font-semibold rounded-md hover:bg-opacity-90 transition-colors">
                    Create manager account
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow p-4">
        <form method="GET" action="{{ route('company.users.index', $company) }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label for="type" class="block text-xs font-medium text-gray-500 mb-1">Role</label>
                <select name="type" id="type" class="rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="all" {{ request('type', 'all') === 'all' ? 'selected' : '' }}>All</option>
                    <option value="company_admin" {{ request('type') === 'company_admin' ? 'selected' : '' }}>Company admin</option>
                    <option value="property_manager" {{ request('type') === 'property_manager' ? 'selected' : '' }}>Property manager</option>
                    <option value="tenant" {{ request('type') === 'tenant' ? 'selected' : '' }}>Tenant</option>
                </select>
            </div>
            <div>
                <label for="status_scope" class="block text-xs font-medium text-gray-500 mb-1">Accounts</label>
                <select name="status_scope" id="status_scope" class="rounded-md border-gray-300 shadow-sm text-sm">
                    <option value="active" {{ request('status_scope', 'active') === 'active' ? 'selected' : '' }}>Active only</option>
                    <option value="all" {{ request('status_scope') === 'all' ? 'selected' : '' }}>Include inactive</option>
                </select>
            </div>
            <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-800 text-sm font-medium rounded-md hover:bg-gray-200">Filter</button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Building / unit</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($users as $u)
                        @php
                            $roleLabel = match ($u->type) {
                                'company_admin' => 'Company admin',
                                'property_manager' => 'Property manager',
                                'tenant' => 'Tenant',
                                default => $u->type,
                            };
                            $activeContract = $u->contracts->first();
                        @endphp
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $u->name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $u->email }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $roleLabel }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                @if ($u->isPropertyManager())
                                    @if ($u->building)
                                        {{ $u->building->name }}
                                    @else
                                        <span class="text-amber-700">Not assigned</span>
                                    @endif
                                @elseif ($u->isTenant() && $activeContract && $activeContract->unit)
                                    {{ $activeContract->unit->building->name ?? '—' }}, unit {{ $activeContract->unit->unit_number }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if ($u->is_active)
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                @if ($u->isPropertyManager() && $u->id !== Auth::id())
                                    <form method="POST" action="{{ route('company.users.update', [$company, $u]) }}" class="inline-flex flex-wrap items-center justify-end gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="building_id" class="text-xs rounded border-gray-300 max-w-[10rem]">
                                            <option value="">— Building —</option>
                                            @foreach ($buildings as $b)
                                                <option value="{{ $b->id }}" {{ (int) $u->building_id === (int) $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                                            @endforeach
                                        </select>
                                        <label class="inline-flex items-center gap-1 text-xs text-gray-700">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" name="is_active" value="1" {{ $u->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-gmo-gold focus:ring-gmo-gold">
                                            Active
                                        </label>
                                        <button type="submit" class="text-xs px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">Save</button>
                                    </form>
                                @elseif ($u->isTenant() && $u->id !== Auth::id())
                                    <form method="POST" action="{{ route('company.users.update', [$company, $u]) }}" class="inline-flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_active" value="0">
                                        <label class="inline-flex items-center gap-1 text-xs text-gray-700">
                                            <input type="checkbox" name="is_active" value="1" {{ $u->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-gmo-gold focus:ring-gmo-gold">
                                            Active
                                        </label>
                                        <button type="submit" class="text-xs px-2 py-1 bg-gray-100 rounded hover:bg-gray-200">Save</button>
                                    </form>
                                @else
                                    <span class="text-gray-400 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($users->isEmpty())
            <p class="px-4 py-8 text-center text-gray-500 text-sm">No users match your filters.</p>
        @endif
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection
