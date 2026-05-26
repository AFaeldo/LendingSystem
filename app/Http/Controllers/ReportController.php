<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Borrower;
use App\Models\InventoryItem;
use App\Models\LendingTransaction;
use App\Models\ReturnTransaction;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::orderBy('generated_at', 'desc')->paginate(20);
        return view('reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load('generator');
        return view('reports.show', compact('report'));
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'borrowers');
        return view('reports.create', compact('type'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:borrowers,items,lendings,returns,overdue,inventory',
        ], [
            'type.required' => 'Report type is required',
            'type.in' => 'Invalid report type selected',
        ]);

        $totalRecords = 0;

        switch ($data['type']) {
            case 'borrowers':
                $totalRecords = Borrower::count();
                break;
            case 'items':
                $totalRecords = InventoryItem::count();
                break;
            case 'lendings':
                $totalRecords = LendingTransaction::count();
                break;
            case 'returns':
                $totalRecords = ReturnTransaction::count();
                break;
            case 'overdue':
                $totalRecords = LendingTransaction::where('status', 'active')
                    ->where('due_at', '<', now()->toDateString())
                    ->count();
                break;
            case 'inventory':
                $totalRecords = InventoryItem::sum('available');
                break;
        }

        $data['generated_by'] = auth()->id();
        $data['generated_at'] = now();
        $data['total_records'] = $totalRecords;

        $report = Report::create($data);
        return redirect()->route('reports.show', $report)->with('success', 'Report generated successfully');
    }
}

