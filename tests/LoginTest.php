<?php

use Schoo\User;

class LoginTest extends TestCase
{
    /**
     * test login page.
     * */
    public function testLoginPageResponse()
    {
        $response = $this->call('GET', '/login');
        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Test accurate content on login.
     * */
    public function testLoginPageHasRightContent()
    {
        $this->visit('/login')
            ->see('Schoo')
            ->see('Register')
            ->see('Log in');
    }

    /**
     * Create User to test login functionality.
     * */
    public function createUser()
    {
        User::create([
            'name'     => 'johndoe',
            'email'    => 'a@b.com',
            'password' => md5('password'),
        ]);
    }

    /**
     * test login works.
     * */
    public function testLoginWorksCorrectly()
    {
        $this->createUser();
        $this->visit('/login')
            ->type('a@b.com', 'email')
            ->type('password', ('password'))
            ->check('remember')
            ->press('Log In')
            ->seePageIs('/dashboard')
            ->seeInDatabase('users', ['email' => 'a@b.com']);
    }

    /**
     * Check to see facebook login.
     * */
    public function testPageHasFaceBookLogin()
    {
        $this->visit('/login')
            ->see('facebook');
    }

    /**
     * Check to see twitter login.
     * */
    public function testPageHasTwitterLogin()
    {
        $this->visit('/login')
            ->see('twitter');
    }

    /**
     * Check to see github login.
     * */
    public function testPageHasGithubLogin()
    {
        $this->visit('/login')
            ->see('github');
    }
}
