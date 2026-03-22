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
            ->paginate(10)
            ->appends($request->query());

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
        ]);

        $reason->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
        ]);

        return redirect()
            ->route('admin.block_reasons')
            ->with('success', 'Iemesls atjaunināts');
    }

    public function destroy($id)
    {
        $reason = BlockReason::findOrFail($id);

        $hasActiveBlocks = $reason->userBlocks()
            ->whereNull('unblocked_at')
            ->where('blocked_until', '>', now())
            ->exists();

        if ($hasActiveBlocks) {
            return redirect()
                ->route('admin.block_reasons')
                ->with('error', 'Šo iemeslu nevar dzēst, jo tas tiek izmantots aktīvā bloķēšanā.');
        }

        $reason->userBlocks()->delete();
        $reason->delete();

        return redirect()
            ->route('admin.block_reasons')
            ->with('success', 'Iemesls un saistītā bloķēšanas vēsture izdzēsti');
    }
}