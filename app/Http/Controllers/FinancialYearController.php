<?php

namespace App\Http\Controllers;

use App\Models\FinancialYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinancialYearController extends Controller
{
    /**
     * Display a listing of the financial years.
     */
    public function index()
    {
        $financialYears = FinancialYear::orderBy('start_date', 'desc')->get();
        return view('financial_years.index', compact('financialYears'));
    }

    /**
     * Show the form for creating a new financial year.
     */
    public function create()
    {
        return view('financial_years.create');
    }

    /**
     * Store a newly created financial year in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicable_year' => 'required|string|max:20|unique:financial_years,applicable_year',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:Active,Inactive',
        ]);

        $validated['created_by'] = Auth::user()->name ?? 'System';

        FinancialYear::create($validated);

        return redirect()->route('financial-years.index')->with('success', 'Financial Year created successfully.');
    }

    /**
     * Show the form for editing the specified financial year.
     */
    public function edit(FinancialYear $financialYear)
    {
        return view('financial_years.edit', compact('financialYear'));
    }

    /**
     * Update the specified financial year in storage.
     */
    public function update(Request $request, FinancialYear $financialYear)
    {
        $validated = $request->validate([
            'year_name' => 'required|string|max:20|unique:financial_years,year_name,' . $financialYear->id,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:Active,Inactive',
        ]);

        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $financialYear->update($validated);

        return redirect()->route('financial-years.index')->with('success', 'Financial Year updated successfully.');
    }

    /**
     * Remove the specified financial year from storage.
     */
    public function destroy(FinancialYear $financialYear)
    {
        $financialYear->delete();
        return redirect()->route('financial-years.index')->with('success', 'Financial Year deleted successfully.');
    }
}
