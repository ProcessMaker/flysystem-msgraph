<?php

namespace Kimmelsg\Flysystem\Adapter;

use Microsoft\Graph\Graph;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class MSGraphUser extends MSGraph 
{

    public function __construct($appId, $appPassword, $tokenEndpoint, $mode = self::MODE_ONEDRIVE, $targetId, $driveName = null)
    {
        parent::__construct($mode);

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
        $graph = new Graph();
        $graph->setAccessToken($this->token->getToken());

        $this->initialize($graph, $mode, $targetId, $driveName);
    }
}
