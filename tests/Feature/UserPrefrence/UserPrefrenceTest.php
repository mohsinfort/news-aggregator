<?php

namespace Tests\Feature\UserPrefrence;

use App\DataObject\NewsSourceData;
use App\Models\User;
use Tests\TestCase;

class UserPrefrenceTest extends TestCase
{
    protected $user;
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function testUpdateUserPrefrence()
    {
        $response = $this->json('POST', '/api/user/prefrences', [
            "news_type" => "sports",
            "news_source" => NewsSourceData::THE_GUARDIAN
        ]);

        $response->assertStatus(200);
    }
}