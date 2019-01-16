<?php
namespace ProcessMaker\Flysystem\Adapter\MSGraph\Test;

use ProcessMaker\Flysystem\Adapter\MSGraph\AuthException;
use ProcessMaker\Flysystem\Adapter\MSGraph as Adapter;

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
        $this->markTestSkipped();
        $this->expectException(AuthException::class);
        $adapter = new Adapter("invalid", "invalid", OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT);
    }

    /**
     * Tests to ensure that the adapter is successfully created which is a result of 
     * valid authentication with access token retrieved.
     */
    public function testAuthSuccess()
    {
        $this->doesNotPerformAssertions();
        $adapter = new Adapter(APP_ID, APP_PASSWORD, OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT);
    }
}
