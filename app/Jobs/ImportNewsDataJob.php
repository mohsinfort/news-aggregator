<?php

namespace App\Jobs;

use App\DataObject\NewsSourceData;
use App\Repositories\NewsRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class ImportNewsDataJob implements ShouldQueue
{
    use Queueable;

    protected string $newsApiUrl;
    protected string $newYorkTimesApiUrl;
    protected string $theGuardianAPIUrl;

    protected string $newsApikey;
    protected string $newYorkTimesApiKey;
    protected string $theGuardianApiKey;


    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->newsApiUrl = 'https://newsapi.org/v2/top-headlines';
        $this->newYorkTimesApiUrl = 'https://api.nytimes.com/svc/topstories/v2/home.json';
        $this->theGuardianAPIUrl = 'https://content.guardianapis.com/search';
        
        $this->newsApikey = env('NEWS_API_KEY', '');
        $this->newYorkTimesApiKey = env('NEWYORK_TIMES_API_KEY', '');
        $this->theGuardianApiKey = env('THE_GUARDIAN_API_KEY', '');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /*
        - TODO: Have to handle data duplication as on every next Api call need to get data that 
        - was not fetched previously.
        */
        $newsApiData = $this->getNewsData($this->newsApiUrl, 'country=us&apiKey='.$this->newsApikey);
        $extractedData = $this->prepareNewsApiData($newsApiData);
        NewsRepository::importNewsData($extractedData);

        $newYorkTimesData = $this->getNewsData($this->newYorkTimesApiUrl, 'api-key='.$this->newYorkTimesApiKey);
        $extractedData = $this->prepareNewYorkTimesData($newYorkTimesData);
        NewsRepository::importNewsData($extractedData);

        $guardianData = $this->getNewsData($this->theGuardianAPIUrl, 'api-key='.$this->theGuardianApiKey);
        $extractedData = $this->prepareGuardianData($guardianData);
        NewsRepository::importNewsData($extractedData);
    }

    private function getNewsData(string $url, string $params)
    {
        $response = Http::get($url.'?'.$params);

        return $response->json();
    }

    private function prepareNewsApiData($data)
    {
        $extractedData = [];
        $articles = $data["articles"];

        foreach ($articles as $item) {
            $newItem = [
                "title" => $item['title'],
                "description" => $item['description'],
                "url" => $item['url'],
                "published_at" => Carbon::parse($item['publishedAt']),
                "type" => "general",
                "source" => NewsSourceData::NEWSAPI,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ];

            array_push($extractedData, $newItem);
        }

        return $extractedData;
    }

    private function prepareNewYorkTimesData($data)
    {
        $extractedData = [];
        $articles = $data["results"];

        foreach ($articles as $item) {
            $newItem = [
                "title" => $item['title'],
                "description" => $item['abstract'],
                "url" => $item['url'],
                "published_at" => Carbon::parse($item['published_date']),
                "type" => $item['section'],
                "source" => NewsSourceData::NEW_YORK_TIMES,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ];

            array_push($extractedData, $newItem);
        }

        return $extractedData;
    }

    private function prepareGuardianData($data)
    {
        $extractedData = [];
        $articles = $data["response"]["results"];

        foreach ($articles as $item) {
            $newItem = [
                "title" => $item['webTitle'],
                "description" => $item['webTitle'],
                "url" => $item['webUrl'],
                "published_at" => Carbon::parse($item['webPublicationDate']),
                "type" => $item['sectionName'],
                "source" => NewsSourceData::THE_GUARDIAN,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ];

            array_push($extractedData, $newItem);
        }

        return $extractedData;
    }
}
