<?php

namespace App\Http\Controllers;

use App\Http\Requests\News\GetNewsListRequest;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;
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
        $data = $this->newsRepository->getNewsList(
            $request->title,
            $request->type,
            $request->published_at_from,
            $request->published_at_to
        );

        return response()->json($data, 200);
    }

    public function getNewsListByUserPrefrences(Request $request)
    {
        $userPrefrences = $request->user()->userPrefrence;
        $data = $this->newsRepository->getNewsListByUserPrefrence(
            $userPrefrences->news_type,
            $userPrefrences->news_source,
        );

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
