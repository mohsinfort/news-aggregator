<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\GetNewsListRequest;
use App\Repositories\NewsRepository;
use Illuminate\Support\Facades\Lang;

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

    public function getNewsById(int $id)
    {
        $data = $this->newsRepository->getNewsById($id);
        if (! $data) {
            return response()->json(['errors' => Lang::get('general.notFound')], 404);
        }

        return response()->json($data, 200);
    }
}
