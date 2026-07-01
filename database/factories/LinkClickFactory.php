<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\LinkClick;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LinkClick>
 */
class LinkClickFactory extends Factory
{
    protected $model = LinkClick::class;

    public function definition(): array
    {
        return [
            'link_id' => Link::factory(),
            'ip_address' => fake()->ipv4(),
            'clicked_at' => now(),
        ];
    }
}
