<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository
{
    private News $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    public static function importNewsData(array $data)
    {
        return News::insert($data);
    }

    public function getNewsList()
    {
        return $this->news
            ->select('id', 'title', 'url','type')
            ->paginate(15);
    }
}
