<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockReason;
use Illuminate\Http\Request;

class AdminBlockReasonController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));

        $reasons = BlockReason::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->orderBy('id')
            ->get();

        return view('admin.block_reasons.index', compact('reasons', 'q'));
    }

    public function create()
    {
        return view('admin.block_reasons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        BlockReason::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_active' => 1,
        ]);

        return redirect()
            ->route('admin.block_reasons')
            ->with('success', 'Iemesls pievienots');
    }

    public function edit($id)
    {
        $reason = BlockReason::findOrFail($id);

        return view('admin.block_reasons.edit', compact('reason'));
    }

    public function update(Request $request, $id)
    {
        $reason = BlockReason::findOrFail($id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $reason->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'is_active' => $data['is_active'],
        ]);

        return redirect()
            ->route('admin.block_reasons')
            ->with('success', 'Iemesls atjaunināts');
    }

    public function destroy($id)
    {
        $reason = BlockReason::findOrFail($id);

        if ($reason->userBlocks()->exists()) {
            $reason->update([
                'is_active' => 0,
            ]);

            return redirect()
                ->route('admin.block_reasons')
                ->with('error', 'Iemesls jau tiek izmantots. Tas netika izdzēsts, bet tika izslēgts.');
        }

        $reason->delete();

        return redirect()
            ->route('admin.block_reasons')
            ->with('success', 'Iemesls izdzēsts');
    }
}