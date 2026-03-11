<?php

namespace App\Http\Controllers;

use App\Models\BlockReason;

class RulesController extends Controller
{
    public function index()
    {
        $reasons = BlockReason::where('is_active', 1)
            ->orderBy('id')
            ->get();

        return view('rules.index', compact('reasons'));
    }
}