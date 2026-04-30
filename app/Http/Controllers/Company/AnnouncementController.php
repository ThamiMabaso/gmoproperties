<?php

declare(strict_types=1);

namespace App\Http\Controllers\Company;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\Building;
use App\Models\Company;
use App\Services\Announcements\AnnouncementPublishService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AnnouncementController extends BaseCompanyController
{
    public function __construct(
        private readonly AnnouncementPublishService $publishService,
    ) {
    }

    public function index(Request $request, Company $company): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_announcements');

        $buildingIds = $this->managedBuildingIdsFor($company);

        $query = Announcement::query()
            ->where('company_id', $company->id)
            ->with(['creator', 'building'])
            ->where(function ($outer): void {
                $outer->where(function ($pub): void {
                    $pub->publishedVisible();
                })->orWhere(function ($draft): void {
                    $draft->where('is_published', false)
                        ->where('created_by', Auth::id());
                });
            })
            ->orderByDesc('priority')
            ->orderByDesc('published_at');

        if ($buildingIds !== null) {
            if ($buildingIds === []) {
                $query->whereRaw('0 = 1');
            } else {
                $query->where(function ($q) use ($buildingIds): void {
                    $q->whereNull('building_id')
                        ->orWhereIn('building_id', $buildingIds);
                });
            }
        }

        $announcements = $query->paginate(15)->withQueryString();

        return view('company.announcements.index', compact('company', 'announcements'));
    }

    public function create(Company $company): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('create_announcements');

        $buildings = $this->scopedBuildingsQuery($company, $this->managedBuildingIdsFor($company))
            ->orderBy('name')
            ->get();

        return view('company.announcements.create', compact('company', 'buildings'));
    }

    public function store(Request $request, Company $company): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('create_announcements');

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:20000',
            'building_id' => 'nullable|exists:buildings,id',
            'priority' => 'nullable|integer|min:0|max:5',
            'publish_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:publish_at',
            'is_published' => 'sometimes|boolean',
        ]);

        $buildingId = isset($validated['building_id']) ? (int) $validated['building_id'] : null;

        if ($buildingId !== null) {
            $building = Building::query()->findOrFail($buildingId);
            $this->ensureBuildingInScope($company, $building);
        }

        if (Auth::user()->isPropertyManager()) {
            $managerBuildingId = Auth::user()->building_id;

            if ($managerBuildingId === null) {
                return back()->withInput()->with('error', 'You must be assigned to a building to publish announcements.');
            }

            $buildingId = (int) $managerBuildingId;
        }

        $publishAt = isset($validated['publish_at'])
            ? \Carbon\Carbon::parse($validated['publish_at'])
            : null;

        $isPublished = (bool) ($validated['is_published'] ?? false);

        $announcement = Announcement::create([
            'company_id' => $company->id,
            'created_by' => Auth::id(),
            'building_id' => $buildingId,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'is_published' => false,
            'publish_at' => $publishAt,
            'expires_at' => isset($validated['expires_at'])
                ? \Carbon\Carbon::parse($validated['expires_at'])
                : null,
            'priority' => (int) ($validated['priority'] ?? 0),
        ]);

        if ($isPublished && ($publishAt === null || $publishAt->isPast())) {
            $this->publishService->publishNow($announcement);
        }

        return redirect()
            ->route('company.announcements.index', $company)
            ->with('success', 'Announcement saved.');
    }

    public function show(Company $company, Announcement $announcement): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_announcements');

        if ((int) $announcement->company_id !== (int) $company->id) {
            abort(403);
        }

        if ($announcement->building_id !== null) {
            $announcement->loadMissing('building');

            if ($announcement->building !== null) {
                $this->ensureBuildingInScope($company, $announcement->building);
            }
        }

        if (! $announcement->is_published) {
            $user = Auth::user();

            if (! $user->isCompanyAdmin() && (int) $announcement->created_by !== (int) $user->id) {
                abort(404);
            }
        }

        return view('company.announcements.show', compact('company', 'announcement'));
    }

    public function edit(Company $company, Announcement $announcement): View
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('edit_announcements');

        if ((int) $announcement->company_id !== (int) $company->id) {
            abort(403);
        }

        $this->ensureCanEditAnnouncement($announcement);

        $buildings = $this->scopedBuildingsQuery($company, $this->managedBuildingIdsFor($company))
            ->orderBy('name')
            ->get();

        return view('company.announcements.edit', compact('company', 'announcement', 'buildings'));
    }

    public function update(Request $request, Company $company, Announcement $announcement): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('edit_announcements');

        if ((int) $announcement->company_id !== (int) $company->id) {
            abort(403);
        }

        $this->ensureCanEditAnnouncement($announcement);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:20000',
            'building_id' => 'nullable|exists:buildings,id',
            'priority' => 'nullable|integer|min:0|max:5',
            'publish_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:publish_at',
            'is_published' => 'sometimes|boolean',
        ]);

        $buildingId = isset($validated['building_id']) ? (int) $validated['building_id'] : null;

        if ($buildingId !== null) {
            $building = Building::query()->findOrFail($buildingId);
            $this->ensureBuildingInScope($company, $building);
        }

        if (Auth::user()->isPropertyManager()) {
            $managerBuildingId = Auth::user()->building_id;

            if ($managerBuildingId === null) {
                return back()->withInput()->with('error', 'You must be assigned to a building to edit announcements.');
            }

            $buildingId = (int) $managerBuildingId;
        }

        $publishAt = isset($validated['publish_at'])
            ? \Carbon\Carbon::parse($validated['publish_at'])
            : null;

        $wasPublished = $announcement->is_published;

        $announcement->update([
            'building_id' => $buildingId,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'publish_at' => $publishAt,
            'expires_at' => isset($validated['expires_at'])
                ? \Carbon\Carbon::parse($validated['expires_at'])
                : null,
            'priority' => (int) ($validated['priority'] ?? 0),
        ]);

        $wantsPublish = (bool) ($validated['is_published'] ?? false);

        if (! $wasPublished && $wantsPublish && ($publishAt === null || $publishAt->isPast())) {
            $this->publishService->publishNow($announcement);
        }

        return redirect()
            ->route('company.announcements.show', [$company, $announcement])
            ->with('success', 'Announcement updated.');
    }

    public function markRead(Request $request, Company $company, Announcement $announcement): RedirectResponse
    {
        $this->ensureCompanyAccess($company);
        $this->checkPermission('view_announcements');

        if ((int) $announcement->company_id !== (int) $company->id) {
            abort(403);
        }

        AnnouncementRead::query()->updateOrCreate(
            [
                'announcement_id' => $announcement->id,
                'user_id' => Auth::id(),
            ],
            [
                'read_at' => now(),
            ]
        );

        return back()->with('success', 'Marked as read.');
    }

    private function ensureCanEditAnnouncement(Announcement $announcement): void
    {
        $user = Auth::user();

        if ($user->isCompanyAdmin()) {
            return;
        }

        if ($user->isPropertyManager() && (int) $announcement->created_by === (int) $user->id) {
            return;
        }

        abort(403, 'You can only edit announcements you created unless you are a company administrator.');
    }
}
