<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Company;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchController extends Controller
{
    /**
     * Global search for service provider admin (companies, buildings, units, tenants).
     */
    public function search(Request $request): View
    {
        $validated = $request->validate([
            'query' => ['nullable', 'string', 'max:255'],
            'type' => ['nullable', 'string', 'in:companies,buildings,units,tenants'],
        ]);

        $query = trim((string) ($validated['query'] ?? ''));
        $type = $validated['type'] ?? 'companies';

        $escaped = '%'.addcslashes($query, '%_\\').'%';

        $results = match ($type) {
            'companies' => $this->searchCompanies($escaped, $query),
            'buildings' => $this->searchBuildings($escaped, $query),
            'units' => $this->searchUnits($escaped, $query),
            'tenants' => $this->searchTenants($escaped, $query),
            default => $this->searchCompanies($escaped, $query),
        };

        return view('search.index', [
            'results' => $results,
            'query' => $query,
            'type' => $type,
        ]);
    }

    private function searchCompanies(string $likePattern, string $rawQuery): LengthAwarePaginator
    {
        $builder = Company::query()->orderBy('name');

        if ($rawQuery !== '') {
            $builder->where(function ($q) use ($likePattern) {
                $q->where('name', 'like', $likePattern)
                    ->orWhere('email', 'like', $likePattern)
                    ->orWhere('slug', 'like', $likePattern)
                    ->orWhere('phone', 'like', $likePattern);
            });
        }

        return $builder->paginate(15)->withQueryString();
    }

    private function searchBuildings(string $likePattern, string $rawQuery): LengthAwarePaginator
    {
        $builder = Building::query()->with('company')->orderBy('name');

        if ($rawQuery !== '') {
            $builder->where(function ($q) use ($likePattern) {
                $q->where('name', 'like', $likePattern)
                    ->orWhere('code', 'like', $likePattern)
                    ->orWhere('city', 'like', $likePattern)
                    ->orWhere('address', 'like', $likePattern);
            });
        }

        return $builder->paginate(15)->withQueryString();
    }

    private function searchUnits(string $likePattern, string $rawQuery): LengthAwarePaginator
    {
        $builder = Unit::query()->with(['building', 'company'])->orderBy('unit_number');

        if ($rawQuery !== '') {
            $builder->where(function ($q) use ($likePattern) {
                $q->where('unit_number', 'like', $likePattern)
                    ->orWhereHas('building', function ($bq) use ($likePattern) {
                        $bq->where('name', 'like', $likePattern);
                    });
            });
        }

        return $builder->paginate(15)->withQueryString();
    }

    private function searchTenants(string $likePattern, string $rawQuery): LengthAwarePaginator
    {
        $builder = User::query()
            ->where('type', 'tenant')
            ->with('company')
            ->orderBy('name');

        if ($rawQuery !== '') {
            $builder->where(function ($q) use ($likePattern) {
                $q->where('name', 'like', $likePattern)
                    ->orWhere('email', 'like', $likePattern)
                    ->orWhere('phone', 'like', $likePattern);
            });
        }

        return $builder->paginate(15)->withQueryString();
    }
}
