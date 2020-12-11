<?php

class Home
{
    public $header;
    public $links;
    public $featured;

    public function __construct($header)
    {
        $this->header = $header;
        $this->links = NavigationLink::getAllDisplayed();
        $this->featured = Movie::getFeatured();

        var_dump($this->featured); die();
    }

    public function render()
    {
        extract(get_object_vars($this));
        include('views/home.php');
    }
}