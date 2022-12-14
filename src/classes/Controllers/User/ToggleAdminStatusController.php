<?php

namespace dhope0000\LXDClient\Controllers\User;

use dhope0000\LXDClient\Tools\User\ToggleAdminStatus;
use dhope0000\LXDClient\Tools\User\ValidatePermissions;

class ToggleAdminStatusController
{
    private $toggleAdminStatus;
    private $validatePermissions;
    
    public function __construct(
        ToggleAdminStatus $toggleAdminStatus,
        ValidatePermissions $validatePermissions
    ) {
        $this->toggleAdminStatus = $toggleAdminStatus;
        $this->validatePermissions = $validatePermissions;
    }

    public function toggle(int $userId, int $targetUser, int $status)
    {
        $this->validatePermissions->isAdminOrThrow($userId);
        $this->toggleAdminStatus->toggle($targetUser, $status);
        return ["state"=>"success", "message"=>"Changed status"];
    }
}
