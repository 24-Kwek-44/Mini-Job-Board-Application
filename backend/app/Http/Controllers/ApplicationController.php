<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    // Applicant: Apply to a job
    public function store(Request $request)
    {
        if ($request->user()->role !== 'applicant') {
            return response()->json(['message' => 'Only applicants can apply'], 403);
        }

        $request->validate([
            'job_id' => 'required|exists:job_listings,id',
            'message' => 'required|string',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Bonus: File validation
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
            // Ensure storage:link is run
        }

        $application = Application::create([
            'job_id' => $request->job_id,
            'applicant_id' => $request->user()->id,
            'message' => $request->message,
            'resume_path' => $resumePath,
        ]);

        // Load relationships for the email
        $application->load(['job.employer', 'applicant']);

        // Send Email Notification
        if ($application->job->employer && $application->job->employer->email) {
            \Illuminate\Support\Facades\Mail::to($application->job->employer->email)
                ->send(new \App\Mail\ApplicationReceived($application));
        }

        return response()->json($application, 201);
    }

    // Employer: View applications for their job
    public function index(Request $request, Job $job)
    {
        // Auth check: User must be employer AND owner of the job
        if ($request->user()->role !== 'employer' || $job->employer_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $job->applications()->with('applicant:id,name,email')->latest()->get();
    }
}
