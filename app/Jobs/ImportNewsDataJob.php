<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
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
        $newsApiData = $this->getNewsData($this->newsApiUrl, 'country=us&apiKey='.$this->newsApikey);

        $newYorkTimesData = $this->getNewsData($this->newYorkTimesApiUrl, 'api-key='.$this->newYorkTimesApiKey);

        $theGuardianData = $this->getNewsData($this->theGuardianAPIUrl, 'api-key='.$this->theGuardianApiKey);
    }

    private function getNewsData(string $url, string $params)
    {
        $response = Http::get($url.'?'.$params);

        return $response->json();
    }
}
