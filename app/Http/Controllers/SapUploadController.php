<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SapUploadController extends Controller
{
    public function index()
    {
        return view('sapuploads.index');
    }

    public function store(Request $request)
    {
        // dd($request);
        // 1. Validation Error (Triggered automatically by Laravel)
        $request->validate([
            'sap_file' => 'required|mimes:xlsx,xls|max:10240',
        ], [
            'sap_file.mimes' => 'Invalid file format. Please upload an Excel file (.xlsx, .xls)',
            'sap_file.required' => 'Please select a file first.',
        ]);
        try {
            // ... process excel logic ...

            // 2. Success Toast
            return redirect()->route('dashboard')->with('success', 'SAP Data Imported Successfully!');

        } catch (\Exception $e) {
            // 3. System/Processing Error Toast
            return back()->with('error', 'Error: '.$e->getMessage());
        }
    }
}
