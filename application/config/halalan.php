<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// don't change if you already entered some data
$config['halalan']['pin'] = FALSE;
$config['halalan']['password_pin_generation'] = "web";
$config['halalan']['password_pin_characters'] = "nozero";
$config['halalan']['password_length'] = 12;
$config['halalan']['pin_length'] = 4;
$config['halalan']['auth_type'] = "oauth2"; //oauth2 or local
$config['halalan']['auth_domain'] = "up"; //google or up
$config['halalan']['client_id'] = ""; //from Google
$config['halalan']['client_secret'] = ""; //from Google
$config['halalan']['redirect_uri'] = "http://halalan.uplug.org/index.php/gate/voter";
$config['halalan']['captcha'] = TRUE;
$config['halalan']['captcha_length'] = 5;
$config['halalan']['show_candidate_details'] = FALSE;
$config['halalan']['generate_image_trail'] = TRUE;
$config['halalan']['image_trail_path'] = "/home/user/vpt/";

$config['base_url'] = "http://halalan.uplug.org";
$config['encryption_key'] = "";
$config['index_page'] = "index.php";

?>
