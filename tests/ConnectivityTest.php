<?php
namespace Kimmelsg\Flysystem\Adapter\MSGraph\Test;

use Kimmelsg\Flysystem\Adapter\MSGraph\AuthException;
use Kimmelsg\Flysystem\Adapter\MSGraph\SiteInvalidException;
use Kimmelsg\Flysystem\Adapter\MSGraph\ModeException;

use Kimmelsg\Flysystem\Adapter\MSGraph as Adapter;

class ConnectivityTest extends TestBase
{
    /**
     * Tests if an exception is properly thrown when unable to connect to  
     * Microsoft Graph service due to invalid credentials.
     * 
     * @test
     */
    public function testAuthFailure()
    {
        $this->expectException(AuthException::class);
        $adapter = new Adapter("invalid", "invalid", OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT, Adapter::MODE_SHAREPOINT, "invalid");
    }

    /**
     * Tests if an exception is properly thrown when a sharepoint site specified is invalid.
     * 
     * @test
     */
    public function testInvalidSiteSpecified()
    {
        $this->expectException(SiteInvalidException::class);
        $adapter = new Adapter(APP_ID, APP_PASSWORD, OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT, Adapter::MODE_SHAREPOINT, "invalid");
    }

    /**
     * Tests to ensure that the adapter is successfully created which is a result of 
     * valid authentication with access token retrieved.
     * 
     * @test
     */
    public function testAuthSuccess()
    {
        $adapter = new Adapter(APP_ID, APP_PASSWORD, OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT, Adapter::MODE_SHAREPOINT, SHAREPOINT_SITE_ID);
        $this->assertNotNull($adapter);
    }
}
