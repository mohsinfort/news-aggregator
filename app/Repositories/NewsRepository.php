<?php

namespace App\Repositories;

use App\Http\Requests\News\GetNewsListRequest;
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

    public function getNewsList(GetNewsListRequest $request)
    {
        /*
        * Can implement mysql fulltext search or other search services like milisearch, algolia etc.
        */
        return $this->news
            ->select('id', 'title', 'url','type')
            ->when($request->title, function ($query) use ($request) {
                $query->where('title', 'LIKE', '%'.$request->title.'%');
            })
            ->when($request->type, function ($query) use ($request) {
                $query->where('type', 'LIKE', '%'.$request->type.'%');
            })
            ->when($request->published_at_from, function ($query) use ($request) {
                $query->whereDate('published_at', '>=' , $request->published_at_from);
            })
            ->when($request->published_at_to, function ($query) use ($request) {
                $query->whereDate('published_at', '<=' , $request->published_at_to);
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
