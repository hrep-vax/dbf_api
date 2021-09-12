<?php

namespace App\Http\Controllers;

use App\Helpers\ApiErrorResponse;
use App\Http\Requests\StoreTestResource;
use App\Http\Requests\UpdateTestRequest;
use App\Models\TestResource;
use App\Traits\ApiResponder;
use Illuminate\Http\Response;

class TestResourceController extends Controller
{
    use ApiResponder;

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $resources = TestResource::all();
        return $this->success(['resources' => $resources], 200);
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
        return $this->success(['newResource' => $newResource], 201);
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

        return $this->success(['resource' => $resource], 200);
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

        return $this->success(['updatedResources' => $resource], 200);
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

        return $this->success(['deletedResource' => $deletedResource], 200);
    }

    private function findResourceById($id)
    {
        $resource = TestResource::find($id);

        if (!$resource) {
            $message = ['id' => 'Could not find resource with given id.'];
            $this->throwError('Resource not found', $message, 404, ApiErrorResponse::RESOURCE_NOT_FOUND_CODE);
        }

        return $resource;
    }
}
