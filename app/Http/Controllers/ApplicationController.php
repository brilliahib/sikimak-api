<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Application::where('user_id', auth()->id());

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 10);
        $applications = $query->latest()->paginate($perPage);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Applications retrieved successfully',
            ],
            'data' => $applications->items(),
            'pagination' => [
                'current_page' => $applications->currentPage(),
                'per_page' => $applications->perPage(),
                'total' => $applications->total(),
                'last_page' => $applications->lastPage(),
            ],
        ], 200);
    }

    public function timeline()
    {
        $applications = Application::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Timeline retrieved successfully',
            ],
            'data' => $applications,
        ], 200);
    }

    public function summary()
    {
        $userId = auth()->id();

        $totalApplications = Application::where('user_id', $userId)->count();
        $totalNotSubmitted = Application::where('user_id', $userId)
            ->where('submitted_status', 'not submitted')
            ->count();
        $totalAccepted = Application::where('user_id', $userId)
            ->where('approval_status', 'accepted')
            ->count();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Summary applications retrieved successfully',
            ],
            'data' => [
                'total_applications' => $totalApplications,
                'total_not_submitted' => $totalNotSubmitted,
                'total_accepted' => $totalAccepted,
            ],
        ], 200);
    }

    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $application = Application::create([
            'user_id' => auth()->id(),
            ...$request->validated(),
        ]);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 201,
                'message' => 'Application created successfully',
            ],
            'data' => $application,
        ], 201);
    }

    public function show($id): JsonResponse
    {
        $application = Application::where('user_id', auth()->id())
            ->findOrFail($id);

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Application retrieved successfully',
            ],
            'data' => $application,
        ], 200);
    }

    public function update(UpdateApplicationRequest $request, $id): JsonResponse
    {
        $application = Application::where('user_id', auth()->id())
            ->findOrFail($id);

        $application->update($request->validated());

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Application updated successfully',
            ],
            'data' => $application,
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        $application = Application::where('user_id', auth()->id())
            ->findOrFail($id);

        $application->delete();

        return response()->json([
            'meta' => [
                'status' => 'success',
                'statusCode' => 200,
                'message' => 'Application deleted successfully',
            ],
            'data' => null,
        ], 200);
    }
}
