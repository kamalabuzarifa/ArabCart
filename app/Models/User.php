<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['name', 'email', 'password', 'provider', 'provider_id', 'avatar', 'email_verified_at', 'seller_rating_avg', 'seller_rating_count', 'seller_sales_count'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'seller_rating_avg' => 'decimal:2',
            'seller_rating_count' => 'integer',
            'seller_sales_count' => 'integer',
        ];
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                $avatar = $this->attributes['avatar'] ?? null;

                if (blank($avatar)) {
                    return null;
                }

                if (Str::startsWith($avatar, ['http://', 'https://', '//'])) {
                    return $avatar;
                }

                return asset('storage/'.ltrim($avatar, '/'));
            },
        );
    }
    
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(ListingOrder::class, 'buyer_id');
    }

    public function sales(): HasMany
    {
        return $this->hasMany(ListingOrder::class, 'seller_id');
    }

    public function writtenReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'buyer_id');
    }

    public function receivedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'seller_id');
    }
}
