<?php

class Home
{
    public $header;
    public $links;
    public $featured;
    public $recentlyReleasedMovies;
    public $teased;
    public $articles;
    public $publishedMovies;

    public function __construct($header)
    {
        $this->header = $header;
        $this->links = NavigationLink::getAllDisplayed();
        $this->featured = Movie::getFeatured();
        $this->recentlyReleasedMovies = Movie::getRecentlyReleased();
        $this->teased = Movie::getTeased();
        $this->articles = Article::getRecentlyPublished();
        $this->publishedMovies = Movie::getRecentlyPublished();
    }

    public function render()
    {
        extract(get_object_vars($this));
        include('views/home.php');
    }
}