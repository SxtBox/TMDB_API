<?php

if (file_exists(__DIR__)."/TMDB_API/TMDB_API.php") {
    require (__DIR__)."/TMDB_API/TMDB_API.php";
}

// OR
//require_once (__DIR__)."/TMDB_API/TMDB_API.php";

$TMDB_API = new TMDB\API\TMDB_API();

// $data = $TMDB_API->get_videos("157336");
// $data = $TMDB_API->get_movie_by_genre("28");
// $data = $TMDB_API->get_movies_data();
// $data = $TMDB_API->get_top_rated_movies();
// $data = $TMDB_API->get_top_movies_one("539972");
// $data = $TMDB_API->get_stream_movie_data("539972");
// $data = $TMDB_API->get_movies_list();
// $data = $TMDB_API->get_videos("157336");
// $data = $TMDB_API->get_movies_genres();
// $data = $TMDB_API->get_guest_authentication_session();
 $data = $TMDB_API->get_movie_by_id("939243");
//echo $youtube_embed_data = $TMDB_API->make_youtube_embed_url("kGLWT792tQc"); // USE AS RAW DATA

$json_data = str_replace("\\/", "/", json_encode($data,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
echo $json_data;