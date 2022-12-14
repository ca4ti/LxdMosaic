<?php

namespace dhope0000\LXDClient\Controllers\User\Dashboard;

use dhope0000\LXDClient\Model\Users\Dashboard\InsertUserDashboard;

class CreateDashboardController
{
    private $insertUserDashboard;
    
    public function __construct(InsertUserDashboard $insertUserDashboard)
    {
        $this->insertUserDashboard = $insertUserDashboard;
    }

    public function create(int $userId, string $name)
    {
        $this->insertUserDashboard->insert($userId, $name);
        return ["state"=>"success", "message"=>"Created dashbaord"];
    }
}
