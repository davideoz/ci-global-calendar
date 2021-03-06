<?php

namespace Tests\Browser;

use App\User;
use DavideCasiraghi\LaravelEventsCalendar\Models\Teacher;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\LoginPage;
use Tests\DuskTestCase;

class TeachersTest extends DuskTestCase
{
    use DatabaseMigrations;

    /***************************************************************************/

    /**
     * Populate test DB with seeds.
     */
    public function setUp(): void
    {
        parent::setUp();

        // Seeders - /database/seeds (continetns, countries, post categories, event categories)
        $this->seed();
    }

    /***************************************************************************/

    /**
     * Verify if the teachers list is showing.
     *
     * @return void
     */
    public function test_teachers_list_is_showing()
    {
        $this->browse(function (Browser $browser) {
            $browser->on(new LoginPage)
                    ->loginUser()
                    ->visit('/teachers')
                    ->assertSee('Teachers management') // The list is empty because the new user didn't create an event yet
                    ->logoutUser();
        });
    }

    /*******************************************************************************/

    /**
     * Open the Create teacher form.
     *
     * @return void
     */
    public function test_open_create_teacher()
    {
        $this->browse(function (Browser $browser) {
            $browser->on(new LoginPage)
                      ->loginUser()
                      ->visit('/teachers')   //dusk don't like to visit page /teachers/create, so let's go there clicking
                      ->click('a.create-new')
                      ->assertSee('Year of starting to practice')
                      ->logoutUser();
        });
    }

    /*******************************************************************************/

    /**
     * Create a new teacher.
     *
     * @param  \Laravel\Dusk\Browser  $browser
     * @param  string  $name
     * @return void
     */
    public function test_create_new_teacher()
    {
        $this->browse(function (Browser $browser) {
            $browser->on(new LoginPage)
                     ->loginUser()
                     ->visit('/teachers')
                     ->click('a.create-new')
                     ->type('name', 'Test Teacher')
                     ->select('country_id', 3)
                     ->type('bio', 'lorem ipsum dolet')
                     ->type('year_starting_practice', '1999')
                     ->type('year_starting_teach', '1995')
                     ->type('significant_teachers', 'test teachers')
                     ->type('facebook', 'http://www.facebook.com/test')
                     ->type('website', 'http://www.test.it')
                     ->resize(1920, 3000)
                     ->press('Submit')
                     ->assertSee(__('messages.teacher_added_successfully'))
                     ->logoutUser();
        });
    }

    /***************************************************************************/
}
