<?php

namespace App\Http\Controllers;

use App\Models\Flashcard;
use Illuminate\Http\Request;

class FlashcardController extends Controller
{
    public function index()
    {
        // $flashcards = Flashcard::latest()->paginate(10);
        // return view('flashcards.index', compact('flashcards'));
        return view('flashcards.index');

    }

    public function create()
    {
        return view('flashcards.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'word' => 'required|string',
            'reading' => 'nullable|string',
            'meaning' => 'nullable|string',
            'example' => 'nullable|string',
            'synonym' => 'nullable|string',
            'antonym' => 'nullable|string',
            'usage' => 'nullable|string',
        ]);

        Flashcard::create($data);
        return redirect()->route('flashcards.index')->with('success', 'Flashcard đã được lưu');
    }

    public function show(Flashcard $flashcard)
    {
        return view('flashcards.show', compact('flashcard'));
    }

    public function destroy(Flashcard $flashcard)
    {
        $flashcard->delete();
        return redirect()->route('flashcards.index')->with('success', 'Đã xoá flashcard');
    }
}
