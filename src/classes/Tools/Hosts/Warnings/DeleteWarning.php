<?php

namespace dhope0000\LXDClient\Tools\Hosts\Warnings;

use dhope0000\LXDClient\Tools\User\ValidatePermissions;
use dhope0000\LXDClient\Objects\Host;

class DeleteWarning
{
    private $validatePermissions;
    
    public function __construct(ValidatePermissions $validatePermissions)
    {
        $this->validatePermissions = $validatePermissions;
    }

    public function delete(int $userId, Host $host, string $id)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $host->warnings->remove($id);
    }
}
