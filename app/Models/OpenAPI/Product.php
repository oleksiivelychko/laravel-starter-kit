<?php

namespace App\Models\OpenAPI;

use DateTime;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     title="Product",
 *     description="Product OpenAPI model inherits App\Models\Product",
 *     @OA\Xml(
 *         name="Product"
 *     )
 * )
 */
class Product
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="Primary Key",
     *     format="int64"
     * )
     */
    private int $id;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Required, JSON",
     *      example="{'name__en':'Test','name__uk':'Тест'}"
     * )
     */
    public string $name;

    /**
     * @OA\Property(
     *      title="Slug",
     *      example="test-product"
     * )
     */
    public string $slug;

    /**
     * @OA\Property(
     *      title="Description",
     *      description="JSON",
     *      example="{'description__en':'Test','description__uk':'Тест'}"
     * )
     */
    public string $description;

    /**
     * @OA\Property(
     *      title="Price",
     *      description="Required",
     *      type="float"
     * )
     */
    public float $price;

    /**
     * @OA\Property(
     *      type="array",
     *      @OA\Items(type="object", ref="#/components/schemas/Category"),
     *      description="Categories"
     * )
     */
    public array $categories;

    /**
     * @OA\Property(
     *      title="Image"
     * )
     */
    public string $image;

    /**
     * @OA\Property(
     *     title="Created at",
     *     example="2021-04-17 19:52:53",
     *     format="datetime",
     *     type="string"
     * )
     */
    private DateTime $created_at;

    /**
     * @OA\Property(
     *     title="Updated at",
     *     example="2021-04-17 19:52:53",
     *     format="datetime",
     *     type="string"
     * )
     */
    private DateTime $updated_at;

    /**
     * @OA\Property(
     *     title="Deleted at",
     *     example="2021-04-17 19:52:53",
     *     format="datetime",
     *     type="string"
     * )
     */
    private DateTime $deleted_at;
}
