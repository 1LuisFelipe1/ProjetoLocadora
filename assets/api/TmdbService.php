<?php
class TmdbService
{
    private const BASE_URL  = 'https://api.themoviedb.org/3';
    private const IMAGE_URL = 'https://image.tmdb.org/t/p/';
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function search(string $query, string $language = 'pt-BR'): array
    {
        $data = $this->get('/search/movie', [
            'query'    => $query,
            'language' => $language,
        ]);

        return array_map([$this, 'normalizeResult'], $data['results'] ?? []);
    }
    public function getDetails(int $tmdbId, string $language = 'pt-BR'): ?array
    {
        $data = $this->get("/movie/{$tmdbId}", [
            'language'           => $language,
            'append_to_response' => 'release_dates',
        ]);

        if (empty($data['id'])) {
            return null;
        }

        return [
            'tmdb_id'       => $data['id'],
            'titulo'        => $data['title'],
            'titulo_orig'   => $data['original_title'],
            'ano'           => (int) substr($data['release_date'] ?? '', 0, 4),
            'sinopse'       => $data['overview'] ?? '',
            'genero'        => implode(', ', array_column($data['genres'] ?? [], 'name')),
            'classificacao' => $this->extractRating($data),
            'poster_url'    => $this->imageUrl($data['poster_path'], 'w342'),
            'backdrop_url'  => $this->imageUrl($data['backdrop_path'], 'w780'),
            'nota'          => round((float)($data['vote_average'] ?? 0), 1),
        ];
    }

    private function normalizeResult(array $item): array
    {
        return [
            'tmdb_id'    => $item['id'],
            'titulo'     => $item['title'],
            'ano'        => (int) substr($item['release_date'] ?? '', 0, 4),
            'poster_url' => $this->imageUrl($item['poster_path'], 'w185'),
            'nota'       => round((float)($item['vote_average'] ?? 0), 1),
        ];
    }

    private function imageUrl(?string $path, string $size): ?string
    {
        if (!$path) return null;
        return self::IMAGE_URL . $size . $path;
    }

    private function extractRating(array $data): string
    {
        $map = ['L' => 'Livre', '10' => '10', '12' => '12', '14' => '14', '16' => '16', '18' => '18'];
        $results = $data['release_dates']['results'] ?? [];

        foreach ($results as $country) {
            if ($country['iso_3166_1'] !== 'BR') continue;
            foreach ($country['release_dates'] as $rd) {
                $cert = $rd['certification'] ?? '';
                if (isset($map[$cert])) return $map[$cert];
            }
        }
        return 'Livre';
    }

    private function get(string $endpoint, array $params = []): array
    {
        $params['api_key'] = $this->apiKey;
        $url = self::BASE_URL . $endpoint . '?' . http_build_query($params);

        $ctx = stream_context_create(['http' => [
            'timeout' => 5,
            'header'  => "Accept: application/json\r\n",
        ]]);

        $raw = @file_get_contents($url, false, $ctx);
        if ($raw === false) {
            throw new RuntimeException("Falha ao contactar a API do TMDB.");
        }

        return json_decode($raw, true) ?? [];
    }
}