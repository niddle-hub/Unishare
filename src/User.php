<?php

require '../lib/rb-postgres.php';
use RedBeanPHP\OODBBean;

class User
{
    private array|null $userData;
    private OODBBean $user;

    public function __construct(int $id)
    {
        $this->connect();

        $this->userData = R::getRow('SELECT * FROM users WHERE vkid = ? LIMIT 1', [$id]);
        $this->user = R::load('users', $this->userData['id']);
    }

    private function connect(): void
    {
        $host = getenv('db_host');
        $dbname = getenv('db_name');
        $user = getenv('db_user');
        $password = getenv('db_password');
        R::setup("pgsql:host=$host;dbname=$dbname", (string)$user, (string)$password);
    }

    public function addNew(int $vkid, string $group = 'исит-1701', bool $waitdate = false, bool $waitgroup = false, bool $notification = false): bool
    {
        $users = R::dispense('users');
        $users->vkid = $vkid;
        $users->group = $group;
        $users->waitdate = $waitdate;
        $users->waitgroup = $waitgroup;
        $users->notification = $notification;

        if (R::count('users', "vkid = $vkid") === 0) {
            R::store($users);
            return true;
        }
        return false;
    }

    public function load(): OODBBean
    {
        return $this->user;
    }

    public function getData(): array|null
    {
        return $this->userData;
    }

    public function update(OODBBean $user): int|null|string
    {
        return R::store($user);
    }
}
