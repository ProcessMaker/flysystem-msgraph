<?php
namespace ProcessMaker\Flysystem\Adapter;

use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use ProcessMaker\Flysystem\Adapter\MSGraph\AuthException;
use ProcessMaker\Flysystem\Adapter\MSGraph\ModeException;
use ProcessMaker\Flysystem\Adapter\MSGraph\SiteInvalidException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class MSGraph extends AbstractAdapter 
{
    const MODE_SHAREPOINT = 'sharepoint';
    const MODE_ONEDRIVE = 'onedrive';


    // Our Microsoft Graph Client
    private $graph;
    // Our Microsoft Graph Access Token
    private $token;

    public function __construct($appId, $appPassword, $tokenEndpoint, $mode = self::MODE_ONEDRIVE, $targetId)
    {
        if($mode != self::MODE_ONEDRIVE && $mode != self::MODE_SHAREPOINT) {
            throw new ModeException("Unknown mode specified: " . $mode);
        }


        // Initialize the OAuth client
        $oauthClient = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId' => $appId,
            'clientSecret' => $appPassword,
            'urlAuthorize' => '',
            'urlResourceOwnerDetails' => '',
            'urlAccessToken' => $tokenEndpoint,
        ]);

        try {
            $this->token = $oauthClient->getAccessToken('client_credentials', [
                'scope' => 'https://graph.microsoft.com/.default'
            ]);
        } catch(IdentityProviderException $e) {
            throw new AuthException($e->getMessage());
        }

        // Assign graph instance
        $this->graph = new Graph();
        $this->graph->setAccessToken($this->token->getToken());

        // Check for existence
        if($mode == self::MODE_SHAREPOINT) {
            try {
            $drive = $this->graph->createRequest('GET', '/sites/' . $targetId . '/drive')
                ->setReturnType(Model\Drive::class)
                ->execute();
            } catch(\Exception $e) {
                if($e->getCode() == 400) {
                    throw new SiteInvalidException("The sharepoint site " . $targetId . " is invalid.");
                }
                throw $e;
            }
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