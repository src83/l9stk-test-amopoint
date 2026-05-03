<?php

namespace App\Modules\Example\Http\Controllers\Cabinet\Ajax;

use App\Http\Controllers\Controller;
use App\Modules\Example\Http\Requests\Cabinet\StoreEntityRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    public function __construct() {}

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'data' => ['examples'],
        ]);
    }

    public function store(StoreEntityRequest $request): JsonResponse
    {
        $clientId = $request->validated('client_id');

        $data = [
            'client_id' => trim($clientId),
            'status' => 'success',
        ];
        // $message = $this->messageRepository->create($data);

        return response()->json([
            'status' => 'post_created',
        ], 201);
    }
}
