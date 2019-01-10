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
     * Test that logged user can see posts index view
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
     * Test that guest user can READ a post
     */  
    public function test_guest_user_can_see_single_post(){
        
        // Access to the page (post.show)
            $response = $this->get('/posts/'.$this->post->id.'/')
                         ->assertStatus(200);
    }
    
    /***************************************************************************/
    /**
     * Test that logged user can CREATE a post
     * The post datas are stored in the post_translations table with locale = en (english is the defaul post language)
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
            $this->assertDatabaseHas('post_translations',[
                                    'title' => $data['title'],
                                    'locale' => 'en'
                                ]);
            
        // Status
            $response
                    ->assertStatus(200)
                    ->assertSee(__('messages.article_added_successfully'));
    }
    
    /***************************************************************************/
    /**
     * Test that guest user can UPDATE a post
     */  
    public function test_guest_user_can_update_post(){
        
        // Authenticate the user
            $this->authenticate();
            
        // Update the post
            $this->post->title = "Updated Title";
            $response = $this
                        ->followingRedirects()
                        ->put('/posts/'.$this->post->id, $this->post->toArray())
                        ->assertSee(__('messages.article_updated_successfully'));
                
        // Check the update on DB        
            $this->assertDatabaseHas('post_translations',['id'=> $this->post->id , 'title' => 'Updated Title']);
    }

    /***************************************************************************/
    /**
     * Test that guest user can UPDATE a post
     */  
    public function test_guest_user_can_delete_post(){
        
        // Authenticate the user
            $this->authenticate();
            
        // Delete the post
            $response = $this
                        ->followingRedirects()
                        ->delete('/posts/'.$this->post->id, $this->post->toArray())
                        ->assertSee(__('messages.article_deleted_successfully'));
                
        // Check the update on DB        
            $this->assertDatabaseMissing('post_translations',['id'=> $this->post->id]);
    }

    /***************************************************************************/
    /**
     * Test that logged user can access post in english by slug
     */  
    public function test_guest_user_can_see_about_us_english()
    {
        $slug = $this->post->slug;
        
        // Access to the page
            $response = $this->get('/post/'.$slug)
                             ->assertStatus(200);
    }
    
    /***************************************************************************/
    /**
     * Test that logged user can can access post translation in italian by slug
     */  
/*    public function test_guest_user_can_see_about_us_italian_translation()
    {
        // Access to the page
            $response = $this->get('/it/post/about')
                             ->assertStatus(200);
    }
    
        -- create post translation factory first
    
    */


    
    
}
