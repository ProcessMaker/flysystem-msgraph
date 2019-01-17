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

    private $filesToPurge = [];

    public function setUp()
    {
        $adapter = new Adapter(APP_ID, APP_PASSWORD, OAUTH_AUTHORITY . OAUTH_TOKEN_ENDPOINT, Adapter::MODE_SHAREPOINT, SHAREPOINT_SITE_ID);

        $this->fs = new Filesystem($adapter);
    }

    public function testWrite()
    {
        $this->assertEquals(true, $this->fs->write(TEST_FILE_PREFIX . 'testWrite.txt', 'testing'));
        $this->filesToPurge[] = TEST_FILE_PREFIX . 'testWrite.txt';
    }

    public function testDelete()
    {
         // Create file
        $this->fs->write(TEST_FILE_PREFIX . 'testDelete.txt', 'testing');
        // Ensure it exists
        $this->assertEquals(true, $this->fs->has(TEST_FILE_PREFIX . 'testDelete.txt'));
        // Now delete
        $this->assertEquals(true, $this->fs->delete(TEST_FILE_PREFIX . 'testDelete.txt'));
        // Ensure it no longer exists
        $this->assertEquals(false, $this->fs->has(TEST_FILE_PREFIX . 'testDelete.txt'));
    }

    public function testHas()
    {
        // Test that file does not exist
        $this->assertEquals(false, $this->fs->has(TEST_FILE_PREFIX . 'testHas.txt'));

        // Create file
        $this->fs->write(TEST_FILE_PREFIX . 'testHas.txt', 'testing');
        $this->filesToPurge[] = TEST_FILE_PREFIX . 'testHas.txt';

        // Test that file exists
        $this->assertEquals(true, $this->fs->has(TEST_FILE_PREFIX . 'testHas.txt'));
    }

    public function testRead()
    {
        // Not completed yet
        $this->markTestIncomplete('Read not implemented yet.');

        // Create file
        $this->fs->write(TEST_FILE_PREFIX . 'testRead.txt', 'testing read functionality');
        $this->filesToPurge[] = TEST_FILE_PREFIX . 'testRead.txt';

        // Call read
        $this->assertEquals("testing read functionality", $this->fs->read(TEST_FILE_PREFIX . 'testRead.txt'));


    }

    /**
     * Tears down the test suite by attempting to delete all files written, clearing things up
     * 
     * @todo Implement functionality
     */
    public function tearDown()
    {
        foreach($this->filesToPurge as $path) {
            try {
                $this->fs->delete($path);
            } catch(\Exception $e) {
                // Do nothing, just continue. We obviously can't clean it
            }
        }
        $this->filesToPurge = [];
    }

}