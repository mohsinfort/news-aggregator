<?php

namespace App\Repositories;

use App\Models\UserPreference;

class UserPrefrencesRepository
{
    private UserPreference $userPreference;

    public function __construct(UserPreference $userPreference)
    {
        $this->userPreference = $userPreference;
    }

    public function updateOrCreateUserPrefrence(int $userId, ?string $newsType, ?string $newsSource, ?string $newsAuthor = null)
    {
        $this->userPreference->updateOrCreate(
            ["user_id" => $userId],
            [
                "news_type" => $newsType,
                "news_source" => $newsSource,
                "news_author" => $newsAuthor,
            ]
        );
    }
}
