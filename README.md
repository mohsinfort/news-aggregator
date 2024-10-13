# News Aggregator
This news aggregator application is designed to provide users with a centralized platform for discovering and accessing news articles from various sources. It leverages the power of APIs to fetch news content and presents it in a user-friendly format.

## Key Features
- **Personalized News Feed** Users can customize their news feed based on their interests and preferences.
- **Multiple News Sources:** Aggregates news from a variety of reputable sources, ensuring a diverse range of perspectives.
- **Advanced Search:** Allows users to search for specific news articles or topics using keywords or filters.

## Technologies
 - [laravel](https://laravel.com/)
 - [docker](https://www.docker.com/get-started/)

## Getting Started
- Install [docker](https://www.docker.com/get-started/)
- Install [docker-compose](https://docs.docker.com/compose/install/)
  - usually auto installed with docker client
- Clone project and checkout to develop branch 
- cp .env.example .env
  - update values for keys
## Run
```
docker-compose up -d
```
*should able to see images and running containers into docker client*

## Testing
For Laravel PHPUnit tests run
```
docker-compose exec api php artisan test
```
Or run this command in Api container terminal.
```
php artisan test
```
