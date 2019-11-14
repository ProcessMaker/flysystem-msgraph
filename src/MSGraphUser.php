<?php

namespace ProcessMaker\Flysystem\Adapter;

class MSGraphUser extends MSGraph
{

    public function __construct($appId, $appPassword, $tokenEndpoint, $mode = self::MODE_ONEDRIVE, $targetId, $driveName = null)
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
        $graph = new Graph();
        $graph->setAccessToken($this->token->getToken());

        $this->initialize($graph, $mode, $targetId, $driveName);
    }
}
