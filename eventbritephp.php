<?php
  class eventbrite {
    var $endpoint = 'https://www.eventbriteapi.com/v3/';
    var $token = '';
    
    function __construct($oauth_token) {
      $this->token = $oauth_token;
    }
    
    function __call($method, $args) {
      // Get the URI we need.
      $uri = $this->build_uri($method, $args);
      // Construct the full URL.
      $request_url = $this->endpoint . $uri;
      $request_url .= "?";
      if (!is_null($args[1])) {
        foreach ($args[1] as $key => $value) {
          $request_url .= $key . "=" . $value . "&";
        }
      }
      
      $request_url .= "token=" . $this->token;
      // echo $request_url . "<br>";
      // This array is used to authenticate our request.
      $options = array(
        'http' => array(
          'method' => 'GET',
          'header'=> "Authorization: Bearer " . $this->token
        )
      );
      // Call the URL and get the data.
      $resp = file_get_contents($request_url, false, stream_context_create($options));
      // Return it as arrays/objects.
      return json_decode($resp, true);
    }
    
    function build_uri($method, $args) {
      // Get variables from the $args.
      extract($args[0]);
      // Get rid of the args array.
      unset($args);
      // Create an array of all the vars within this function scope.
      // This should be at most 'method', 'id' and 'data'.
      $vars = get_defined_vars();
      // Put them together with a slash.
      $uri = implode($vars, '/');
      return $uri;
    }
  }
?>