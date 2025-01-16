<?php

namespace App\Http\Controllers;

use App\Models\JobTitle;
use Illuminate\Http\Request;

class JobTitleController extends Controller
{
    public function index()
    {
        $jobTitles = JobTitle::all();
        return response()->json($jobTitles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:job_titles,title',
        ]);

        $jobTitle = JobTitle::create([
            'title' => $validated['title'],
            'is_default' => false
        ]);

        return response()->json($jobTitle, 201);
    }
}