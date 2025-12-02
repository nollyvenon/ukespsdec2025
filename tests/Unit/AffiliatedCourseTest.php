<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\AffiliatedCourse;
use App\Models\University;
use App\Models\User;
use App\Models\AffiliatedCourseEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AffiliatedCourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_university()
    {
        $university = University::factory()->create();
        $course = AffiliatedCourse::factory()->create(['university_id' => $university->id]);

        $this->assertEquals($university->id, $course->fresh()->university->id);
    }

    /** @test */
    public function it_has_enrollments()
    {
        $course = AffiliatedCourse::factory()->create();
        $user = User::factory()->create();
        $enrollment = AffiliatedCourseEnrollment::factory()->create([
            'affiliated_course_id' => $course->id,
            'user_id' => $user->id
        ]);

        $this->assertCount(1, $course->fresh()->affiliatedCourseEnrollments);
        $this->assertEquals($enrollment->id, $course->fresh()->affiliatedCourseEnrollments->first()->id);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $course = AffiliatedCourse::factory()->create();

        $this->assertNotNull($course->id);
        $this->assertNotNull($course->title);
        $this->assertNotNull($course->description);
        $this->assertNotNull($course->level);
        $this->assertNotNull($course->start_date);
        $this->assertNotNull($course->end_date);
    }

    /** @test */
    public function it_has_correct_attributes()
    {
        $course = AffiliatedCourse::factory()->create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'level' => 'beginner',
            'duration' => 10,
            'status' => 'published'
        ]);

        $this->assertEquals('Test Course', $course->title);
        $this->assertEquals('Test Description', $course->description);
        $this->assertEquals('beginner', $course->level);
        $this->assertEquals(10, $course->duration);
        $this->assertEquals('published', $course->status);
    }
}
