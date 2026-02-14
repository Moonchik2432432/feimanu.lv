<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ieraksts;
use App\Models\Kategorija;
use App\Models\Komentars;
use App\Models\Lietotajs;

class DataController extends Controller
{
    // =====================
    // LIST (Отображение всех)
    // =====================

    public function showAllIeraksti()
    {
        $ieraksti = Ieraksts::orderBy('ieraksts_id', 'asc')->get();
        return view('AllShow.allIeraksti', compact('ieraksti'));
    }

    public function showAllKategorijas()
    {
        $kategorijas = Kategorija::orderBy('kategorija_id', 'asc')->get();
        return view('AllShow.allKategorijas', compact('kategorijas'));
    }

    public function showAllKomentari()
    {
        $komentari = Komentars::orderBy('komentars_id', 'asc')->get();
        return view('AllShow.allKomentari', compact('komentari'));
    }

    public function showAllLietotaji()
    {
        $lietotaji = Lietotajs::orderBy('lietotajs_id', 'asc')->get();
        return view('AllShow.allLietotaji', compact('lietotaji'));
    }

    // =====================
    // DELETE (Удаление)
    // =====================

    public function deleteIeraksts($id)
    {
        Ieraksts::findOrFail($id)->delete();
        return redirect('/data/allIeraksti')->with('success', 'Ieraksts dzēsts!');
    }

    public function deleteKategorija($id)
    {
        Kategorija::findOrFail($id)->delete();
        return redirect('/data/allKategorijas')->with('success', 'Kategorija dzēsta!');
    }

    public function deleteKomentars($id)
    {
        Komentars::findOrFail($id)->delete();
        return redirect('/data/allKomentari')->with('success', 'Komentārs dzēsts!');
    }

    public function deleteLietotajs($id)
    {
        Lietotajs::findOrFail($id)->delete();
        return redirect('/data/allLietotaji')->with('success', 'Lietotājs dzēsts!');
    }

    // =====================
    // CREATE (Формы)
    // =====================

    public function createIeraksts()
    {
        $kategorijas = Kategorija::orderBy('nosaukums')->get();
        return view('Create.createIeraksts', compact('kategorijas'));
    }

    public function newSubmitIeraksts(Request $request)
    {
        $validated = $request->validate([
            'nosaukums' => 'required|string|max:255',
            'kategorija_id' => 'required|integer',
            'saturs' => 'required|string',
            'status' => 'required|string|max:20',
            'publicets_datums' => 'nullable|date',
            'bilde' => 'nullable|string|max:255',
        ]);

        Ieraksts::create($validated);
        return redirect('/data/allIeraksti')->with('success', 'Ieraksts pievienots!');
    }

    public function createKategorija()
    {
        return view('Create.createKategorija');
    }

    public function newSubmitKategorija(Request $request)
    {
        $validated = $request->validate([
            'nosaukums' => 'required|string|max:100|unique:kategorija,nosaukums',
        ]);

        Kategorija::create($validated);
        return redirect('/data/allKategorijas')->with('success', 'Kategorija pievienota!');
    }

    public function createKomentars()
    {
        $ieraksti = Ieraksts::orderBy('ieraksts_id', 'desc')->get();
        $lietotaji = Lietotajs::orderBy('lietotajs_id', 'asc')->get();
        return view('Create.createKomentars', compact('ieraksti', 'lietotaji'));
    }

    public function newSubmitKomentars(Request $request)
    {
        $validated = $request->validate([
            'ieraksts_id' => 'required|integer',
            'lietotajs_id' => 'required|integer',
            'teksts' => 'required|string|max:2000',
            'izveidots_datums' => 'nullable|date',
        ]);

        Komentars::create($validated);
        return redirect('/data/allKomentari')->with('success', 'Komentārs pievienots!');
    }

    public function createLietotajs()
    {
        return view('Create.createLietotajs');
    }

    public function newSubmitLietotajs(Request $request)
    {
        $validated = $request->validate([
            'vards' => 'required|string|max:100',
            'uzvards' => 'required|string|max:100',
            'epasts' => 'required|email|max:150|unique:lietotajs,epasts',
            'loma' => 'required|string|max:50',
        ]);

        Lietotajs::create($validated);
        return redirect('/data/allLietotaji')->with('success', 'Lietotājs pievienots!');
    }
}
