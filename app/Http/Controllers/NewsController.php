<?php

namespace App\Http\Controllers;

use App\Repositories\NewsRepository; 

class NewsController extends Controller
{
    private NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function index()
    {
        $data = $this->newsRepository->getNews();

        return response()->json($data, 200);
    }
}
