<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Import extends Model
{
    protected $table = 'imports';

    protected $fillable = ['state', 'entity', 'received', 'updated', 'created'];

    public const STATE_INIT = 'INIT';
    public const STATE_WORKS = 'WORKS';
    public const STATE_SUCCEED = 'SUCCEED';
    public const STATE_FAILED = 'FAILED';
    const STATES = [
        self::STATE_INIT,
        self::STATE_WORKS,
        self::STATE_SUCCEED,
        self::STATE_FAILED,
    ];

    public const ALLOWED_ENTITIES = [
        'product',
        'category'
    ];

    public function init(string $entity)
    {
        $this->attributes['state'] = self::STATE_INIT;
        $this->attributes['entity'] = $entity;
        $this->save();
    }
}
