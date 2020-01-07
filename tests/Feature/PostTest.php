<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\BlogPost;
use App\Comment;
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
        $response->assertSeeText('No comments yet!');

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

    public function testSee1BlogPostWithComments()
    {
/*         // Arrange
        $user = $this->user();
        $post = $this->createDummyBlogPost();
        factory(Comment::class, 4)->create([
            'commentable_id' => $post->id,
            'commentable_type' => 'App\BlogPost',
            'user_id' => $user->id
        ]);
        $response = $this->get('/posts');
        $response->assertSeeText('4 comments'); */
        $post = $this->createDummyBlogPost();
        factory(Comment::class, 4)->create([
            'blog_post_id' => $post->id
        ]);
        $response = $this->get('/posts');
        $response->assertSeeText('4 comments');

 
    }

    private function createDummyBlogPost(): BlogPost
    {

/*         $post = new BlogPost();
        $post->title = 'New Title';
        $post->content = 'New Content of Post.';
        $post->save(); */

        return factory(BlogPost::class)->states('new-title')->create();


        /* return $post; */
    }
}
