<?php

class NavigationLink extends Model
{
    public $url;
    public $label;
    public $icon;

    public function __construct($data)
    {
        $this->url = $data['url'];
        $this->label = $data['label'];
        $this->icon = $data['icon'];
    }

    static public function getAllDisplayed()
    {
        // 1. Effectuer la requête en base de données
        $results = static::fetchAll('SELECT `url`, `label`, `icon` FROM `navigation_links` ORDER BY `order` ASC;');

        // 2. Créer une instance par ligne récupérée
        foreach ($results as $index => $line) {
            $results[$index] = new NavigationLink($line);
        }

        // 3. Retourner toutes ces instances ainsi créées
        return $results;
    }
}