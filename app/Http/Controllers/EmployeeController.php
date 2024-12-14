<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class EmployeeController extends Controller
{
    // Display a listing of employees
    public function index()
    {
        $employees = Employee::all();

        // Return JSON if requested
        if (request()->wantsJson()) {
            return response()->json($employees);
        }

        // Return XML using the helper function
        return response_xml($employees);
    }

    // Store a new employee
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email|max:255',
            'division' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'section' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:255',
        ]);

        $employee = Employee::create($validatedData);

        // Return JSON or XML response
        if (request()->wantsJson()) {
            return response()->json($employee, 201);
        }

        return response_xml($employee);
    }

    // Display the specified employee
    public function show($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        if (request()->wantsJson()) {
            return response()->json($employee);
        }

        return response_xml($employee);
    }

    

    public function update(Request $request, $id)
    {
        // Log the incoming request data for debugging purposes
        Log::info('Request Data:', $request->all());
    
        // Find the employee by ID
        $employee = Employee::find($id);
    
        // If the employee doesn't exist, return a 404 response
        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }
    
        // Validate the request data
        try {
            $validatedData = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|unique:employees,email,' . $id . '|max:255', // Ensure email is unique except for the current employee
                'division' => 'required|string|max:255',
                'position' => 'required|string|max:255',
                'section' => 'required|string|max:255',
                'middlename' => 'nullable|string|max:255',
                'name_extension' => 'nullable|string|max:255',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If validation fails, return a 422 Unprocessable Entity response with the validation errors
            return response()->json(['error' => $e->errors()], 422);
        }
    
        // Update the employee with the validated data
        $employee->update($validatedData);
    
        // Check if the request wants a JSON response
        if ($request->wantsJson()) {
            // Return a success message with the updated employee data in JSON format
            return response()->json([
                'message' => 'Employee updated successfully',
                'data' => $employee,
            ], 200);

        }
    
        // If the request doesn't want JSON, return the employee data as XML
        return response_xml($employee);
    }
    

    // Delete the specified employee
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $employee->delete();

        return response()->json(['message' => 'Employee deleted successfully']);
    }
}
