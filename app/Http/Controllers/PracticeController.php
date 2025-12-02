<?php

namespace App\Http\Controllers;

use App\Models\Practice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PracticeController extends Controller
{
    /**
     * Display a listing of practice projects with filtering and search.
     */
    public function index(Request $request)
    {
        // Start query
        $query = Practice::query();

        // Get all unique categories for filters
        $allCategories = Practice::distinct()
            ->whereNotNull('category')
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();

        // Get popular tags (you can customize this logic)
        $popularTags = ['Beginner', 'Intermediate', 'Advanced'];

        // Combine filters: All + Categories + Popular Tags
        $allFilters = array_merge(['All'], $allCategories, $popularTags);

        // Get filter and search parameters
        $requestedFilter = $request->query('filter');
        $searchQuery = $request->query('search');
        $activeFilter = 'All';

        // Apply category/tag filter
        if ($requestedFilter && $requestedFilter !== 'All' && in_array($requestedFilter, $allFilters)) {
            $query->where(function ($q) use ($requestedFilter) {
                $q->where('category', $requestedFilter)
                  ->orWhereJsonContains('tags', $requestedFilter);
            });
            $activeFilter = $requestedFilter;
        }

        // Apply search filter
        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('title', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhereJsonContains('tags', $searchQuery);
            });
        }

        // Get filtered practices
        $filteredPractices = $query->latest()->get();

        // Return view with data
        return view('practice.index', compact(
            'filteredPractices',
            'allFilters',
            'activeFilter',
            'searchQuery'
        ));
    }

    /**
     * Display the specified practice project.
     */
    public function show($slug)
    {
        // Find practice by slug and load exercises
        $practice = Practice::where('slug', $slug)
            ->with('exercises')
            ->firstOrFail();

        return view('practice.show', compact('practice'));
    }

    /**
     * Start an exercise
     */
    public function startExercise($practiceSlug, $exerciseId)
    {
        $practice = Practice::where('slug', $practiceSlug)->firstOrFail();
        $exercise = \App\Models\PracticeExercise::where('practice_id', $practice->id)
            ->where('id', $exerciseId)
            ->firstOrFail();

        return view('practice.exercise', compact('practice', 'exercise'));
    }
}


