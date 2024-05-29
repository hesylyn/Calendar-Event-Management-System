<?php 

// Database configuration    
define('DB_HOST', 'localhost'); 
define('DB_USERNAME', 'root'); 
define('DB_PASSWORD', ''); 
define('DB_NAME', 'calendar_event'); 

// Google API configuration 
define('GOOGLE_CLIENT_ID', '1077180541610-fcuthqrivs9uo3ct1ogu1s5tv6d8kjj6.apps.googleusercontent.com'); 
define('GOOGLE_CLIENT_SECRET', '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'); 
define('GOOGLE_OAUTH_SCOPE', 'https://www.googleapis.com/auth/calendar'); 
define('REDIRECT_URI', 'http://localhost/calendarevent/google_calendar_event_sync.php'); 

// Start session 
if(!session_id()) session_start(); 

// Create database connection 
$db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection 
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Google OAuth URL 
$googleOauthURL = 'https://accounts.google.com/o/oauth2/auth?scope=' . urlencode(GOOGLE_OAUTH_SCOPE) . '&redirect_uri=' . REDIRECT_URI . '&response_type=code&client_id=' . GOOGLE_CLIENT_ID . '&access_type=online'; 

?>