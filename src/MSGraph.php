<?php

namespace ProcessMaker\Flysystem\Adapter;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Stream;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Config;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use ProcessMaker\Flysystem\Adapter\MSGraph\ModeException;
use ProcessMaker\Flysystem\Adapter\MSGraph\SiteInvalidException;

class MSGraph extends AbstractAdapter
{
    const MODE_SHAREPOINT = 'sharepoint';

    const MODE_ONEDRIVE = 'onedrive';

    // Our mode, if sharepoint or onedrive
    private $mode;

    // Our Microsoft Graph Client
    private $graph;

    // Our Microsoft Graph Access Token
    private $token;

    // Our targetId, sharepoint site if sharepoint, drive id if onedrive
    private $targetId;

    // Our driveId, which if non empty points to a Drive
    private $driveId;

    // Our url prefix to be used for most file operations. This gets created in our constructor
    private $prefix;

    public function __construct($mode)
    {
        if ($mode != self::MODE_ONEDRIVE && $mode != self::MODE_SHAREPOINT) {
            throw new ModeException("Unknown mode specified: " . $mode);
        }
    }

    public function initialize($graph, $mode = self::MODE_ONEDRIVE, $targetId, $driveName)
    {
        $this->mode = $mode;
        $this->graph = $graph;

        // Check for existence
        if ($mode == self::MODE_SHAREPOINT) {
            try {
                $site = $this->graph->createRequest('GET', '/sites/' . $targetId)
                    ->setReturnType(Model\Site::class)
                    ->execute();
                // Assign the site id triplet to our targetId
                $this->targetId = $site->getId();
            } catch (\Exception $e) {
                if ($e->getCode() == 400) {
                    throw new SiteInvalidException("The sharepoint site " . $targetId . " is invalid.");
                }

                throw $e;
            }
            $this->prefix = "/sites/" . $this->targetId . '/drive/items/';
            if ($driveName != '') {
                // Then we specified a drive name, so let's enumerate the drives and find it
                $drives = $this->graph->createRequest('GET', '/sites/' . $this->targetId . '/drives')
                    ->execute();
                $drives = $drives->getBody()['value'];
                foreach ($drives as $drive) {
                    if ($drive['name'] == $driveName) {
                        $this->driveId = $drive['id'];
                        $this->prefix = "/drives/" . $this->driveId . "/items/";

                        break;
                    }
                }
                if (! $this->driveId) {
                    throw new SiteInvalidException("The sharepoint drive with name " . $driveName . " could not be found.");
                }
            }
        }
    }

    public function has($path)
    {
        if ($this->mode == self::MODE_SHAREPOINT) {
            try {
                $driveItem = $this->graph->createRequest('GET', $this->prefix . 'root:/' . $path)
                    ->setReturnType(Model\DriveItem::class)
                    ->execute();
                // Successfully retrieved meta data.
                return true;
            } catch (ClientException $e) {
                if ($e->getCode() == 404) {
                    // Not found, let's return false;
                    return false;
                }

                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        return false;
    }

    public function read($path)
    {
        if ($this->mode == self::MODE_SHAREPOINT) {
            try {
                $driveItem = $this->graph->createRequest('GET', $this->prefix . 'root:/' . $path)
                    ->setReturnType(Model\DriveItem::class)
                    ->execute();
                // Successfully retrieved meta data.
                // Now get content
                $contentStream = $this->graph->createRequest('GET', $this->prefix . $driveItem->getId() . '/content')
                    ->setReturnType(Stream::class)
                    ->execute();
                $contents = '';
                $bufferSize = 8012;
                // Copy over the data into a string
                while (! $contentStream->eof()) {
                    $contents .= $contentStream->read($bufferSize);
                }

                return ['contents' => $contents];
            } catch (ClientException $e) {
                if ($e->getCode() == 404) {
                    // Not found, let's return false;
                    return false;
                }

                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        return false;
    }

    public function getUrl($path)
    {
        if ($this->mode == self::MODE_SHAREPOINT) {
            try {
                $driveItem = $this->graph->createRequest('GET', $this->prefix . 'root:/' . $path)
                    ->setReturnType(Model\DriveItem::class)
                    ->execute();
                // Successfully retrieved meta data.
                // Return url property
                return $driveItem->getWebUrl();
            } catch (ClientException $e) {
                if ($e->getCode() == 404) {
                    // Not found, let's return false;
                    return false;
                }

                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        return false;
    }

    public function readStream($path)
    {
    }

    public function listContents($directory = '', $recursive = false)
    {
        if ($this->mode == self::MODE_SHAREPOINT) {
            try {
                $drive = $this->graph->createRequest('GET', $this->prefix . 'root:/' . $directory)
                    ->setReturnType(Model\Drive::class)
                    ->execute();
                // Successfully retrieved meta data.
                // Now get content
                $driveItems = $this->graph->createRequest('GET', $this->prefix . $drive->getId() . '/children')
                    ->setReturnType(Model\DriveItem::class)
                    ->execute();

                $children = [];
                foreach ($driveItems as $driveItem) {
                    $item = $driveItem->getProperties();
                    $item['path'] = $directory . '/' . $driveItem->getName();
                    $children[] = $item;
                }

                return $children;
            } catch (ClientException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        return [];
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
        if ($this->mode == self::MODE_SHAREPOINT) {
            // Attempt to write to sharepoint
            $driveItem = $this->graph->createRequest('PUT', $this->prefix . 'root:/' . $path . ':/content')
                ->attachBody($contents)
                ->setReturnType(Model\DriveItem::class)
                ->execute();

            // Successfully created
            return true;
        }

        return false;
    }

    public function writeStream($path, $resource, Config $config)
    {
    }

    public function update($path, $contents, Config $config)
    {
        return $this->write($path, $contents, $config);
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
        if ($this->mode == self::MODE_SHAREPOINT) {
            try {
                $driveItem = $this->graph->createRequest('GET', $this->prefix . 'root:/' . $path)
                    ->setReturnType(Model\DriveItem::class)
                    ->execute();
                // Successfully retrieved meta data.
                // Now delete the file
                $this->graph->createRequest('DELETE', $this->prefix . $driveItem->getId())
                    ->execute();

                return true;
            } catch (ClientException $e) {
                if ($e->getCode() == 404) {
                    // Not found, let's return false;
                    return false;
                }

                throw $e;
            } catch (Exception $e) {
                throw $e;
            }
        }

        return false;
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
