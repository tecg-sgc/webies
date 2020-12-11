<?php

class Producer extends Model
{
    public $id;
    public $firstname;
    public $lastname;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->firstname = $data['firstname'];
        $this->lastname = $data['lastname'];
    }

    static public function getForMovieId($id)
    {
        // 1. Effectuer la requête en base de données
        $results = static::fetchAll(
            'SELECT 
            p.id,
            p.firstname,
            p.lastname
            FROM producers p
            JOIN movie_producer mp ON mp.producer_id = p.id
            WHERE mp.movie_id = :id;
        ', ['id' => $id]);

        // 2. Créer une instance par ligne récupérée
        foreach ($results as $index => $line) {
            $results[$index] = new Producer($line);
        }

        // 3. Retourner toutes ces instances ainsi créées
        return $results;
    }
}