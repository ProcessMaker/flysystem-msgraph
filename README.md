# flysystem-msgraph
A Flysystem Adapter that supports Microsoft OneDrive and Sharepoint Document Libraries using Microsoft Graph

## Setting Up A Background App For Microsoft Graph
This flysystem adapter operates as a Background Service. See https://docs.microsoft.com/en-us/graph/auth-v2-service for more detailed information 
on how to configure an application.

When setting up permissions, you must add Application Permissions to specify File.ReadWrite.All.

Once your app is completed, you'll need your Administrator to consent to the application using the Azure Portal at https://portal.azure.com/.

## Using with OneDrive

## Using with Sharepoint Document Libraries


## Running Tests
The tests are functional in which a valid Microsoft Office 365/Azure environment must be available. Once you have your 
application id and password and the application has consent from the administrator, you must provide the credentials 
through environment variables and then run the phpunit test suites.  The environment variables requires are:

* APP_ID : The application ID you registered
* APP_PASSWORD : The application Password you generated
* OAUTH_AUTHORITY (optional) : The OAuth2 Authority URL to use. Defaults to https://login.microsoftonline.com/common
* OAUTH_AUTHORIZE_ENDPOINT (optional) : The OAuth2 Authorize Endpoint to use. Defaults to /oauth2/v2.0/authorize
* OAUTH_TOKEN_ENDPOINT (optional) : The OAuth2 Token Endpoint to use. Defaults to /oauth2/v2.0/token
*