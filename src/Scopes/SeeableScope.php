<?php

namespace ZepFietje\Seeable\Scopes;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SeeableScope implements Scope
{
    protected static array $extensions = [
        'SeenAfter',
        'SeenPastDay',
        'SeenPastWeek',
        'SeenPastMonth',
    ];

    public function apply(Builder $builder, Model $model): void
    {
    }

    public function extend(Builder $builder): void
    {
        foreach (static::$extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }
    }

    protected function addSeenAfter(Builder $builder): void
    {
        $builder->macro(
            'seenAfter',
            fn (Builder $builder, DateTime|string $value) => $builder->where($builder->getModel()->getQualifiedSeenAtColumn(), '>', $value),
        );
    }

    protected function addSeenPastDay(Builder $builder): void
    {
        $builder->macro(
            'seenPastDay',
            fn (Builder $builder) => $builder->seenAfter(now()->subDay()),
        );
    }

    protected function addSeenPastWeek(Builder $builder): void
    {
        $builder->macro(
            'seenPastWeek',
            fn (Builder $builder) => $builder->seenAfter(now()->subWeek()),
        );
    }

    protected function addSeenPastMonth(Builder $builder): void
    {
        $builder->macro(
            'seenPastMonth',
            fn (Builder $builder) => $builder->seenAfter(now()->subMonth()),
        );
    }
}
