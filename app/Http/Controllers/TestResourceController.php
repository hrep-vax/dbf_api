<?php

namespace App\Http\Controllers;

use App\Helpers\ApiErrorResponse;
use App\Http\Requests\StoreTestResource;
use App\Http\Requests\UpdateTestRequest;
use App\Models\TestResource;
use Illuminate\Http\Response;

class TestResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $resources = TestResource::all();
        return response(['resources' => $resources]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTestResource $request
     * @return Response
     */
    public function store(StoreTestResource $request)
    {
        $newResource = TestResource::create($request->all());
        return response(['newResource' => $newResource], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id)
    {
        $resource = $this->findResourceById($id);

        return response(['resource' => $resource], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTestRequest $request
     * @param int $id
     * @return Response
     */
    public function update(UpdateTestRequest $request, int $id)
    {
        $resource = $this->findResourceById($id);

        $resource->update($request->all());

        return response(['updatedResources' => $resource], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id)
    {
        $deletedResource = $this->findResourceById($id);

        $deletedResource->delete();

        return response(['deletedResource' => $deletedResource], 200);
    }

    private function findResourceById($id)
    {
        $resource = TestResource::find($id);

        if (!$resource) {
            $message = ['id' => 'Could not find resource with given id.'];
            return throw ApiErrorResponse::createErrorResponse('Resource not found', $message, 404, ApiErrorResponse::$RESOURCE_NOT_FOUND_CODE);
        }

        return $resource;
    }
}
