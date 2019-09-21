<?php
  /* 
   * App Core Class
   * Creates URL & loads core controllers
   * URL FORMAT - /controller/method/params
   */
  
  class Core {
      protected $currentController = 'Pages';
      protected $currentMethod = 'index';
      protected $params = [];
  
      public function __construct(){
        // var_dump($this->getUrl());

        $url = $this->getUrl();

        //------------------------------------------------------------------------
        // Look in controllers for first value
        if(file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
          // If exists, set as controller
          $this->currentController = ucwords($url[0]);
          // Unset 0 index
          unset($url[0]);
        }

        // Require the controller
        require_once '../app/controllers/' . $this->currentController . '.php';

        // Instantiate controller Class
        $this->currentController = new $this->currentController;
        //------------------------------------------------------------------------
        // Check for second part o url
        if(isset($url[1])){
          // Check to see if method exists in controller
          if(method_exists($this->currentController, $url[1])){
            $this->currentMethod = $url[1];
            // Unset 1 index
            unset($url[1]);

          }
        }
        //------------------------------------------------------------------------
        // Get params
        $this->params = $url ? array_values($url) : [];

        // Call a callback whit array of params
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

      }// End __construct

      public function getUrl(){
        if(isset($_GET['url'])){
          $url = rtrim($_GET['url']);
          $url = filter_var($url,FILTER_SANITIZE_URL);
          $url = explode('/', $url);

          return $url;
        }
      }

  }// End Class Core