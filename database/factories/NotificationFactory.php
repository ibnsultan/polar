<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['info', 'success', 'warning', 'error'];
        $icons = [
            'info' => 'fas fa-info-circle',
            'success' => 'fas fa-check-circle',
            'warning' => 'fas fa-exclamation-circle',
            'error' => 'fas fa-exclamation-triangle'
        ];
        
        $type = $this->faker->randomElement($types);
        
        return [
            'title' => $this->faker->sentence(3),
            'message' => $this->faker->paragraph(2),
            'type' => $type,
            'icon' => $icons[$type],
            'data' => $this->faker->optional(0.3)->passthrough([
                'category' => $this->faker->randomElement(['system', 'user', 'security', 'update']),
                'priority' => $this->faker->randomElement(['low', 'medium', 'high'])
            ]),
            'action_url' => $this->faker->optional(0.4)->url(),
            'action_text' => $this->faker->optional(0.4)->randomElement(['View Details', 'Learn More', 'Take Action', 'Update Now']),
            'expires_at' => $this->faker->optional(0.2)->dateTimeBetween('now', '+30 days'),
            'is_global' => $this->faker->boolean(20), // 20% chance of being global
            'created_by' => User::factory(),
        ];
    }

    /**
     * Indicate that the notification is global.
     */
    public function global(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_global' => true,
        ]);
    }

    /**
     * Indicate that the notification is for specific users.
     */
    public function targeted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_global' => false,
        ]);
    }

    /**
     * Indicate that the notification has an expiry date.
     */
    public function withExpiry(int $daysFromNow = 7): static
    {
        return $this->state(fn (array $attributes) => [
            'expires_at' => now()->addDays($daysFromNow),
        ]);
    }

    /**
     * Indicate that the notification has an action.
     */
    public function withAction(string $url = null, string $text = null): static
    {
        return $this->state(fn (array $attributes) => [
            'action_url' => $url ?? $this->faker->url(),
            'action_text' => $text ?? 'Take Action',
        ]);
    }

    /**
     * Create a success notification.
     */
    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'success',
            'icon' => 'fas fa-check-circle',
        ]);
    }

    /**
     * Create an error notification.
     */
    public function error(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'error',
            'icon' => 'fas fa-exclamation-triangle',
        ]);
    }

    /**
     * Create a warning notification.
     */
    public function warning(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'warning',
            'icon' => 'fas fa-exclamation-circle',
        ]);
    }

    /**
     * Create an info notification.
     */
    public function info(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'info',
            'icon' => 'fas fa-info-circle',
        ]);
    }
}
