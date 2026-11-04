<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * POST /api/consultations - "Book a Consultation" form.
     * Invalid input returns 422 with an "errors" object (Laravel default).
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'],
            'company' => ['nullable', 'string', 'max:120'],
            'service' => ['nullable', 'string', 'max:80'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        Consultation::create($data);

        return response()->json([
            'message' => 'Thanks! We will get back to you within one business day.',
        ], 201);
    }
}
