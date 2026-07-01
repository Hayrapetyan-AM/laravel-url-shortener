<?php

namespace Database\Factories;

use App\Enums\LinkStatus;
use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Link>
 */
class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'original_url' => fake()->url(),
            'short_code' => Str::lower(Str::random(6)),
            'status' => LinkStatus::Active,
        ];
    }
}
