<?php

namespace App\Models;

use App\Enums\ConfigEnum;
use App\Services\ConfigService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;

class Invite extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = ['email', 'token', 'role_id', 'sent_at', 'inviter_user_id'];

    public function casts()
    {
        $format = config('app.save_date_format');

        return [
            'sent_at' => "date:$format",
        ];
    }

    /**
     * Invite's Role.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Invite's Inviter.
     *
     * @return BelongsTo
     */
    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_user_id');
    }

    /**
     * Invite's Role.
     *
     * @return BelongsTo
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        if (! $this->sent_at) {
            return false;
        }
        $configService = app(ConfigService::class);

        return $this->sent_at->addDays($configService->get(ConfigEnum::INVITE_EXPIRATION_IN_DAYS))->isPast();
    }

    public function getCanResendAttribute(): bool
    {
        if (! $this->sent_at) {
            return true;
        }
        $configService = app(ConfigService::class);

        return $this->sent_at->addMinutes($configService->get(ConfigEnum::INVITE_RESENT_WAIT_IN_MINUTES))->isPast();
    }
}
