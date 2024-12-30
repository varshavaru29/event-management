<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Retrieve a random user with the role of 'organizer'
        $organizer = User::whereHas('roles', function($query) {
            $query->where('name', 'organizer');
        })->inRandomOrder()->first();

        // Ensure an organizer exists to avoid null errors
        if (!$organizer) {
            // Handle the case where no organizer exists, e.g., create one or throw an exception
            $organizer = User::factory()->create();  // Assuming the 'organizer' role will be attached to this user
            $organizer->roles()->attach(Role::where('name', 'organizer')->first());
        }
        return [
            'organizer_id' => $organizer->id,
            'title' => fake()->sentence,
            'slug' => fake()->unique()->slug,
            'description' => fake()->paragraph,
            'location' => fake()->address,
            'start_date' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'end_date' => fake()->dateTimeBetween('+2 weeks', '+3 weeks'),
            //'image' => Storage::putFile('events', UploadedFile::fake()->image('example.jpg', 640, 480)),
            'image' => 'events/download.jpeg',
            'deleted_at' => null,
        ];
    }
}
