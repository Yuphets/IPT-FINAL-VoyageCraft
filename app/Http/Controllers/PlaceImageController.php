<?php

namespace App\Http\Controllers;

use App\Services\UnsplashImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class PlaceImageController extends Controller
{
    public function search(Request $request, UnsplashImageService $unsplash): JsonResponse
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'min:2', 'max:255'],
        ]);

        try {
            return response()->json([
                'data' => $unsplash->search($validated['query']),
            ]);
        } catch (RuntimeException) {
            return response()->json([
                'message' => 'Place photo search is unavailable until Unsplash is configured.',
            ], 503);
        }
    }
}
