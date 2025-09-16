<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Profiles;

class ProfilesFactory extends Factory
{
    protected $model = Profiles::class;

    public function definition(): array
    {
        return [
            'name'     => $this->faker->name(),
            'contact'  => $this->faker->unique()->phoneNumber(),
            'email'    => $this->faker->unique()->safeEmail(),
            'address'  => $this->faker->streetAddress(),
            'address2' => $this->faker->secondaryAddress(),
            'zip'      => $this->faker->postcode(),
        ];
    }
}
