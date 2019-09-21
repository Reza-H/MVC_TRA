<?php
  class Pages extends Controller{
      private $postModel;

      public function __construct(){
       
    }// End __construct
    public function index(){
        if (isLoggedIn()) {
          Redirect('posts');
        }

        $data = [
            'title' => 'Share Posts',
            'description' => 'simple social network built on the TravercyMVC PHP framework'
          ];
         $this->view('pages/index', $data);
    }// End index
    public function about(){


      $data = [
        'title' => 'About Share Posts',
        'description' => 'App to share posts'
        ];

      $this->view('pages/about', $data);

    }// End about
  }// End class Pages