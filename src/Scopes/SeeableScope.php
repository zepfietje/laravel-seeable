<?php

namespace ZepFietje\Seeable\Scopes;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class SeeableScope implements Scope
{
    protected static array $extensions = [
        'WhereSeenAfter',
        'WhereSeenLastDay',
        'WhereSeenLastWeek',
        'WhereSeenLastMonth',
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

    protected function addWhereSeenAfter(Builder $builder): void
    {
        $builder->macro(
            'whereSeenAfter',
            fn (Builder $builder, DateTime|string $value) => $builder->where($builder->getModel()->getQualifiedSeenAtColumn(), '>', $value),
        );
    }

    protected function addWhereSeenLastDay(Builder $builder): void
    {
        $builder->macro(
            'whereSeenLastDay',
            fn (Builder $builder) => $builder->whereSeenAfter(now()->subDay()),
        );
    }

    protected function addWhereSeenLastWeek(Builder $builder): void
    {
        $builder->macro(
            'whereSeenLastWeek',
            fn (Builder $builder) => $builder->whereSeenAfter(now()->subWeek()),
        );
    }

    protected function addWhereSeenLastMonth(Builder $builder): void
    {
        $builder->macro(
            'whereSeenLastMonth',
            fn (Builder $builder) => $builder->whereSeenAfter(now()->subMonth()),
        );
    }
}
