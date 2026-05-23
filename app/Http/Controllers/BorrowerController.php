<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;

class BorrowerController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrower::query();
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($qb) => $qb->where('firstname','like','%'.$q.'%')->orWhere('lastname','like','%'.$q.'%'));
        }
        $borrowers = $query->orderBy('lastname')->paginate(15);
        return view('borrowers.index', compact('borrowers'));
    }

    public function create()
    {
        return view('borrowers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'purok' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact' => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
        ]);
        Borrower::create($data);
        return redirect()->route('borrowers.index')->with('success','Borrower created');
    }

    public function edit(Borrower $borrower)
    {
        return view('borrowers.edit', compact('borrower'));
    }

    public function update(Request $request, Borrower $borrower)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'gender' => 'nullable|in:Male,Female,Other',
            'purok' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'contact' => 'nullable|string|max:50',
            'organization' => 'nullable|string|max:255',
        ]);
        $borrower->update($data);
        return redirect()->route('borrowers.index')->with('success','Borrower updated');
    }

    public function destroy(Borrower $borrower)
    {
        $borrower->delete();
        return back()->with('success','Borrower removed');
    }
}
