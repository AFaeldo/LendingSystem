<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use Illuminate\Validation\Rule;

class BorrowerController extends Controller
{
    /**
     * Display a listing of active borrowers.
     */
   public function index(Request $request)
{
    // Exclude archived accounts from the active primary panel grid view
    $query = Borrower::query()->where('status', '!=', 'archived');

    if ($request->filled('q')) {
        $q = $request->q;
        $query->where(fn($qb) => $qb->where('firstname', 'like', '%'.$q.'%')->orWhere('lastname', 'like', '%'.$q.'%'));
    }

    // 🔥 FIX: Pinalitan ang 'lastname' ng 'id' at ginawang 'asc' para magsimula sa #1, #2, #3 pataas
    $borrowers = $query->orderBy('id', 'asc')->paginate(15);

    return view('borrowers.index', compact('borrowers'));
}

    /**
     * Display a listing of archived borrowers.
     */
    public function archiveIndex()
    {
        // Fetch only flagged records specifically inside the vault scope
        $archivedBorrowers = Borrower::where('status', 'archived')
            ->orderBy('lastname')
            ->get();

        return view('borrowers.archive', compact('archivedBorrowers'));
    }

    public function create()
    {
        return view('borrowers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => [
                'required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]+$/',
                // Prevents duplicate firstname + lastname combinations
                Rule::unique('borrowers')->where(function ($query) use ($request) {
                    return $query->where('lastname', $request->lastname);
                }),
            ],
            'lastname' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s]+$/',
            'middlename' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'gender' => 'nullable|in:Male,Female',
            'age' => 'required|integer|min:18|max:150', // Enforces age 18 and up
            'purok' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'contact' => 'nullable|string|max:50|unique:borrowers,contact|regex:/^[0-9\s\-\+\(\)]*$/',
            'organization' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,suspended,banned', // Dinagdag para sa form flexibility
        ], [
            'firstname.required' => 'First name is required',
            'firstname.min' => 'First name must be at least 2 characters',
            'firstname.regex' => 'First name can only contain letters and spaces',
            'firstname.unique' => 'A borrower with this exact first name and last name is already registered',
            'lastname.required' => 'Last name is required',
            'lastname.min' => 'Last name must be at least 2 characters',
            'lastname.regex' => 'Last name can only contain letters and spaces',
            'middlename.regex' => 'Middle name can only contain letters and spaces',
            'age.required' => 'Age is required',
            'age.integer' => 'Age must be a valid number',
            'age.min' => 'Borrower must be 18 years old or above',
            'age.max' => 'Age cannot exceed 150',
            'contact.unique' => 'This contact number is already registered',
            'contact.regex' => 'Contact number format is invalid',
        ]);

        // Kung walang ipinasang status sa pag-create, automatic 'active'
        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        Borrower::create($data);
        return redirect()->route('borrowers.index')->with('success', 'Borrower created successfully');
    }

    public function edit(Borrower $borrower)
    {
        return view('borrowers.edit', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
        $data = $request->validate([
            'firstname' => [
                'required', 'string', 'max:255', 'min:2', 'regex:/^[a-zA-Z\s]+$/',
                // Checks for duplicate name combinations while ignoring the current record
                Rule::unique('borrowers')->where(function ($query) use ($request) {
                    return $query->where('lastname', $request->lastname);
                })->ignore($borrower->id),
            ],
            'lastname' => 'required|string|max:255|min:2|regex:/^[a-zA-Z\s]+$/',
            'middlename' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]*$/',
            'gender' => 'nullable|in:Male,Female',
            'age' => 'required|integer|min:18|max:150', // Enforces age 18 and up
            'purok' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'contact' => 'nullable|string|max:50|unique:borrowers,contact,' . $borrower->id . '|regex:/^[0-9\s\-\+\(\)]*$/',
            'organization' => 'nullable|string|max:255',
            'status' => 'required|string|in:active,inactive,suspended,banned', // <--- DITO ANG KULANG! Dinagdag ang validation rule
        ], [
            'firstname.required' => 'First name is required',
            'firstname.min' => 'First name must be at least 2 characters',
            'firstname.regex' => 'First name can only contain letters and spaces',
            'firstname.unique' => 'A borrower with this exact first name and last name is already registered',
            'lastname.required' => 'Last name is required',
            'lastname.min' => 'Last name must be at least 2 characters',
            'lastname.regex' => 'Last name can only contain letters and spaces',
            'middlename.regex' => 'Middle name can only contain letters and spaces',
            'age.required' => 'Age is required',
            'age.integer' => 'Age must be a valid number',
            'age.min' => 'Borrower must be 18 years old or above',
            'age.max' => 'Age cannot exceed 150',
            'contact.unique' => 'This contact number is already registered',
            'contact.regex' => 'Contact number format is invalid',
        ]);

        $borrower->update($data);
        return redirect()->route('borrowers.index')->with('success', 'Borrower updated successfully');
    }

    /**
     * Archive the specified borrower profile.
     */
    public function archive(Borrower $borrower)
    {
        $borrower->update(['status' => 'archived']);
        return redirect()->route('borrowers.index')->with('success', 'Borrower profile archived successfully');
    }

    /**
     * Restore a borrower out of archive records back into active operational listings.
     */
    public function restore(Borrower $borrower)
    {
        $borrower->update(['status' => 'active']);
        return redirect()->route('borrowers.archive.index')->with('success', 'Borrower profile restored to active records successfully');
    }

    /**
     * Remove the specified borrower from storage permanently.
     */
    public function destroy(Borrower $borrower)
    {
        $borrower->delete();
        return back()->with('success', 'Borrower removed permanently');
    }
}
