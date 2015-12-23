<?php

use Schoo\User;
use Schoo\Course;
use Schoo\Http\Controllers\CourseController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseTest extends TestCase
{
    /**
     * Create User to test login functionality.
     * */
    public function createUser()
    {
        User::create([
            'id'       => 1,
            'name'     => 'johndoe',
            'email'    => 'a@b.com',
            'password' => md5('password'),
        ]);
    }

    /**
     * Create Course to test login functionality.
     * */
    public function createCourse()
    {
        return Course::create([
            'id' => 1,
            'course'     => 'phone',
            'description'    => 'something very good',
            'url' => 'Dji9ALCgfpM',
            'video_id' => 'Dji9ALCgfpM',
            'section' => 'languages',
            'slug' => 'phone',
            'user_id' => 1,
        ]);
    }

    /**
     * [loginAUser description]
     * @return [type] [description]
     */
    public function loginAUser()
    {
        $user = factory(\Schoo\User::class)->create();
        $this->actingAs($user);
        $this->call('GET', '/dashboard');
    }

    /**
     * Test  courses
     */
    public function testAllCoursesPageHasRightContent()
    {
        $this->visit('/all-courses')
             ->see('Available Courses')
             ->dontSee('log Out');

        $this->assertViewHas('courses');
    }

    /**
     * Check page loads.
     * */
    public function testAllCoursesLoadsCorrectly()
    {
        $this->call('GET', '/all-courses');
        $this->assertResponseOk();
    }

    /**
     * testIndex description
     * @return void
     */
    public function testIndex()
    {
        $response = $this->call('GET', 'dashboard');
        $courses = Course::all();

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $courses);
    }

    /**
     * Test video id is obtained
     */
    public function testGetVideoId()
    {
        $course = new CourseController();
        $test = $course->getVideoId('https://www.youtube.com/watch?v=Dji9ALCgfpM');

        $this->assertEquals('Dji9ALCgfpM', $test);
    }

    /**
     * Test that Course can be viewed
     */
    public function testThatCoursesCanbeViewedByALoggedInUser()
    {
        $this->loginAUser();
        $this->createCourse();

        $this->visit('/dashboard')
             ->see('phone')
             ->see('languages')
             ->click('Start Course')
             ->seePageIs('/courses/phone')
             ->see('See All Courses')
             ->seeInDatabase('courses', ['course' => 'phone']);
    }

    /**
     * Test that Course can be viewed
     */
    public function testUnavailableCourseReturns404()
    {
        $response = $this->call('GET', '/courses/solo');

        $this->assertEquals(404, $response->getStatusCode());
    }

    /**
     * Test that Course can be viewed
     */
    // public function testThatCoursesCanbeEditedByCreator()
    // {
    //     $this->loginAUser();
    //     $this->createCourse();

    //     $this->visit('/dashboard')
    //          ->see('phone')
    //          ->see('languages')
    //          ->click('Start Course')
    //          ->seePageIs('/courses/phone')
    //          ->click('Edit')
    //          ->seePageIs('/courses/1/edit');
    // }

    /**
     * Test Course delete route
     *
     * @return void
     */
    public function testOnlyLoggedInUserCanDelete()
    {
        $response = $this->call('DELETE', '/course/1/delete');

        $this->assertEquals(500, $response->status());
    }

    /**
     * Test Course edit route
     */
    public function testOnlyLoggedInUserCanEdit()
    {
        $response = $this->call('POST', '/course/1/edit');

        $this->assertEquals(500, $response->status());
    }
}
