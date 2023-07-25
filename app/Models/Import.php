<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    public const STATE_INIT = 'INIT';
    public const STATE_WORKS = 'WORKS';
    public const STATE_SUCCEED = 'SUCCEED';
    public const STATE_FAILED = 'FAILED';

    public const STATES = [
        self::STATE_INIT,
        self::STATE_WORKS,
        self::STATE_SUCCEED,
        self::STATE_FAILED,
    ];

    public const ALLOWED_ENTITIES = [
        'product',
        'category',
    ];

    protected $table = 'imports';

    protected $fillable = ['state', 'entity', 'received', 'updated', 'created'];

    public function init(string $entity): void
    {
        $this->attributes['state'] = self::STATE_INIT;
        $this->attributes['entity'] = $entity;

        $this->save();
    }
}
