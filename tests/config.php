<?php
namespace ProcessMaker\Flysystem\Adapter\MSGraph\Test;

use Exception;

/**
 * To run the tests, you must supply your Microsoft Azure Application
 * ID and Password. This must be done via environment variables before 
 * loading the tests.
 * 
 * 
*/
if(!getenv("test_app_id") || !getenv("test_app_password")) {
    throw new Exception("No application ID or password specified in environment.");
}

define("APP_ID", getenv("test_app_id"));
define("APP_PASSWORD", getenv("test_app_password"));
define("OAUTH_AUTHORITY", getenv("test_oauth_authority") ? getenv("test_oauth_authority") : "https://login.microsoftonline.com/common");
define("OAUTH_AUTHORIZE_ENDPOINT", getenv("test_oauth_authorize_endpoint") ? getenv("test_oauth_authorize_endpoint") : "/oauth2/v2.0/authorize");
define("OAUTH_TOKEN_ENDPOINT", getenv("test_oauth_token_endpoint") ? getenv("test_oauth_token_endpoint") : "/oauth2/v2.0/token");
define("SHAREPOINT_SITE_ID", getenv("test_sharepoint_site_id") ? getenv("test_sharepoint_site_id") : "example.com");
define("TEST_FILE_PREFIX", getenv("test_file_prefix") ? getenv("test_file_prefix") : "");