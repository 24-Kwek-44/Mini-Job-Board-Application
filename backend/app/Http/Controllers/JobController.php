<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    // Public/Applicant: Browse all published jobs
    public function index()
    {
        return Job::where('status', 'open')->with('employer:id,name')->latest()->get();
    }

    // Employer: View own jobs
    public function myJobs(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return $request->user()->jobs()->latest()->get(); // Requires User hasMany jobs
    }

    // Employer: Create Job
    public function store(Request $request)
    {
        if ($request->user()->role !== 'employer') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'salary_range' => 'required|string',
            'is_remote' => 'boolean',
        ]);

        $job = $request->user()->jobs()->create($validated);

        return response()->json($job, 201);
    }

    // Employer: Update Job
    public function update(Request $request, Job $job)
    {
        if ($request->user()->role !== 'employer' || $job->employer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'title' => 'string|max:255',
            'description' => 'string',
            'location' => 'string',
            'salary_range' => 'string',
            'is_remote' => 'boolean',
            'status' => 'string|in:open,closed',
        ]);

        $job->update($validated);

        return response()->json($job);
    }

    // Employer: Delete Job
    public function destroy(Request $request, Job $job)
    {
        if ($request->user()->role !== 'employer' || $job->employer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $job->delete();

        return response()->json(['message' => 'Job deleted']);
    }

    public function show(Job $job)
    {
        return $job->load('employer:id,name');
    }
}
