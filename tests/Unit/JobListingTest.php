<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\JobListing;
use App\Models\JobApplication;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_poster()
    {
        $user = User::factory()->create();
        $job = JobListing::factory()->create(['posted_by' => $user->id]);

        $this->assertEquals($user->id, $job->fresh()->poster->id);
    }

    /** @test */
    public function it_has_applications()
    {
        $job = JobListing::factory()->create();
        $applicant = User::factory()->create();
        $application = JobApplication::factory()->create([
            'job_id' => $job->id,
            'applicant_id' => $applicant->id
        ]);

        $this->assertCount(1, $job->fresh()->applications);
        $this->assertEquals($application->id, $job->fresh()->applications->first()->id);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $job = JobListing::factory()->create();

        $this->assertNotNull($job->id);
        $this->assertNotNull($job->title);
        $this->assertNotNull($job->description);
        $this->assertNotNull($job->requirements);
        $this->assertNotNull($job->responsibilities);
        $this->assertNotNull($job->job_type);
        $this->assertNotNull($job->experience_level);
        $this->assertNotNull($job->location);
        $this->assertNotNull($job->job_status);
    }

    /** @test */
    public function it_has_correct_attributes()
    {
        $job = JobListing::factory()->create([
            'title' => 'Test Job',
            'description' => 'Test Description',
            'job_type' => 'full_time',
            'experience_level' => 'mid',
            'job_status' => 'published'
        ]);

        $this->assertEquals('Test Job', $job->title);
        $this->assertEquals('Test Description', $job->description);
        $this->assertEquals('full_time', $job->job_type);
        $this->assertEquals('mid', $job->experience_level);
        $this->assertEquals('published', $job->job_status);
    }
}
