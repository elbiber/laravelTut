<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\BlogPost as BlogPost;
use App\Http\Middleware as Middleware;


class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testNoBlogPostWhenDatabaseTableEmpty()
    {
        $response = $this->get('/posts');

        $response->assertSeeText('No blog posts yet!');
    }

        /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSeeOneBlogPostWhenThereIsOne()
    {
        // Arange
        $post = $this->createDummyBlogPost();

        //Act
        $response = $this->get('/posts');

        // Assert
        $response->assertSeeText('New Title');

        $this->assertDatabaseHas('blog_posts', [
            'title' => 'New title'
        ]);
    }

    public function testStoreValid()
    {
        // Arange
        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];
        $this->withoutMiddleware(Middleware\VerifyCsrfToken::class);

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('status');
        $this->assertEquals(session('status'), 'Blog post was created!');
    }

    public function testStoreFail()
    {
        // Arange
        $params = [
            'title' => 'x',
            'content' => 'x'
        ];
        $this->withoutMiddleware(Middleware\VerifyCsrfToken::class);

        $this->post('/posts', $params)
            ->assertStatus(302)
            ->assertSessionHas('errors');

        $messages = session('errors')->getMessages();

        $this->assertEquals($messages['title'][0], 'The title must be at least 3 characters.');
        $this->assertEquals($messages['content'][0], 'The content must be at least 10 characters.');
    }

    public function testUpdateValid()
    {
        // Arange
        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters',
        ];

        $this->withoutMiddleware(Middleware\VerifyCsrfToken::class);

        $this->put("/posts/{$post->id}", $params)
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertEquals(session('status'), 'Blog post was updated!');
        $this->assertDatabaseMissing('blog_posts', $post->toArray());
        $this->assertDatabaseHas('blog_posts', [
            'title' => 'Valid title'
        ]);
    }

    public function testDelete()
    {
        $post = $this->createDummyBlogPost();

        $this->assertDatabaseHas('blog_posts', $post->toArray());

        $this->withoutMiddleware(Middleware\VerifyCsrfToken::class);

        $this->delete("/posts/{$post->id}")
            ->assertStatus(302)
            ->assertSessionHas('status');

        $this->assertDatabaseMissing('blog_posts', $post->toArray());
        $this->assertEquals(session('status'), 'Blog post was deleted!');

    }

    private function createDummyBlogPost(): BlogPost
    {

        $post = new BlogPost();
        $post->title = 'New Title';
        $post->content = 'New Content of Post.';
        $post->save();

        return $post;
    }
}
