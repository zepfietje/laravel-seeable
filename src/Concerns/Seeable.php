<?php

namespace ZepFietje\Seeable\Concerns;

use DateTime;
use ZepFietje\Seeable\Scopes\SeeableScope;

/**
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder seenAfter(DateTime|string $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder seenPastDay()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder seenPastWeek()
 * @method static \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder seenPastMonth()
 */
trait Seeable
{
    public static function bootSeeable(): void
    {
        static::addGlobalScope(new SeeableScope);
    }

    public function initializeSeeable(): void
    {
        if (! isset($this->casts[$this->getSeenAtColumn()])) {
            $this->casts[$this->getSeenAtColumn()] = 'datetime';
        }
    }

    public function see(): bool
    {
        $seenAt = $this->{$this->getSeenAtColumn()};

        if ($seenAt !== null && $seenAt->diffInSeconds() < config('seeable.throttle')) {
            return false;
        }

        return $this->updateSeenAt();
    }

    public function updateSeenAt(DateTime|string $value = null): bool
    {
        $this->timestamps = false;
        $this->{$this->getSeenAtColumn()} = $value ?? $this->freshTimestamp();

        return $this->save();
    }

    public function getSeenAtColumn(): string
    {
        return defined(static::class.'::SEEN_AT') ? static::SEEN_AT : 'seen_at';
    }

    public function getQualifiedSeenAtColumn(): string
    {
        return $this->qualifyColumn($this->getSeenAtColumn());
    }
}
