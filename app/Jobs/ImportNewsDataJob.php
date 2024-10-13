<?php

namespace App\Jobs;

use App\DataObject\NewsSourceData;
use App\Repositories\NewsRepository;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        try {
            $newsApiData = $this->getNewsData($this->newsApiUrl, 'country=us&apiKey='.$this->newsApikey);
            if(count($newsApiData) && isset($newsApiData["status"]) && $newsApiData["status"] == "ok") {
                $extractedData = $this->prepareNewsApiData($newsApiData);
                NewsRepository::importNewsData($extractedData);
            } else {
                Log::warning('ImportNewsDataJob@handle', ["No data found to import from " . NewsSourceData::NEWSAPI]);
            }
    
            $newYorkTimesData = $this->getNewsData($this->newYorkTimesApiUrl, 'api-key='.$this->newYorkTimesApiKey);
            if(count($newYorkTimesData) && isset($newYorkTimesData["status"]) && $newYorkTimesData["status"] == "OK") {
                $extractedData = $this->prepareNewYorkTimesData($newYorkTimesData);
                NewsRepository::importNewsData($extractedData);
            } else {
                Log::warning('ImportNewsDataJob@handle', ["No data found to import from " . NewsSourceData::NEW_YORK_TIMES]);
            }
    
            $guardianData = $this->getNewsData($this->theGuardianAPIUrl, 'api-key='.$this->theGuardianApiKey);
            if(count($guardianData) && isset($guardianData["response"]["status"]) && $guardianData["response"]["status"] == "ok") {
                $extractedData = $this->prepareGuardianData($guardianData);
                NewsRepository::importNewsData($extractedData);
            } else {
                Log::warning('ImportNewsDataJob@handle', ["No data found to import from " . NewsSourceData::THE_GUARDIAN]);
            }
        } catch(Exception $e) {
            Log::error('ImportNewsDataJob@handle', [$e]);
        }
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
