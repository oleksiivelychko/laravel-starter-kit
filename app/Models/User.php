<?php

namespace App\Models;

use App\Exceptions\InterfaceInstanceException;
use App\Interfaces\Pagination;
use App\Interfaces\UploadImages;
use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use App\Traits\ACL;
use App\Traits\Asset;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

/**
 * @method select(string[] $array)
 */
class User extends Authenticatable implements MustVerifyEmail, UploadImages, Pagination
{
    use HasFactory;
    use Notifiable;
    use ACL;
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
     * @throws InterfaceInstanceException
     */
    public function store($rolesIds = [], $permissionsIds = [], $uploadedAvatar = null): bool
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
}
