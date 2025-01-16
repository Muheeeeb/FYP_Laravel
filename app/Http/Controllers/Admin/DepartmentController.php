<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments
     */
    public function index()
    {
        $departments = Department::withCount(['users', 'jobRequests'])->get();
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new department
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created department
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
        ]);

        try {
            Department::create([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.departments.index')
                ->with('success', 'Department created successfully');
        } catch (\Exception $e) {
            Log::error('Department creation error: ' . $e->getMessage());
            return back()->with('error', 'Error creating department')
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified department
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified department
     */
    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        try {
            $department->update([
                'name' => $request->name,
            ]);

            return redirect()->route('admin.departments.index')
                ->with('success', 'Department updated successfully');
        } catch (\Exception $e) {
            Log::error('Department update error: ' . $e->getMessage());
            return back()->with('error', 'Error updating department')
                ->withInput();
        }
    }

    /**
     * Remove the specified department
     */
    public function destroy(Department $department)
    {
        try {
            // Check for related records
            if ($department->users()->exists()) {
                return back()->with('error', 'Cannot delete department with associated users');
            }

            if ($department->jobRequests()->exists()) {
                return back()->with('error', 'Cannot delete department with associated job requests');
            }

            $department->delete();
            return redirect()->route('admin.departments.index')
                ->with('success', 'Department deleted successfully');
        } catch (\Exception $e) {
            Log::error('Department deletion error: ' . $e->getMessage());
            return back()->with('error', 'Error deleting department');
        }
    }
}