<?php

class Home
{
    public $header;
    public $links;

    public function __construct($header)
    {
        $this->header = $header;
        $this->links = NavigationLink::getAllDisplayed();
    }

    public function render()
    {
        extract(get_object_vars($this));
        include('views/home.php');
    }
}