<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hero;

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
                'heroes'        => $heroes,
                'total'         => $heroes->count(),
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

        $query = Hero::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $heroes = $query->get();

        if ($request->ajax()) {
            return response()->json([
                'heroes' => $heroes,
                'total'  => $heroes->count(),
            ]);
        }

        return view('layout.galeri', compact('heroes'));
    }

    /**
     * Halaman Detail
     */
    public function show($slug)
    {
        $hero = Hero::where('slug', $slug)->firstOrFail();
        return view('layout.hero-detail', compact('hero'));
    }

    public function about()
    {
        return view('layout.about');
    }
}
