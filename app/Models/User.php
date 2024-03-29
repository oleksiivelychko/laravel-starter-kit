<?php

namespace App\Models;

use App\Contracts\Pagination;
use App\Contracts\UploadImages;
use App\Exceptions\InterfaceInstanceException;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use App\Traits\AccessControl;
use App\Traits\Asset;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @method select(string[] $array)
 */
class User extends Authenticatable implements MustVerifyEmail, UploadImages, Pagination, JWTSubject
{
    use HasFactory;
    use Notifiable;
    use AccessControl;
    use Asset;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected string $imageField = 'avatar';

    protected string $imagesFolder = 'avatars';

    protected array $cropPresets = [[150, 150]];

    public function getImageField(): string
    {
        return $this->imageField;
    }

    public function getImagesFolder(): string
    {
        return $this->imagesFolder;
    }

    public function getCropPresets(): array
    {
        return $this->cropPresets;
    }

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPassword($token));
    }

    public function getUsernameAttribute(): string
    {
        return $this->attributes['name'] ?: $this->attributes['email'];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }

    /**
     * @param null|mixed $uploadedAvatar
     *
     * @throws InterfaceInstanceException
     */
    public function store(array $rolesIds = [], array $permissionsIds = [], $uploadedAvatar = null): bool
    {
        $saved = $this->save();
        if ($saved) {
            $this->assignRoles($rolesIds);
            $this->assignPermissions($permissionsIds);

            if ($uploadedAvatar instanceof UploadedFile) {
                $filename = $this->uploadImage($uploadedAvatar);
                if ($filename) {
                    $this->{$this->getImageField()} = $filename;
                    $this->save();
                }
            }
        }

        return $saved;
    }

    public function pagination(Request $request, ?string $locale = null): LengthAwarePaginator
    {
        $sortColumn = $request->query('sort', 'id');
        $sortDirection = $request->query('direction', 'asc');

        return $this->select(['id', 'name', 'email', 'created_at'])
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($this->getPaginationLimit())
        ;
    }

    public function getPaginationLimit(): int
    {
        return config('settings.schema.pagination_limit', 10);
    }

    public function uploadImages(array $data): void
    {
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
