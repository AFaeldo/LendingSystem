<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::orderBy('generated_at','desc')->paginate(20);
        return view('reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        $report->load('generator');
        return view('reports.show', compact('report'));
    }
}
