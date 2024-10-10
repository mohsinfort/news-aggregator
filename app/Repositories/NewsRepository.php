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

    public function getNewsList(?string $title = null, ?string $type = null, ?string $source = null, ?string $published_at_from = null, ?string $published_at_to = null)
    {
        /*
        * Can implement mysql fulltext search or other search services like milisearch, algolia etc.
        */
        return $this->news
            ->select('id', 'title', 'url','type', 'source')
            ->when($title, function ($query) use ($title) {
                $query->where('title', 'LIKE', '%'.$title.'%');
            })
            ->when($type, function ($query) use ($type) {
                $query->where('type', 'LIKE', '%'.$type.'%');
            })
            ->when($source, function ($query) use ($source) {
                $query->where('source', 'LIKE', '%'.$source.'%');
            })
            ->when($published_at_from, function ($query) use ($published_at_from) {
                $query->whereDate('published_at', '>=' , $published_at_from);
            })
            ->when($published_at_to, function ($query) use ($published_at_to) {
                $query->whereDate('published_at', '<=' , $published_at_to);
            })
            ->paginate(15);
    }

    public function getNewsListByUserPrefrence(?string $type = null, ?string $source = null)
    {
        return $this->news
            ->select('id', 'title', 'url','type', 'source')
            ->when($type, function ($query) use ($type) {
                $query->where('type', 'LIKE', '%'.$type.'%');
            })
            ->when($source, function ($query) use ($source) {
                $query->orWhere('source', 'LIKE', '%'.$source.'%');
            })
            ->paginate(15);
    }

    public function getNewsById(int $id)
    {
        return $this->news
            ->where('id', $id)
            ->first();
    }
}
