    <?php
    class ApiRequest
    {
    	private static $instance;

    	public static function getInstance() {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        	return self::$instance;
    	
    	}		

        public function sendGet($url)
        {

          $response = \Httpful\Request::get($url)->expectsJson()->send();
          return $response->body;
          
        }
   }     