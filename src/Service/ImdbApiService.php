<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Movie;

class ImdbApiService
{
    private $httpClient;
    private $apiKey;
    private $entityManager;

    public function __construct(HttpClientInterface $httpClient, string $apiKey, EntityManagerInterface $entityManager)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->entityManager = $entityManager;
    }

    public function getMoviePosterUrl(Movie $movie): ?string
    {
        // Vérifier si l'URL du poster existe déjà
        if ($movie->getPosterUrl()) {
            return $movie->getPosterUrl();
        }

        // Effectuer la requête à l'API IMDB
        $response = $this->httpClient->request('GET', 'https://imdb8.p.rapidapi.com/title/find', [
            'query' => [
                'q' => $movie->getTitle(), // Utiliser le titre du film pour la recherche
            ],
            'headers' => [
                'X-RapidAPI-Key' => $this->apiKey,
                'X-RapidAPI-Host' => 'imdb8.p.rapidapi.com',
            ],
        ]);

        $data = json_decode($response->getContent(), true);

        // Vérifier si au moins un résultat a été trouvé et si l'URL du poster existe dans le premier résultat de la recherche
        if (isset($data['results'][0]['image']['url'])) {
            $posterUrl = $data['results'][0]['image']['url'];
            $movie->setPosterUrl($posterUrl);
            $this->entityManager->flush(); 
            return $posterUrl;
        }

        return null; // Aucun poster trouvé
    }
}

