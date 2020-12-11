<?php

class Genre extends Model
{
    public $id;
    public $label;
    public $slug;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->label = json_decode($data['label'])->fr;
        $this->slug = json_decode($data['slug'])->fr;
    }

    static public function getForMovieId($id)
    {
        // 1. Effectuer la requête en base de données
        $results = static::fetchAll(
            'SELECT g.id, g.label, g.slug
            FROM genres g
            JOIN genre_movie gm ON gm.genre_id = g.id
            WHERE gm.movie_id = :id
            ORDER BY gm.order;
        ', ['id' => $id]);

        // 2. Créer une instance par ligne récupérée
        foreach ($results as $index => $line) {
            $results[$index] = new Genre($line);
        }

        // 3. Retourner toutes ces instances ainsi créées
        return $results;
    }
}