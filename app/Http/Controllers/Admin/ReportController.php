<?php
// First create ReportController
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::with(['reporter', 'post', 'comment', 'reviewer']);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type')) {
            if ($request->type === 'post') {
                $query->whereNotNull('post_id');
            } elseif ($request->type === 'comment') {
                $query->whereNotNull('comment_id');
            }
        }

        $reports = $query->latest()->paginate(20);

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load(['reporter', 'post', 'comment', 'reviewer']);

        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,resolved,dismissed',
            'admin_notes' => 'nullable|string|max:500'
        ]);

        $report->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now()
        ]);

        return back()->with('success', 'Report updated successfully!');
    }
}
