<?php
require './vendor/autoload.php';
use GuzzleHttp\Client;
const BASE_URL = 'https://api.aweber.com/1.0/';
require_once ('db.class.php');

    class Controller extends Database{

    function getClient(){ // Function to get the ClientID

      // Create a Guzzle client
      $client = new GuzzleHttp\Client();

      // Load credentials
      $credentials = parse_ini_file('./credentials.ini');
      $accessToken = $credentials['accessToken'];
      $bearer = 'Bearer '.$accessToken;
      $headers = [
          'User-Agent' => 'AWeber-PHP-code-sample/1.0',
          'Accept' => 'application/json',
          'Authorization' => $bearer,
      ];

      $url = BASE_URL . 'accounts';
      $response = $client->get($url, ['headers' => $headers]);
      $body = json_decode($response->getBody(), true);
      return $body['entries'][0]['id'];
    }

    function getlist($clientid){ // Function to get ListID  based on ClientID - (Free Account only has 1 list)
        
  
        // Create a Guzzle client
        $client = new GuzzleHttp\Client();
        // Load credentials
        $credentials = parse_ini_file('./credentials.ini');
        $accessToken = $credentials['accessToken'];

        $bearer = 'Bearer '.$accessToken;
        $headers = [
            'User-Agent' => 'AWeber-PHP-code-sample/1.0',
            'Accept' => 'application/json',
            'Authorization' => $bearer,
        ];
       
        $url = BASE_URL . "accounts/".$clientid."/lists";

        $response = $client->get($url, ['headers' => $headers]);
        $body = json_decode($response->getBody(), true);
        return $body['entries'][0]['id']; //(Free Account only has 1 list so we always get [0])
    }

    function addSub( $clientid , $list , $data){ // Function to add new Sub 

        // Create a Guzzle client
        $client = new GuzzleHttp\Client();

        // Load credentials
        $credentials = parse_ini_file('./credentials.ini');
        $accessToken = $credentials['accessToken'];
        $bearer = 'Bearer '.$accessToken;

        $externalContent = file_get_contents('http://checkip.dyndns.com/');
        preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);

        $body = [
            'ad_tracking' => 'control panel',
            'custom_fields' => [
              'Date' => $data[4],
              'IP' => $data[2],
              'Time' =>$data[5],
              'URL'=>$data[3]
            ],
            'email' => $data[1],
            'ip_address' => $m[1],
            'last_followup_message_number_sent' => 0,
            'misc_notes' => 'string',
            'name' => $data[0],
            'strict_custom_fields' => true,
            'tags' => [
              'test_new_sub'
            ]
          ];
          $headers = [
              'Content-Type' => 'application/json',
              'Accept' => 'application/json',
              'User-Agent' => 'AWeber-PHP-code-sample/1.0',
              'Authorization' => $bearer,
          ];

          $url = BASE_URL . "accounts/".$clientid."/lists/".$list."/subscribers";
          $response = $client->post($url, ['json' => $body, 'headers' => $headers]);
          $body = json_decode($response->getBody(), true);

          return $body;
          
    }

    function updateSub( $clientid , $list , $data){ // Function to update Sub based on email

      // Create a Guzzle client
      $client = new GuzzleHttp\Client();

      // Load credentials
      $credentials = parse_ini_file('./credentials.ini');
      $accessToken = $credentials['accessToken'];
      $bearer = 'Bearer '.$accessToken;

      $body = [
          'ad_tracking' => 'control panel',
          'custom_fields' => [
            'Date' => $data[4],
            'IP' => $data[2],
            'Time' =>$data[5],
            'URL'=>$data[3]
          ],
          'email' => $data[1],
          'ip_address' => $data[2],
          'last_followup_message_number_sent' => 0,
          'misc_notes' => 'string',
          'name' => $data[0],
          'strict_custom_fields' => true,
          'update_existing' => true,
          'tags' => [
            'test_existing_sub'
          ]
          
        ];
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'User-Agent' => 'AWeber-PHP-code-sample/1.0',
            'Authorization' => $bearer,
        ];
        $url = BASE_URL . "accounts/".$clientid."/lists/".$list."/subscribers";
        $response = $client->post($url, ['json' => $body, 'headers' => $headers]);
        $body = json_decode($response->getBody(), true);

        return $body;     
  }

    function findSubInList($accountId , $listId , $email){ // Function to check if Sub is already created based on email

      // Create a Guzzle client
      $client = new GuzzleHttp\Client();
      // Load credentials
      $credentials = parse_ini_file('./credentials.ini');
      $accessToken = $credentials['accessToken'];
      $bearer = 'Bearer '.$accessToken;
      $headers = [
        'User-Agent' => 'AWeber-PHP-code-sample/1.0',
        'Accept' => 'application/json',
        'Authorization' => $bearer,
      ];
      $url = BASE_URL . "accounts/{$accountId}/lists/{$listId}/subscribers";
      $params = [
          'ws.op' => 'find',
          'email' => $email,
      ];
      $findUrl = $url . '?' . http_build_query($params);
      $response = $client->get($findUrl, ['headers' => $headers]);
      $body = json_decode($response->getBody(), true);
      return $body;
    }

    function addSubDB($data){ // Function to insert a new sub in DB and create his tag relation

        $query =  "INSERT INTO subscribers (name, email, date, time, ip, url) VALUES ('".$data[0]."','".$data[1]."', '".$data[4]."', '".$data[5]."', '".$data[2]."' , '".$data[3]."')" ;
        $query2 = "SELECT id FROM subscribers WHERE email = '".$data[1]."'";
      
        $this->connect();
        $result1 = $this->execute($query);
        $result2 = $this->execute($query2);
        $result2 = $result2->fetch_assoc();
        $query = "INSERT INTO tag (subscriber_id, tag) VALUES ('".$result2['id']."' , 'test_new_sub')";
        $result = $this->execute($query);
        $this->disconnect();
    }

    function updateSubDB($data){ // Function to update sub in DB and create new tag if necessary
 
        $query =  "UPDATE subscribers SET name = '".$data[0]."', date = '".$data[4]."' , time = '".$data[5]."', ip = '".$data[2]."', url = '".$data[3]."'  WHERE email = '".$data[1]."'" ;
        $query2 = "SELECT id FROM subscribers WHERE email = '".$data[1]."'";
       
        $this->connect();
        $result1 = $this->execute($query);
        $result2 = $this->execute($query2);
        $result2 = $result2->fetch_assoc();
        $querytag = "SELECT id FROM tag WHERE subscriber_id = '".$result2['id']."' AND tag ='test_existing_sub'";
        $result = $this->execute($querytag);
       
        if($result->num_rows == 0){
          $query = "INSERT INTO tag (subscriber_id, tag) VALUES ('".$result2['id']."' , 'test_existing_sub')";
        }
        
        $result = $this->execute($query);
        $this->disconnect();
    }

  }
?>
