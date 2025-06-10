<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::with('department')->get();
            return view('admin.users.index', compact('users'));
        } catch (\Exception $e) {
            Log::error('Error fetching users: ' . $e->getMessage());
            return back()->with('error', 'Error loading users.');
        }
    }

    public function create()
    {
        try {
            $departments = Department::all();
            $roles = [
                'admin' => 'Admin',
                'hod' => 'HOD',
                'dean' => 'Dean',
                'hr' => 'HR'
            ];
            return view('admin.users.create', compact('departments', 'roles'));
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return back()->with('error', 'Error loading create form.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/',
            ],
            'role' => 'required|in:admin,hod,dean,hr',
            'department_id' => 'required_if:role,hod|nullable|exists:departments,id',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (!@#$%^&*)',
        ]);

        Log::info('Store User Request:', $request->all());

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'is_admin' => $request->role === 'admin',
                'is_active' => true,
                'department_id' => $request->role === 'hod' ? $request->department_id : null,
            ]);

            if ($request->role === 'hod') {
                Department::where('hod_id', $user->id)->update(['hod_id' => null]);
                Department::where('id', $request->department_id)->update(['hod_id' => $user->id]);
            }

            DB::commit();
            Log::info('User created successfully:', $user->toArray());

            return redirect()->route('admin.users.index')
                ->with('success', 'User created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Error creating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(User $user)
    {
        try {
            $departments = Department::all();
            $roles = [
                'admin' => 'Admin',
                'hod' => 'HOD',
                'dean' => 'Dean',
                'hr' => 'HR'
            ];
            return view('admin.users.edit', compact('user', 'departments', 'roles'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return back()->with('error', 'Error loading edit form.');
        }
    }

    public function update(Request $request, User $user)
    {
        Log::info('Update User Request:', [
            'user_id' => $user->id,
            'data' => $request->all()
        ]);

        try {
            DB::beginTransaction();

            // Base validation rules
            $validationRules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'role' => 'required|string|in:admin,hod,dean,hr',
            ];

            // Add department_id validation only for HOD role
            if ($request->role === 'hod') {
                $validationRules['department_id'] = 'required|exists:departments,id';
            }

            $validated = $request->validate($validationRules);

            // Base user data
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'is_admin' => $validated['role'] === 'admin',
                'department_id' => null, // Default to null
            ];

            // Set department_id only for HOD
            if ($validated['role'] === 'hod') {
                $userData['department_id'] = $request->department_id;
            }

            // Handle password update if provided
            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'required|string|min:8|confirmed'
                ]);
                $userData['password'] = Hash::make($request->password);
            }

            // Handle HOD department relationships
            if ($user->role !== $validated['role'] || 
                ($validated['role'] === 'hod' && $user->department_id !== $request->department_id)) {
                
                // Remove old HOD association
                if ($user->role === 'hod') {
                    Department::where('hod_id', $user->id)->update(['hod_id' => null]);
                }

                // Set new HOD association
                if ($validated['role'] === 'hod') {
                    Department::where('id', $request->department_id)->update(['hod_id' => $user->id]);
                }
            }

            Log::info('Before update:', $user->toArray());
            $user->update($userData);
            Log::info('After update:', $user->fresh()->toArray());

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', 'User updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating user:', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->with('error', 'Error updating user: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();
            
            if ($user->role === 'hod') {
                Department::where('hod_id', $user->id)->update(['hod_id' => null]);
            }
            
            $user->delete();
            
            DB::commit();
            Log::info('User deleted successfully', ['user_id' => $user->id]);

            return redirect()->route('admin.users.index')
                ->with('success', 'User deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting user: ' . $e->getMessage());
            
            return back()->with('error', 'Error deleting user. Please try again.');
        }
    }

    public function show(User $user)
    {
        try {
            return view('admin.users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('Error showing user: ' . $e->getMessage());
            return back()->with('error', 'Error loading user details.');
        }
    }

    public function toggleStatus(User $user)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'is_active' => !$user->is_active
            ]);

            DB::commit();
            Log::info('User status toggled', [
                'user_id' => $user->id,
                'new_status' => $user->is_active ? 'active' : 'inactive'
            ]);

            return back()->with('success', 'User status updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error toggling user status: ' . $e->getMessage());
            
            return back()->with('error', 'Error updating user status');
        }
    }

    public function resetPassword(User $user)
    {
        try {
            DB::beginTransaction();

            $newPassword = 'password123';
            $user->update([
                'password' => Hash::make($newPassword)
            ]);

            DB::commit();
            Log::info('User password reset', ['user_id' => $user->id]);

            return back()->with('success', 'Password reset successfully to: ' . $newPassword);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error resetting password: ' . $e->getMessage());
            
            return back()->with('error', 'Error resetting password');
        }
    }
}