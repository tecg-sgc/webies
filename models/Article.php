<?php

class Article extends Model
{
    public $id;
    public $title;
    public $excerpt;
    public $published_at;

    public function __construct($data)
    {
        $this->id = $data['id'];
        $this->title = json_decode($data['title'])->fr;
        $this->excerpt = json_decode($data['excerpt'])->fr;
        $this->published_at = $data['published_at'];
    }

    static public function getRecentlyPublished()
    {
        $results = static::fetchAll('
            SELECT id, title, excerpt, published_at
            FROM articles
            WHERE published_at <= NOW()
            AND deleted_at IS NULL
            ORDER BY published_at DESC
            LIMIT 2;
        ');

        foreach($results as $index => $line) {
            $results[$index] = new Article($line);
        }

        return $results;
    }
}