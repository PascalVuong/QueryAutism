<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'uuid',
    'organization_id',
    'user_id',
    'customer_number',
    'first_name',
    'last_name',
    'email',
    'phone',
    'date_of_birth',
    'status',
    'source',
    'marketing_consent',
    'registered_at',
    'last_activity_at',
    'notes',
])]
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function externalIdentifiers(): HasMany
    {
        return $this->hasMany(ExternalIdentifier::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationParticipants(): HasMany
    {
        return $this->hasMany(ReservationParticipant::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function creditAccounts(): HasMany
    {
        return $this->hasMany(CreditAccount::class);
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'marketing_consent' => 'boolean',
            'registered_at' => 'datetime',
            'last_activity_at' => 'datetime',
        ];
    }
}
