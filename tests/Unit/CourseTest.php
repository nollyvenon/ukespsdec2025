<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_an_instructor()
    {
        $instructor = User::factory()->create();
        $course = Course::factory()->create(['instructor_id' => $instructor->id]);

        $this->assertEquals($instructor->id, $course->fresh()->instructor->id);
    }

    /** @test */
    public function it_has_enrollments()
    {
        $course = Course::factory()->create();
        $student = User::factory()->create();
        $enrollment = CourseEnrollment::factory()->create([
            'course_id' => $course->id,
            'student_id' => $student->id
        ]);

        $this->assertCount(1, $course->fresh()->enrollments);
        $this->assertEquals($enrollment->id, $course->fresh()->enrollments->first()->id);
    }

    /** @test */
    public function it_can_be_created_with_factory()
    {
        $course = Course::factory()->create();

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
        $course = Course::factory()->create([
            'title' => 'Test Course',
            'description' => 'Test Description',
            'level' => 'beginner',
            'duration' => 10,
            'course_status' => 'published'
        ]);

        $this->assertEquals('Test Course', $course->title);
        $this->assertEquals('Test Description', $course->description);
        $this->assertEquals('beginner', $course->level);
        $this->assertEquals(10, $course->duration);
        $this->assertEquals('published', $course->course_status);
    }
}
