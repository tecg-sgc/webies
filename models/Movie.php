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

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->slug = json_decode($data['slug'])->fr;
        $this->title = json_decode($data['title'])->fr;
        $this->description = json_decode($data['description'])->fr;
        $this->featured_img = $data['featured_img'];
        $this->featured_alt = json_decode($data['featured_alt'])->fr;
        $this->released_at = $data['released_at'];
        $this->trailer_url = $data['trailer_url'];
        $this->public_label = json_decode($data['public_label'])->fr;
        $this->public_slug = json_decode($data['public_slug'])->fr;
        $this->public_color = $data['public_color'];
        $this->rating = $data['rating'];
        $this->producers = $data['producers'];
        $this->genres = $data['genres'];
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
}