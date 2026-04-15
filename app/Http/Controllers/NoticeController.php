<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    /**
     * Check if user can manage notices (admin or receptionist only)
     */
    private function canManageNotices(): bool
    {
        $user = Auth::user();
        if (!$user) return false;

        $roles = $user->getRoleNames()->toArray();
        return in_array('admin', $roles) || in_array('receptionist', $roles);
    }

    public function index()
    {
        $notices = Notice::orderBy('created_at', 'desc')->get()->map(function ($notice) {
            return [
                'id' => $notice->id,
                'title' => $notice->title,
                'content' => $notice->content,
                'type' => $notice->type,
                'priority' => $notice->priority,
                'target_audience' => $notice->target_audience,
                'published_at' => $notice->published_at?->format('Y-m-d'),
                'expires_at' => $notice->expires_at?->format('Y-m-d'),
                'created_at' => $notice->created_at,
                'updated_at' => $notice->updated_at,
            ];
        });

        return Inertia::render('Notice/Index', [
            'notices' => $notices,
            'canManageNotices' => $this->canManageNotices(),
        ]);
    }

    public function store(Request $request)
    {
        // Only admin and receptionist can create notices
        if (!$this->canManageNotices()) {
            abort(403, 'Unauthorized. Only admin and receptionist can create notices.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,holiday,exam,meeting,policy,event',
            'priority' => 'required|string|in:low,medium,high',
            'target_audience' => 'required|string|in:all,students,parents,teachers,staff',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:published_at',
        ]);

        $validated['created_by'] = Auth::id();

        $notice = Notice::create($validated);

        return back()->with('success', 'Notice created successfully.');
    }

    public function update(Request $request, Notice $notice)
    {
        // Only admin and receptionist can update notices
        if (!$this->canManageNotices()) {
            abort(403, 'Unauthorized. Only admin and receptionist can update notices.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:general,holiday,exam,meeting,policy,event',
            'priority' => 'required|string|in:low,medium,high',
            'target_audience' => 'required|string|in:all,students,parents,teachers,staff',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:published_at',
        ]);

        $notice->update($validated);

        return back()->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        // Only admin and receptionist can delete notices
        if (!$this->canManageNotices()) {
            abort(403, 'Unauthorized. Only admin and receptionist can delete notices.');
        }

        $notice->delete();

        return back()->with('success', 'Notice deleted successfully.');
    }

    public function show($id)
    {
        $notice = Notice::findOrFail($id);

        return Inertia::render('Notice/Show', [
            'notice' => [
                'id' => $notice->id,
                'title' => $notice->title,
                'content' => $notice->content,
                'type' => $notice->type,
                'priority' => $notice->priority,
                'target_audience' => $notice->target_audience,
                'published_at' => $notice->published_at?->format('Y-m-d'),
                'expires_at' => $notice->expires_at?->format('Y-m-d'),
                'created_at' => $notice->created_at,
                'updated_at' => $notice->updated_at,
            ],
            'canManageNotices' => $this->canManageNotices(),
        ]);
    }
}
