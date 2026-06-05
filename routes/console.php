<?php

use App\Notifications\NewLendingNotification;
use App\Models\User;
use Illuminate\Support\Facades\Schedule;
use App\Models\Report;
use App\Models\LendingTransaction;
use App\Models\Borrower;

Schedule::call(function () {

    // 1. Compile Lending Data
    $totalLendingsToday = LendingTransaction::count();
    Report::create([
        'type' => 'lendings',
        'generated_by' => null,
        'generated_at' => now(),
        'total_records' => $totalLendingsToday,
        'meta' => "Automated Daily Ledger compiled before date rollover.",
    ]);

    // 2. Compile Borrower Data
    $totalBorrowers = Borrower::count();
    Report::create([
        'type' => 'borrowers',
        'generated_by' => null,
        'generated_at' => now(),
        'total_records' => $totalBorrowers,
        'meta' => "Automated Daily Ledger updated for user profiles.",
    ]);

    // 🔥 DITO NATIN IPAPASOK ANG NOTIFICATION ENGINE:
    // Kukunin natin ang lahat ng Admin sa system para padalhan ng abiso
    $admins = User::where('role', 'admin')->get();

    foreach ($admins as $admin) {
        $admin->notify(new NewLendingNotification(
            "Midnight ledger completed. System auto-generated new data reports for Lending transactions and Borrower activities.",
            "success"
        ));
    }

})->dailyAt('23:59');
