<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Requests\Dashboard\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\JsonResponse;
use Throwable;
use Symfony\Component\HttpFoundation\Response;


class ProductController extends OpenApiController
{
    /**
     * @OA\Get (
     *      path="/api/v1/products",
     *      operationId="getProductsList",
     *      tags={"Products"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Get products",
     *      description="Fetch all products using `offset` and `limit` parameters",
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
    public function index(): ProductCollection|JsonResponse
    {
        $offset = Request::input('offset', 0);
        $limit = Request::input('limit', 100);

        $models = Product::with('categories')->offset($offset)->limit($limit)->orderBy('id')->get();
        if (count($models)) {
            return new ProductCollection($models);
        } else {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @OA\Post (
     *      path="/api/v1/products",
     *      operationId="storeProduct",
     *      tags={"Products"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Create a new product",
     *      description="Create a new product from POST data",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              encoding={"categories[]":{"explode":"true"}},
     *              @OA\Schema(
     *                  required={"name__en","name__uk","price","categories[]"},
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
     *                      property="description__en",
     *                      description="Description in English",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="description__uk",
     *                      description="Description in Ukranian",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="slug",
     *                      description="Slug",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="price",
     *                      type="number",
     *                      format="float"
     *                   ),
     *                  @OA\Property(
     *                      property="categories[]",
     *                      type="array",
     *                      collectionFormat="multi",
     *                      @OA\Items(type="integer")
     *                   ),
     *                  @OA\Property(
     *                      property="images[]",
     *                      type="array",
     *                      @OA\Items(type="file", format="binary")
     *                   ),
     *               ),
     *           ),
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
     * @throws Throwable
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            $product = new Product;
            if ($product->saveModel($validatedData)) {
                return response()->json($product);
            }
        }

        return response()->json([], 422);
    }

    /**
     * @OA\Get  (
     *      path="/api/v1/products/{id}",
     *      operationId="getProduct",
     *      tags={"Products"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Get product",
     *      description="Get product by ID",
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
    public function show(int $id): JsonResponse
    {
        $model = Product::with('categories')->find($id);
        if ($model) {
            return response()->json($model);
        } else {
            return response()->json([], 404);
        }
    }

    /**
     * @OA\Put (
     *      path="/api/v1/products/{id}",
     *      operationId="updateProduct",
     *      tags={"Products"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Update the exists product",
     *      description="Update the exists product from POST data",
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
     *              encoding={"categories[]":{"explode":"true"}},
     *              @OA\Schema(
     *                  required={"name__en","name__uk","price","categories[]"},
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
     *                      property="description__en",
     *                      description="Description in English",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="description__uk",
     *                      description="Description in Ukranian",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="slug",
     *                      description="Slug",
     *                      type="string"
     *                   ),
     *                  @OA\Property(
     *                      property="price",
     *                      type="number",
     *                      format="float"
     *                   ),
     *                  @OA\Property(
     *                      property="categories[]",
     *                      type="array",
     *                      @OA\Items(type="integer")
     *                   ),
     *               ),
     *           ),
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
     * @throws Throwable
     */
    public function update(StoreProductRequest $request, Product $product): JsonResponse
    {
        $validatedData = $request->validated();
        if ($validatedData) {
            if ($product->saveModel($validatedData)) {
                return response()->json($product);
            }
        }

        return response()->json([], 422);
    }

    /**
     * @OA\Delete (
     *      path="/api/v1/products/{id}",
     *      operationId="deleteProduct",
     *      tags={"Products"},
     *      security={{"ApiKeyAuth": {}}},
     *      summary="Delete the exists product",
     *      description="Delete the exists product by ID",
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
    public function destroy(HttpRequest $request, Product $product): JsonResponse
    {
        if ($product->delete()) {
            $dir = public_path('uploads') . "/{$product->getImagesFolder()}/$product->id";
            if (File::exists($dir)) {
                File::deleteDirectory($dir);
            }

            return response()->json(['Product has been successfully deleted']);
        }

        return response()->json([], 422);
    }
}
