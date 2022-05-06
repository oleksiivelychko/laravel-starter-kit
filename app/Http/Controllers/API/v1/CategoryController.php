<?php

namespace App\Http\Controllers\API\v1;

use App\Models\Category;
use App\Http\Requests\Dashboard\StoreCategoryRequest;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\JsonResponse;
use Throwable;


class CategoryController extends OpenApiController
{
    /**
     * @OA\Get (
     *      path="/api/v1/categories",
     *      operationId="getCategoriesList",
     *      tags={"Categories"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Get categories",
     *      description="Fetch all categories using `offset` and `limit` parameters",
     *      @OA\Parameter(
     *          name="offset",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="limit",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     */
    public function index(): JsonResponse
    {
        $offset = Request::input('offset', 0);
        $limit = Request::input('limit', 100);

        $models = Category::with('parent')->offset($offset)->limit($limit)->orderBy('id')->get();
        if (count($models)) {
            return response()->json($models);
        } else {
            return response()->json([], 404);
        }
    }

    /**
     * @OA\Post (
     *      path="/api/v1/categories",
     *      operationId="storeCategory",
     *      tags={"Categories"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Create a new category",
     *      description="Create a new category from POST data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  required={"name__en","name__uk"},
     *                  @OA\Property(
     *                      property="name__en",
     *                      description="Name in English",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="name__uk",
     *                      description="Name in Ukranian",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="slug",
     *                      description="Slug",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="parent_id",
     *                      description="Parent ID of Category",
     *                      type="integer"
     *                   ),
     *               ),
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Category")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * @throws Throwable
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $category = new Category;
            if ($category->saveModel($validatedData)) {
                return response()->json($category);
            }
        }

        return response()->json([], 422);
    }

    /**
     * @OA\Get  (
     *      path="/api/v1/categories/{id}",
     *      operationId="getCategory",
     *      tags={"Categories"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Get category",
     *      description="Get category by ID",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Category")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     */
    public function show(int $id): JsonResponse
    {
        $model = Category::with('parent')->find($id);
        if ($model) {
            return response()->json($model);
        } else {
            return response()->json([], 404);
        }
    }

    /**
     * @OA\Put (
     *      path="/api/v1/categories/{id}",
     *      operationId="updateCategory",
     *      tags={"Categories"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Update the exists category",
     *      description="Update the exists category from POST data",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/x-www-form-urlencoded",
     *              @OA\Schema(
     *                  required={"name__en","name__uk"},
     *                  @OA\Property(
     *                      property="name__en",
     *                      description="Name in English",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="name__uk",
     *                      description="Name in Ukranian",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="slug",
     *                      description="Slug",
     *                      type="string"
     *                   ),
     *                   @OA\Property(
     *                      property="parent_id",
     *                      description="Parent ID of Category",
     *                      type="integer"
     *                   ),
     *               ),
     *           ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Category")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     * @throws Throwable
     */
    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            if ($category->saveModel($validatedData)) {
                return response()->json($category);
            }
        }

        return response()->json([], 422);
    }

    /**
     * @OA\Delete (
     *      path="/api/v1/categories/{id}",
     *      operationId="deleteCategory",
     *      tags={"Categories"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Delete the exists category",
     *      description="Delete the exists category by ID",
     *      @OA\Parameter(
     *          name="id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Resource Not Found"
     *      )
     * )
     *
     */
    public function destroy(HttpRequest $request, Category $category): JsonResponse
    {
        $ids = $category->selectUniqueProductIds();
        if ($category->delete()) {
            $category->deleteUnusedProducts($ids);

            return response()->json(['Category has been successfully deleted']);
        }

        return response()->json([], 422);
    }
}
