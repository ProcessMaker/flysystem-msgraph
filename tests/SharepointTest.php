<?php
namespace ProcessMaker\Flysystem\Adapter\MSGraph\Test;

use League\Flysystem\Filesystem;
use ProcessMaker\Flysystem\Adapter\MSGraph\AuthException;
use ProcessMaker\Flysystem\Adapter\MSGraph\SiteInvalidException;
use ProcessMaker\Flysystem\Adapter\MSGraph\ModeException;

use ProcessMaker\Flysystem\Adapter\MSGraph as Adapter;

class SharepointTest extends TestBase
{
    private $fs;

    public function setUp()
    {
        $adapter = new Adapter(APP_ID, APP_PASSWORD, OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT, Adapter::MODE_SHAREPOINT, SHAREPOINT_SITE_ID);

        $this->fs = new Filesystem($adapter);
    }

    public function testWrite()
    {
        $this->assertEquals(true, $this->fs->write(TEST_FILE_PREFIX . 'testWrite.txt', 'testing'));
    }

    public function testHas()
    {
        // Test that file does not exist
        $this->assertEquals(false, $this->fs->has(TEST_FILE_PREFIX . 'testHas.txt'));

        // Create file
        $this->fs->write(TEST_FILE_PREFIX . 'testHas.txt', 'testing');

        // Test that file exists
        $this->assertEquals(true, $this->fs->has(TEST_FILE_PREFIX . 'testHas.txt'));
    }

    /**
     * Tears down the test suite by attempting to delete all files written, clearing things up
     * 
     * @todo Implement functionality
     */
    public function tearDown()
    {

    }


}