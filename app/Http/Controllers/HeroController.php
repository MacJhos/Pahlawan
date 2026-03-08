<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;
use App\Models\Monument;
use App\Models\Relic;

class HeroController extends Controller
{
    /**
     * Halaman Home
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $heroOfTheDay = Hero::where('name', 'LIKE', '%Soekarno%')->first();

        $query = Hero::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%')
                  ->orWhere('hometown', 'like', '%' . $search . '%');
            });
            $heroes = $query->latest()->get();
        } else {
            $heroes = $query->get();
        }

        if ($request->ajax()) {
            return response()->json([
                'heroes' => $heroes,
                'total'  => $heroes->count(),
            ]);
        }

        return view('layout.home', [
            'heroes' => $heroes,
            'soekarno' => $heroOfTheDay
        ]);
    }

    /**
     * Halaman Galeri
     */
    public function galeri(Request $request)
    {
        $search = $request->input('search');

        $heroes = Hero::query()
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->get()
            ->map(function($item) {
                $item->type = 'hero';
                return $item;
            });

        $monuments = Monument::query()
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->get()
            ->map(function($item) {
                $item->type = 'monument';
                $item->hometown = $item->location;
                return $item;
            });

        $relics = Relic::query()
            ->when($search, fn($q) => $q->where('name', 'like', '%' . $search . '%'))
            ->get()
            ->map(function($item) {
                $item->type = 'relic';
                $item->hometown = $item->origin;
                return $item;
            });

        $allMedia = $heroes->concat($monuments)->concat($relics)->shuffle();

        if ($request->ajax()) {
            return response()->json([
                'heroes' => $allMedia,
                'total'  => $allMedia->count(),
            ]);
        }

        return view('layout.galeri', ['allMedia' => $allMedia]);
    }

    /**
     * Halaman Detail
     */
    public function show($type, $id_or_slug)
    {
        switch ($type) {
            case 'hero':
                $data = Hero::where('slug', $id_or_slug)->firstOrFail();
                return view('layout.details.hero-detail', compact('data'));

            case 'monument':
                $data = Monument::findOrFail($id_or_slug);
                return view('layout.details.monument', compact('data'));

            case 'relic':
                $data = Relic::findOrFail($id_or_slug);
                return view('layout.details.relic', compact('data'));

            default:
                abort(404);
        }
    }

    public function about()
    {
        return view('layout.about');
    }
}
