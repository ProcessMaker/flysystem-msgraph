<?php

namespace ProcessMaker\Flysystem\Adapter;

use Microsoft\Graph\Graph;

class MSGraphApp extends MSGraph
{
    public function __construct($mode = self::MODE_ONEDRIVE, $targetId, $driveName = null, $appModeToken = null)
    {
        parent::__construct($mode);

        // Assign graph instance
        $graph = new Graph();
        $graph->setAccessToken($appModeToken);

        $this->initialize($graph, $mode, $targetId, $driveName);
    }
}
