<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PostTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase; // empty the test DB
    
    /***************************************************************************/
    /**
     * Populate test DB with dummy data
     */
    function setUp(){
        parent::setUp();
        
        // Seeders - /database/seeds
            $this->seed(); 
        
        // Factories - /database/factories
            $this->user = factory(\App\User::class)->create();
            $this->post = factory(\App\Post::class)->create();
    }
    /***************************************************************************/
    /**
     * Test that logged user can see teachers index view
     */  
    public function test_logged_user_can_see_posts_index(){
        // Authenticate the user
            $this->authenticate();
        
        // Access to the page
            $response = $this->get('/posts')
                             ->assertStatus(200);
    }
    
    /***************************************************************************/
    /**
     * Test that logged user can create a post
     */  
    public function test_a_logged_user_can_create_post(){
        // Authenticate the user
            $this->authenticate();
        
        // Post datas to create teacher (we don't include created_by and slug becayse are generated by the store method )
            $data = [
                'title' => $this->faker->name,
                'body' => $this->faker->paragraph,
                'category_id' => '3',
                'status' => '2',
                'featured' => '1',
                'before_content' => $this->faker->paragraph,
                'after_content' => $this->faker->paragraph,
            ];
            $response = $this
                            ->followingRedirects()
                            ->post('/posts', $data);
            
        // Assert in database
            //$this->assertDatabaseHas('posts',$data);
            
        // Status
            $response
                    ->assertStatus(200)
                    ->assertSee(__('general.post').__('views.created_successfully'));
    }
    
    /***************************************************************************/
    /**
     * Test that guest user can see a post
     */  
    public function test_guest_user_can_see_single_post(){
        
        // Access to the page (post.show)
            $response = $this->get('/en/posts/'.$this->post->id.'/')
                         ->assertStatus(200);
    }
    
    
}
