<?php

namespace app\modules\user\events;

use app\modules\user\models\User;
use yii\base\Event;

class UserRegistrationEvent extends Event
{
    /**
     * @var User $user
     */
    protected $user;

    /**
     * @param User $user
     */
    public function __construct(User $user) {
        parent::__construct();
        $this->user = $user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
