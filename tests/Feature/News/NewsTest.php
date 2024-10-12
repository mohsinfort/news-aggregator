<?php

namespace Tests\Feature\News;

use App\DataObject\NewsSourceData;
use App\Models\News;
use App\Models\User;
use App\Models\UserPreference;
use Tests\TestCase;

class NewsTest extends TestCase
{
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testGetNewsListDefault()
    {
        $response = $this->json('GET', '/api/news');

        $response->assertStatus(200);
    }

    public function testGetNewsList()
    {
        News::factory(5)->create();

        $response = $this->json('GET', '/api/news');

        $response->assertStatus(200);
        $this->assertEquals(5, count(json_decode($response->content())->data));
    }

    public function testGetNewsListByUserPrefrences()
    {
        UserPreference::factory()->create([
            'user_id' => $this->user->id,
            'news_type' => "sports",
            'news_source' => NewsSourceData::NEW_YORK_TIMES,
        ]);

        News::factory(5)->create();

        $response = $this->json('GET', '/api/news/user/prefrences');

        $response->assertStatus(200);
        $this->assertEquals(5, count(json_decode($response->content())->data));
    }

    public function testGetNewsById()
    {
        $news = News::factory()->create();

        $response = $this->json('GET', '/api/news/'. $news->id);

        $response->assertStatus(200);
    }

    public function testGetNewsByInvalidId()
    {
        $response = $this->json('GET', '/api/news/100');

        $response->assertStatus(404);
    }
}
