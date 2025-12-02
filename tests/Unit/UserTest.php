<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use App\Models\Course;
use App\Models\JobListing;
use App\Models\AffiliatedCourse;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_has_a_profile_relationship()
    {
        $user = User::factory()->create();

        $this->assertNull($user->fresh()->profile);

        $profile = \App\Models\UserProfile::factory()->create(['user_id' => $user->id]);
        $this->assertEquals($profile->id, $user->fresh()->profile->id);
    }

    /** @test */
    public function it_has_created_events_relationship()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['created_by' => $user->id]);

        $this->assertCount(1, $user->fresh()->createdEvents);
        $this->assertEquals($event->id, $user->fresh()->createdEvents->first()->id);
    }

    /** @test */
    public function it_has_event_registrations_relationship()
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $registration = \App\Models\EventRegistration::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id
        ]);

        $this->assertCount(1, $user->fresh()->eventRegistrations);
        $this->assertEquals($registration->id, $user->fresh()->eventRegistrations->first()->id);
    }

    /** @test */
    public function it_has_taught_courses_relationship()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create(['instructor_id' => $user->id]);

        $this->assertCount(1, $user->fresh()->taughtCourses);
        $this->assertEquals($course->id, $user->fresh()->taughtCourses->first()->id);
    }

    /** @test */
    public function it_has_course_enrollments_relationship()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $enrollment = \App\Models\CourseEnrollment::factory()->create([
            'student_id' => $user->id,
            'course_id' => $course->id
        ]);

        $this->assertCount(1, $user->fresh()->courseEnrollments);
        $this->assertEquals($enrollment->id, $user->fresh()->courseEnrollments->first()->id);
    }

    /** @test */
    public function it_has_posted_jobs_relationship()
    {
        $user = User::factory()->create();
        $job = JobListing::factory()->create(['posted_by' => $user->id]);

        $this->assertCount(1, $user->fresh()->postedJobs);
        $this->assertEquals($job->id, $user->fresh()->postedJobs->first()->id);
    }

    /** @test */
    public function it_has_job_applications_relationship()
    {
        $user = User::factory()->create();
        $job = JobListing::factory()->create();
        $application = \App\Models\JobApplication::factory()->create([
            'applicant_id' => $user->id,
            'job_id' => $job->id
        ]);

        $this->assertCount(1, $user->fresh()->jobApplications);
        $this->assertEquals($application->id, $user->fresh()->jobApplications->first()->id);
    }

    /** @test */
    public function it_has_affiliated_course_enrollments_relationship()
    {
        $user = User::factory()->create();
        $course = AffiliatedCourse::factory()->create();
        $enrollment = \App\Models\AffiliatedCourseEnrollment::factory()->create([
            'user_id' => $user->id,
            'affiliated_course_id' => $course->id
        ]);

        $this->assertCount(1, $user->fresh()->affiliatedCourseEnrollments);
        $this->assertEquals($enrollment->id, $user->fresh()->affiliatedCourseEnrollments->first()->id);
    }

    /** @test */
    public function it_has_contact_messages_relationship()
    {
        $user = User::factory()->create();
        $message = \App\Models\ContactMessage::factory()->create(['user_id' => $user->id]);

        $this->assertCount(1, $user->fresh()->contactMessages);
        $this->assertEquals($message->id, $user->fresh()->contactMessages->first()->id);
    }
}
