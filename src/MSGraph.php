<?php
namespace ProcessMaker\Flysystem\Adapter;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use ProcessMaker\Flysystem\Adapter\MSGraph\AuthException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class MSGraph extends AbstractAdapter 
{
    // Our Microsoft Graph Client
    private $client;

    public function __construct($appId, $appPassword, $tokenEndpoint)
    {
        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => $appId,
            'clientSecret' => $appPassword,
            'urlAuthorize' => '',
            'urlResourceOwnerDetails' => '',
            'urlAccessToken' => $tokenEndpoint,
        ]);

        try {
            $accessToken = $oauthClient->getAccessToken('client_credentials', [
                'scope' => 'https://graph.microsoft.com/.default'
            ]);
            var_dump($accessToken);
        } catch(IdentityProviderException $e) {
            throw new AuthException($e->getMessage());
        }
    }

    public function has($path)
    {
        return false;
    }

    public function read($path)
    {
        return false;
    }
    
    public function readStream($path)
    {

    }

    public function listContents($directory = '', $recursive = false)
    {

    }

    public function getMetadata($path)
    {

    }

    public function getSize($path)
    {

    }

    public function getMimetype($path)
    {

    }

    public function getTimestamp($path)
    {

    }

    public function getVisibility($path)
    {

    }

    // Write methods
    public function write($path, $contents, Config $config)
    {

    }

    public function writeStream($path, $resource, Config $config)
    {

    }

    public function update($path, $contents, Config $config)
    {

    }

    public function updateStream($path, $resource, Config $config)
    {

    }

    public function rename($path, $newpath)
    {

    }

    public function copy($path, $newpath)
    {

    }

    public function delete($path)
    {

    }

    public function deleteDir($dirname)
    {

    }

    public function createDir($dirname, Config $config)
    {

    }

    public  function setVisibility($path, $visibility)
    {

    }

}