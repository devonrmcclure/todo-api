<?php

namespace App\Http\Controllers;

use App\Folder;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Resources\FolderCollection;
use App\Http\Resources\Folder as FolderResource;

class FolderController extends Controller
{
    public function index(): FolderCollection
    {
        $folders = QueryBuilder::for(Folder::class)
            ->where('owner_id', auth()->user()->id)
            ->allowedIncludes(['tasks'])
            ->jsonPaginate();

        return new FolderCollection($folders);
    }

    public function show(Folder $folder)
    {
        if ($folder->owner_id == auth()->user()->id) {
            return new FolderResource($folder);
        }

        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }

    public function store(): JsonResponse
    {
        $attributes = request()->validate([
            'name' => 'required'
        ]);

        $attributes['owner_id'] = auth()->user()->id;

        $folder = Folder::create($attributes);

        return response()->json($folder)->setStatusCode(201);
    }

    public function update(Folder $folder): JsonResponse
    {
        if ($folder->owner_id == auth()->user()->id) {
            $attributes = request()->validate([
                'name' => 'string',
            ]);

            $folder->update($attributes);

            return response()->json($folder);
        }
        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }

    public function destroy(Folder $folder): JsonResponse
    {
        if ($folder->owner_id == auth()->user()->id) {
            $folder->delete();

            return response()->json([
                'message' => 'Folder deleted successfully'
            ])->setStatusCode(200);
        }

        return response()->json(['message' => 'Unauthorized'])->setStatusCode(401);
    }
}
