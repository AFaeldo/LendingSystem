<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Borrower;
use App\Models\InventoryItem;
use App\Models\LendingTransaction;
use App\Models\ReturnTransaction;

class ReportController extends Controller
{
    public function index()
{
    // Kukunin ang mga automated reports para sa table
    $reports = Report::with('generator')->orderBy('generated_at', 'desc')->paginate(20);

    // Kakalkulahin ang mga stats counters para sa dashboard cards
    $stats = [
        'total_logs' => Report::count(),
        'lending_runs' => Report::where('type', 'lendings')->count(),
        'borrower_runs' => Report::where('type', 'borrowers')->count(),
        'last_run' => Report::orderBy('generated_at', 'desc')->first()?->generated_at,
    ];

    return view('reports.index', compact('reports', 'stats'));
}

    public function show(Report $report)
    {
        $report->load('generator');

        // Maghahanda tayo ng lalagyanan ng totoong records mula sa database
        $reportData = collect();

        // Kakalkulahin at kukunin ang huling 50 aktwal na records base sa "Type" ng report para i-display bilang organisadong listahan
        switch ($report->type) {
            case 'borrowers':
                $reportData = Borrower::orderBy('created_at', 'desc')->take(50)->get();
                break;

            case 'lendings':
            case 'overdue':
                $query = LendingTransaction::with(['borrower', 'item'])->orderBy('created_at', 'desc');
                if ($report->type === 'overdue') {
                    $query->where('status', 'active')->where('due_at', '<', $report->generated_at->toDateString());
                }
                $reportData = $query->take(50)->get();
                break;

            case 'returns':
                $reportData = ReturnTransaction::with(['lendingTransaction.borrower', 'lendingTransaction.item'])
                    ->orderBy('created_at', 'desc')
                    ->take(50)
                    ->get();
                break;

            case 'items':
            case 'inventory':
                $reportData = InventoryItem::orderBy('name', 'asc')->get();
                break;
        }

        return view('reports.show', compact('report', 'reportData'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|in:borrowers,items,lendings,returns,overdue,inventory',
            'remarks' => 'nullable|string|max:1000',
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
                $totalRecords = InventoryItem::sum('available') ?? 0;
                break;
        }

        $report = Report::create([
            'type' => $data['type'],
            'generated_by' => Auth::id(),
            'generated_at' => now(),
            'total_records' => $totalRecords,
            'meta' => $request->input('remarks'),
        ]);

        return redirect()->route('reports.show', $report)->with('success', 'Report generated successfully');
    }
}
