<?php

namespace App\Http\Controllers;

use App\Models\Marketing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarketingController extends Controller
{
    public function index()
    {
        $marketings = Marketing::all();
        return view('pages.marketing.index', compact('marketings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = $request->file('image')->store('slides', 'public');

        Marketing::create([
            'name'  => $request->name,
            'image' => $path,
        ]);

        return redirect()->back()->with('success', 'Slide ajouté avec succès.');
    }

   

    public function update(Request $request, $id)
    {
        $marketing = Marketing::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Supprimer l’ancienne image
            Storage::disk('public')->delete($marketing->image);

            // Stocker la nouvelle image
            $path = $request->file('image')->store('slides', 'public');
            $marketing->image = $path;
        }

        $marketing->name = $request->name;
        $marketing->save();

        return redirect()->route('marketings.index')->with('success', 'Slide mis à jour.');
    }

    public function destroy($id)
    {
        $marketing = Marketing::findOrFail($id);

        // Supprimer l’image
        Storage::disk('public')->delete($marketing->image);

        $marketing->delete();

        return redirect()->back()->with('success', 'Slide supprimé.');
    }
}
