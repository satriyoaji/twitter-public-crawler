<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TwitterController extends Controller
{

    public function searchTweets($search)
    {
        $response = Http::withToken(env('TWITTER_BEARER_TOKEN'))
        ->get('https://api.twitter.com/2/tweets/search/recent', [
            'query' => $search,
            'tweet.fields' => 'created_at',
            'expansions' => 'author_id',
            'user.fields' => 'created_at',
            'max_results' => 20,
        ]);

        return $response->json();
    }

    /*
     * Just minimum for Academic Research access on Twitter Developer Platform.
     */
    public function searchAllTweets($search)
    {
        $response = Http::withToken(env('TWITTER_BEARER_TOKEN'))
        ->get('https://api.twitter.com/2/tweets/search/all', [
            'query' => $search,
            'tweet.fields' => 'created_at',
            'expansions' => 'author_id',
            'user.fields' => 'created_at',
            'max_results' => 100,
        ]);

        return $response->json();
    }

    public function searchUserById($id)
    {
        $response = Http::withToken(env('TWITTER_BEARER_TOKEN'))
        ->get('https://api.twitter.com/2/users/'.$id);

        return $response->json();
    }

    public function searchUserByUsername($username)
    {
        $response = Http::withToken(env('TWITTER_BEARER_TOKEN'))
        ->get('https://api.twitter.com/2/users/by/username/'.$username);

        return $response->json();
    }

    public function queryTwitter($search)
    {
//        $settings = array(
//            'oauth_access_token' => env('OAUTH_ACCESS_TOKEN'),
//            'oauth_access_token_secret' => env('OAUTH_TOKEN_SECRET'),
//            'consumer_key' => env('TWITTER_API_KEY'),
//            'consumer_secret' => env('TWITTER_API_KEY_SECRET')
//        );
//        $url = 'https://api.twitter.com/1.1/followers/ids.json';
//        $getfield = '?screen_name=J7mbo';
//        $requestMethod = 'GET';
//
//        $twitter = new \TwitterAPIExchange($settings);
//        $results =  $twitter->setGetfield($getfield)
//            ->buildOauth($url, $requestMethod)
//            ->performRequest();
//        dd($results);

        $url = "https://api.twitter.com/1.1/search/tweets.json";
        if($search != "")
            $search = $search;
        $query = array( 'count' => 100, 'q' => urlencode($search), "result_type" => "recent");
        $oauth_access_token = env('OAUTH_ACCESS_TOKEN');
        $oauth_access_token_secret = env('OAUTH_TOKEN_SECRET');
        $consumer_key = env('TWITTER_API_KEY');
        $consumer_secret = env('TWITTER_API_KEY_SECRET');

        $oauth = array(
            'oauth_consumer_key' => $consumer_key,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $oauth_access_token,
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0');

        $base_params = empty($query) ? $oauth : array_merge($query,$oauth);
        $base_info = $this->buildBaseString($url, 'GET', $base_params);
        $url = empty($query) ? $url : $url . "?" . http_build_query($query);

        $composite_key = rawurlencode($consumer_secret) . '&' . rawurlencode($oauth_access_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $header = array($this->buildAuthorizationHeader($oauth), 'Expect:');
        $options = array( CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false);

        $feed = curl_init();
        curl_setopt_array($feed, $options);
        $json = curl_exec($feed);
        curl_close($feed);
        return  json_decode($json);
    }

    public function buildBaseString($baseURI, $method, $params)
    {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    public function buildAuthorizationHeader($oauth)
    {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
    }
}
