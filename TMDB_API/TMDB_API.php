<?php

namespace TMDB\API;

/**
 * Class TMDB_API
 * @package TMDB\API
 */
class TMDB_API
{

    /** Map of standard HTTP status code */
    private const getStatusCode = [
        100 => "Continue",
        101 => "Switching Protocols",
        102 => "Processing",
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-status",
        208 => "Already Reported",
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        306 => "Switch Proxy",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Time-out",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Request Entity Too Large",
        414 => "Request-URI Too Large",
        415 => "Unsupported Media Type",
        416 => "Requested range not satisfiable",
        417 => "Expectation Failed",
        418 => "I\"m a teapot",
        422 => "Unprocessable Entity",
        423 => "Locked",
        424 => "Failed Dependency",
        425 => "Unordered Collection",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        451 => "Unavailable For Legal Reasons",
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Time-out",
        505 => "HTTP Version not supported",
        506 => "Variant Also Negotiates",
        507 => "Insufficient Storage",
        508 => "Loop Detected",
        510 => "Not Extended",
        511 => "Network Authentication Required",
    ];

// Enter API Key https://www.themoviedb.org/settings/api
    public $api_key = "Enter API Key"; 
    public $movies = [];
    public $genres = [];

   public function getStatusCode($code = NULL) {
          if ($code !== NULL) {
                switch ($code) {
                    case 100: $text = "Continue"; break;
                    case 101: $text = "Switching Protocols"; break;
                    case 200: $text = "OK"; break;
                    case 201: $text = "Created"; break;
                    case 202: $text = "Accepted"; break;
                    case 203: $text = "Non-Authoritative Information"; break;
                    case 204: $text = "No Content"; break;
                    case 205: $text = "Reset Content"; break;
                    case 206: $text = "Partial Content"; break;
                    case 300: $text = "Multiple Choices"; break;
                    case 301: $text = "Moved Permanently"; break;
                    case 302: $text = "Moved Temporarily"; break;
                    case 303: $text = "See Other"; break;
                    case 304: $text = "Not Modified"; break;
                    case 305: $text = "Use Proxy"; break;
                    case 400: $text = "Bad Request"; break;
                    case 401: $text = "Unauthorized"; break;
                    case 402: $text = "Payment Required"; break;
                    case 403: $text = "Forbidden"; break;
                    case 404: $text = "Not Found"; break;
                    case 405: $text = "Method Not Allowed"; break;
                    case 406: $text = "Not Acceptable"; break;
                    case 407: $text = "Proxy Authentication Required"; break;
                    case 408: $text = "Request Time-out"; break;
                    case 409: $text = "Conflict"; break;
                    case 410: $text = "Gone"; break;
                    case 411: $text = "Length Required"; break;
                    case 412: $text = "Precondition Failed"; break;
                    case 413: $text = "Request Entity Too Large"; break;
                    case 414: $text = "Request-URI Too Large"; break;
                    case 415: $text = "Unsupported Media Type"; break;
                    case 500: $text = "Internal Server Error"; break;
                    case 501: $text = "Not Implemented"; break;
                    case 502: $text = "Bad Gateway"; break;
                    case 503: $text = "Service Unavailable"; break;
                    case 504: $text = "Gateway Time-out"; break;
                    case 505: $text = "HTTP Version not supported"; break;
                    default:
                    exit('Unknown HTTP Status Code "' . htmlentities($code) . '"');
                    break;
                }

                $protocol = (isset($_SERVER["SERVER_PROTOCOL"]) ? $_SERVER["SERVER_PROTOCOL"] : "HTTP/1.0");

                header($protocol . " " . $code . " " . $text);

                $GLOBALS["http_response_code"] = $code;

            } else {

                $code = (isset($GLOBALS["http_response_code"]) ? $GLOBALS["http_response_code"] : 200);

            }

            return $code;
    }

    public function get_data ($Url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
    }

    public function get_movie_by_genre(int $genre_id): array {
        $get_json_url = "https://api.themoviedb.org/3/discover/movie?api_key=" . $this->api_key . "&with_genres=" . $genre_id;
        $AUTH_SESSION_API_ENDPOINT = $this->get_guest_authentication_session();
        if ($AUTH_SESSION_API_ENDPOINT["success"]) {
            $data = $this->get_data($get_json_url);

            if ($this->getStatusCode() === 200) {
                return json_decode($data, true);
            }
        }
    }

    public function get_movies_data(): array {
        $AUTH_SESSION_API_ENDPOINT = $this->get_guest_authentication_session();
        if ($AUTH_SESSION_API_ENDPOINT["success"]) {
            $genres = $this->get_movies_genres();
            $movies = $this->get_top_rated_movies();
            $top_movies = $this->get_top_movies_one();
        }
        return [
            "top"    => $top_movies ? $top_movies : [],
            "genres" => $genres ? $genres : [],
            "movies" => $movies ? $movies : []
        ];
    }

    public function get_top_rated_movies(): array {
        $get_json_url = "https://api.themoviedb.org/3/movie/top_rated?api_key=" . $this->api_key . "&language=en-US";
        $data = $this->get_data($get_json_url);
        if ($this->getStatusCode() === 200) {
            $this->movies = json_decode($data, true);
            return $this->movies ;
        } else {
            return [
                "status" => 404,
                "message" => "Movies data not found"
            ];
        }
    }

    public function get_top_movies_one() : array {
        $get_json_url = "https://api.themoviedb.org/3/movie/top_rated?api_key=" . $this->api_key . "&language=en-US&page=1";
        $data = $this->get_data($get_json_url);
        if ($this->getStatusCode() === 200) {
           $movie = json_decode($data, true);
           if ($movie){
               $movie_id = $movie["results"][0]["id"];
               $movie_video_data = $this->get_videos($movie_id);
               return [
                   "movie_id"    => $movie_id,
                   "movie_key"   => $movie_video_data["results"][0]["key"],
                   "youtube_trailer" => $this->make_youtube_embed_url($movie_video_data["results"][0]["key"])
               ];
           }
        } else {
            return [
                "status" => 404,
                "message" => "Top Movie Data Not Found"
            ];
        }
    }

    public function get_stream_movie_data(string $movie_id): array {
        $get_json_url = "https://api.themoviedb.org/3/movie/{$movie_id}?api_key=" . $this->api_key . "&language=en-US";
        $data = $this->get_data($get_json_url);
			if ($this->getStatusCode() === 200) {
            $movie = json_decode($data, true);
            if ($movie) {
                $movie_video_data = $this->get_videos($movie_id);
                return [
                    "status"          => $this->getStatusCode(),
                    "movie_id"        => $movie_id,
                    "movie_key"       => $movie_video_data["results"][0]["key"],
                    "youtube_trailer" => $this->make_youtube_embed_url($movie_video_data["results"][0]["key"]),
                ];
            }
        } else {
            return [
                "status" => 404,
                "message" => "Movie ID#{$movie_id} Not Found"
            ];
        }
    }

    public function get_movies_list() {
        $AUTH_SESSION_API_ENDPOINT = $this->get_guest_authentication_session();
        if ($AUTH_SESSION_API_ENDPOINT["success"]) {
            $movies = $this->get_top_rated_movies()["results"];

            $results_data = [];
            foreach ($movies as $index => $item) {
                $results_data[] = [
                    "id" => $item["id"],
                    "title" => strtolower($item["title"])
                ];
            }

            return $results_data;
        } else {
            return [];
        }
    }

    public function get_videos(int $movie_id) {
        $movieget_json_url = "https://api.themoviedb.org/3/movie/283566/videos?api_key=" . $this->api_key . "&language=en-US";
        $top_moviesData = $this->get_data($movieget_json_url);

			if ($this->getStatusCode() === 200) {
            return json_decode($top_moviesData, true);
        } else {
            return [
                "status" => 404,
                "message" => "Top Movie Data Not Found"
            ];
        }
    }

    public function get_movies_genres(): array {
        $get_json_url = "https://api.themoviedb.org/3/genre/movie/list?api_key=" . $this->api_key . "&language=en-US";
        $data = $this->get_data($get_json_url);
		if ($this->getStatusCode() === 200) {
            $this->gender = json_decode($data, true);
            return $this->gender;
        } else {
            return  [
                "status" => 404,
                "message" => "Genre Movies Data not Found"
            ];
        }
    }

    public function get_guest_authentication_session(): array {
        $get_json_url = "https://api.themoviedb.org/3/authentication/guest_session/new?api_key=" . $this->api_key;
        $response = $this->get_data($get_json_url);
        if ($this->getStatusCode() === 200) {
			return json_decode(($response), true);
        }
        return [
           "status"  => $this->getStatusCode(),
            "message" => "Connection not Established"
        ];
    }

    public function get_movie_by_id(int $movie_id): array {
        $get_json_url = "https://api.themoviedb.org/3/movie/{$movie_id}?api_key={$this->api_key}&language=en-US";
        $response = $this->get_data($get_json_url);
		if ($this->getStatusCode() === 200) {
		   $movie = json_decode($response, true);

           if ($this->get_stream_movie_data($movie_id)["status"] === 200) {
               $movie["youtube_trailer"] = $this->get_stream_movie_data($movie_id)["youtube_trailer"];
           }

           return $movie;
        }
        return [
		"status" => "Movie Not Found",
            "message" => "Movie Not Found"
        ];
    }

    public function make_youtube_embed_url(string $id): string {
        return "https://www.youtube.com/embed/{$id}";
    }
}

/*
$TMDB_API = new TMDB_API();
$data = $TMDB_API->get_videos("157336");
$json_data = str_replace('\\/', '/', json_encode($data,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo $json_data;
*/
?>