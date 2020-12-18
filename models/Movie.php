<?php

class Movie extends Model
{
    public $id;
    public $slug;
    public $title;
    public $description;
    public $featured_img;
    public $featured_alt;
    public $released_at;
    public $trailer_url;
    public $public_label;
    public $public_slug;
    public $public_color;
    public $rating;
    public $producers;
    public $genres;
    public $cover_img;
    public $cover_alt;

    public function __construct($data)
    {
        $this->id = $data['id'] ?? null;
        $this->slug = ($data['slug'] ?? null) ? json_decode($data['slug'])->fr : null;
        $this->title = ($data['title'] ?? null) ? json_decode($data['title'])->fr : null;
        $this->description = ($data['description'] ?? null) ? json_decode($data['description'])->fr : null;
        $this->featured_img = $data['featured_img'] ?? null;
        $this->featured_alt = ($data['featured_alt'] ?? null) ? json_decode($data['featured_alt'])->fr : null;
        $this->released_at = $data['released_at'] ?? null;
        $this->trailer_url = $data['trailer_url'] ?? null;
        $this->public_label = ($data['public_label'] ?? null) ? json_decode($data['public_label'])->fr : null;
        $this->public_slug = ($data['public_slug'] ?? null) ? json_decode($data['public_slug'])->fr : null;
        $this->public_color = $data['public_color'] ?? null;
        $this->rating = $data['rating'] ?? null;
        $this->producers = $data['producers'] ?? null;
        $this->genres = $data['genres'] ?? null;
        $this->cover_img = $data['cover_img'] ?? null;
        $this->cover_alt = ($data['cover_alt'] ?? null) ? json_decode($data['cover_alt'])->fr : null;
    }

    static public function getFeatured()
    {
        // 1. Effectuer la requête en base de données
        $result = static::fetch(
            'SELECT 
            m.id,
            m.title,
            m.slug,
            m.description,
            m.featured_img,
            m.featured_alt,
            m.released_at,
            m.trailer_url,
            p.label AS public_label,
            p.slug AS public_slug,
            p.color AS public_color,
            r.rating
            FROM movies m
            JOIN publics p ON p.id = m.public_id
            LEFT JOIN (SELECT movie_id, AVG(rating) AS rating FROM reviews GROUP BY movie_id) r ON r.movie_id = m.id
            WHERE m.featured = 1
            AND (m.published_until IS NULL OR m.published_until > NOW())
            AND m.published_at <= NOW()
            AND m.deleted_at IS NULL
            ORDER BY m.released_at DESC
            LIMIT 1;'
        );

        // 2. Ajouter les producteurs de ce film au résultat
        $result['producers'] = Producer::getForMovieId($result['id']);

        // 3. AJouter les genres de ce film au résultat
        $result['genres'] = Genre::getForMovieId($result['id']);

        // 3. Créer une instance pour le résultat récupéré
        $result = new Movie($result);

        // 4. Retourner l'instance ainsi créée
        return $result;
    }

    static public function getRecentlyReleasedMovies()
    {
        $results = static::fetchAll('
            SELECT m.id, m.cover_img, m.cover_alt, m.slug
            FROM movies m
            WHERE (m.published_until IS NULL OR m.published_until > NOW())
            AND m.published_at <= NOW()
            AND m.deleted_at IS NULL
            ORDER BY m.released_at DESC
            LIMIT 6;
        ');

        foreach ($results as $index => $line) {
            $results[$index] = new Movie($line);
        }

        return $results;
    }
}