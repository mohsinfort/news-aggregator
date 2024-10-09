<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\GetNewsListRequest;
use App\Repositories\NewsRepository; 

class NewsController extends Controller
{
    private NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function index(GetNewsListRequest $request)
    {
        $data = $this->newsRepository->getNewsList($request);

        return response()->json($data, 200);
    }
}
