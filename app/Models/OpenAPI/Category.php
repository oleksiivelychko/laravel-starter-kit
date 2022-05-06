<?php

namespace App\Models\OpenAPI;

use DateTime;


/**
 * @OA\Schema(
 *     title="Category",
 *     description="Category OpenAPI model inherits App\Models\Category",
 *     @OA\Xml(
 *         name="Category"
 *     )
 * )
 */
class Category
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
     *      example="test-category"
     * )
     */
    public string $slug;

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
     *     property="parent_id",
     *     description="Reference to parent category ID",
     *     type="object",
     *     format="\App\Models\OpenApi\Category"
     * )
     */
    public Category $parent_id;
}
