<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_creator()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $user->id]);

        $this->assertEquals($user->id, $event->fresh()->creator->id);
    }

    /** @test */
    public function it_has_registrations()
    {
        $event = Event::factory()->create();
        $user = User::factory()->create();
        $registration = EventRegistration::factory()->create([
            'event_id' => $event->id,
            'user_id' => $user->id
        ]);

        $this->assertCount(1, $event->fresh()->registrations);
        $this->assertEquals($registration->id, $event->fresh()->registrations->first()->id);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $event = Event::factory()->create();

        $this->assertNotNull($event->id);
        $this->assertNotNull($event->title);
        $this->assertNotNull($event->description);
        $this->assertNotNull($event->location);
        $this->assertNotNull($event->start_date);
        $this->assertNotNull($event->end_date);
    }
}
