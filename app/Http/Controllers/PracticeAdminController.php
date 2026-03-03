<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use App\Models\PracticeExercise;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PracticeAdminController extends Controller
{
    public function index(): View
    {
        $practices = Practice::latest()->get();
        return view('admin.practice.index', compact('practices'));
    }

    public function create(): View
    {
        return view('admin.practice.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',   
            'github_link' => 'nullable|url',    
            'tags' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        // Handle tags: convert comma-separated string to array
        if (!empty($validatedData['tags']) && is_string($validatedData['tags'])) {
            $validatedData['tags'] = array_map('trim', explode(',', $validatedData['tags']));
        } else {
            $validatedData['tags'] = null;
        }

        Practice::create($validatedData);

        return redirect()->route('admin.practice.index')->with('success', 'Proyek latihan berhasil ditambahkan!');
    }

    public function edit(Practice $practice): View
    {
        return view('admin.practice.edit', compact('practice'));
    }

    public function update(Request $request, Practice $practice): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'github_link' => 'nullable|string',
            'tags' => 'nullable|string',
            'content' => 'nullable|string',
        ]);

        if ($validatedData['title'] !== $practice->title) {
            $validatedData['slug'] = Str::slug($validatedData['title']);
        }

        if (!empty($validatedData['tags'])) {
            $validatedData['tags'] = array_map('trim', explode(',', $validatedData['tags']));
        } else {
            $validatedData['tags'] = null;
        }

        $practice->update($validatedData);

        return redirect()->route('admin.practice.index')->with('success', 'Proyek latihan berhasil diperbarui!');
    }

    public function destroy(Practice $practice): RedirectResponse
    {
        $practice->delete();
        return redirect()->route('admin.practice.index')->with('success', 'Proyek latihan berhasil dihapus!');
    }

    /**
     * Show the form for managing exercises for a practice
     */
    public function manageExercises(Practice $practice): View
    {
        $practice->load('exercises');
        return view('admin.practice.exercises', compact('practice'));
    }

    /**
     * Store a new exercise for a practice
     */
    public function storeExercise(Request $request, Practice $practice): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|in:python,html,javascript', // <-- Validasi bahasa baru
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'starter_code' => 'nullable|string',
            'solution_code' => 'nullable|string',
            'hints' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        // Get the next order number
        $order = $practice->exercises()->count() + 1;
        $validatedData['order'] = $order;
        $validatedData['is_completed'] = false;

        $practice->exercises()->create($validatedData);

        return redirect()->route('admin.practice.exercises.manage', $practice->id)
            ->with('success', 'Exercise berhasil ditambahkan!');
    }

    /**
     * Show the form for editing an exercise
     */
    public function editExercise(Practice $practice, PracticeExercise $exercise): View
    {
        return view('admin.practice.exercise-edit', compact('practice', 'exercise'));
    }

    /**
     * Update an exercise
     */
    public function updateExercise(Request $request, Practice $practice, PracticeExercise $exercise): RedirectResponse
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|in:python,html,javascript', // <-- Validasi bahasa baru
            'description' => 'nullable|string',
            'instructions' => 'nullable|string',
            'starter_code' => 'nullable|string',
            'solution_code' => 'nullable|string',
            'hints' => 'nullable|string',
            'difficulty' => 'required|in:easy,medium,hard',
        ]);

        $exercise->update($validatedData);

        return redirect()->route('admin.practice.exercises.manage', $practice->id)
            ->with('success', 'Exercise berhasil diperbarui!');
    }

    /**
     * Delete an exercise
     */
    public function destroyExercise(Practice $practice, PracticeExercise $exercise): RedirectResponse
    {
        $exercise->delete();

        return redirect()->route('admin.practice.exercises.manage', $practice->id)
            ->with('success', 'Exercise berhasil dihapus!');
    }
}