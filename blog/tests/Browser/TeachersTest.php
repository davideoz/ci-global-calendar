<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use App\Teacher;
use App\User;

use Tests\Browser\Pages\LoginPage;

class TeachersTest extends DuskTestCase
{
    
    public function test_venues_list_is_showing(){  
        
        $this->browse(function (Browser $browser) {
            $browser->on(new LoginPage)
                    ->loginUser()
                    ->visit('/teachers')
                    ->assertSee('Teachers management'); // The list is empty because the new user didn't create an event yet
                    //->dump();
        });
        
    }
    
}
