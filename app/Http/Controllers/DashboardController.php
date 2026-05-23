<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrower;
use App\Models\InventoryItem;
use App\Models\LendingTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBorrowers = Borrower::count();
        $totalItems = InventoryItem::count();
        $activeLendings = LendingTransaction::where('status','active')->count();
        $returned = LendingTransaction::where('status','returned')->count();
        $overdue = LendingTransaction::where('status','active')
            ->where('due_at', '<', now())
            ->count();

        $recentTransactions = LendingTransaction::with(['borrower','item'])
            ->orderBy('borrowed_at','desc')
            ->limit(5)
            ->get();

        $inventoryOverview = InventoryItem::orderBy('available','asc')->limit(5)->get();

        return view('dashboard', compact('totalBorrowers','totalItems','activeLendings','returned','overdue','recentTransactions','inventoryOverview'));
    }
}
