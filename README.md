[![Packagist Version](https://img.shields.io/packagist/v/zepfietje/laravel-seeable)](https://packagist.org/packages/zepfietje/laravel-seeable)
[![Packagist Downloads](https://img.shields.io/packagist/dt/zepfietje/laravel-seeable)](https://packagist.org/packages/zepfietje/laravel-seeable/stats)

# Laravel Seeable

This package makes it easy to keep track of the date and time a user was last seen.

## Installation

1. Install this package:
   ```bash
   composer require zepfietje/laravel-seeable
   ```
2. Optionally publish the configuration file:
    ```bash
    php artisan vendor:publish --tag="seeable-config"
    ```
3. Add a `seen_at` column to your users table:
   ```php
   return new class extends Migration
   {
       public function up(): void
       {
           Schema::table('users', function (Blueprint $table) {
               $table->timestamp('seen_at')->nullable();
           });
       }

       // ...
   };
   ```
4. Add the `Seeable` concern to your user model:
   ```php
   namespace App\Models;

   // ...
   use ZepFietje\Seeable\Concerns\Seeable;

   class User extends Authenticatable
   {
       // ...
       use Seeable;
   }
   ```
5. Register the `SeeUser` middleware in your `app/Http/Kernel.php` file:
   ```php
   protected $middlewareGroups = [
       'web' => [
           // ...
           \ZepFietje\Seeable\Http\Middleware\SeeUser::class,
       ],
   ];
   ```

## Usage

### Query scopes

```php
User::seenAfter('2022-06-30')->get();
$dailyActiveUsers = User::seenPastDay()->count();
$weeklyActiveUsers = User::seenPastWeek()->count();
$monthlyActiveUsers = User::seenPastMonth()->count();
```
