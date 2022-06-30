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
        'SeenLastDay',
        'SeenLastWeek',
        'SeenLastMonth',
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

    protected function addSeenLastDay(Builder $builder): void
    {
        $builder->macro(
            'seenLastDay',
            fn (Builder $builder) => $builder->seenAfter(now()->subDay()),
        );
    }

    protected function addSeenLastWeek(Builder $builder): void
    {
        $builder->macro(
            'seenLastWeek',
            fn (Builder $builder) => $builder->seenAfter(now()->subWeek()),
        );
    }

    protected function addSeenLastMonth(Builder $builder): void
    {
        $builder->macro(
            'seenLastMonth',
            fn (Builder $builder) => $builder->seenAfter(now()->subMonth()),
        );
    }
}
