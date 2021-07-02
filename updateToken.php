<?php
    require './vendor/autoload.php';
    use GuzzleHttp\Client;
    const TOKEN_URL = 'https://auth.aweber.com/oauth2/token';
    
    $credentials = parse_ini_file('credentials.ini', true);
    if(sizeof($credentials) == 0 ||
       !array_key_exists('clientId', $credentials) ||
       !array_key_exists('clientSecret', $credentials) ||
       !array_key_exists('accessToken', $credentials) ||
       !array_key_exists('refreshToken', $credentials)) {
        echo "No credentials.ini exists, or file is improperly formatted.\n";
        echo "Please create new credentials.";
        exit();
    }
    $client = new GuzzleHttp\Client();
    $clientId = $credentials['clientId'];
    $clientSecret = $credentials['clientSecret'];
    $response = $client->post(
        TOKEN_URL, [
            'auth' => [
                $clientId, $clientSecret
            ],
            'json' => [
                'grant_type' => 'refresh_token',
                'refresh_token' => $credentials['refreshToken']
            ]
        ]
    );

    $body = $response->getBody();
    $newCreds = json_decode($body, true);
    $accessToken = $newCreds['access_token'];
    $refreshToken = $newCreds['refresh_token'];

$fp = fopen('credentials.ini', 'wt');
fwrite($fp,
"clientId = {$clientId}
clientSecret = {$clientSecret}
accessToken = {$accessToken}
refreshToken = {$refreshToken}");
fclose($fp);
chmod('credentials.ini', 0600);
echo "Updated credentials.ini with your new credentials\n";
?>