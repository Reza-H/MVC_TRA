<?php
  function Redirect($url, $permanent = false)
  {
      if (headers_sent() === false)
      {
          header('Location: ' . URLROOT . '/' . $url, true, ($permanent === true) ? 301 : 302);
      }
  
      exit();
  }