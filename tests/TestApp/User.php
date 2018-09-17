<?php
namespace Phauthentic\Authentication\Test\TestApp;

use ArrayAccess;

/**
 * @Entity
 * @Table(name="users")
 **/
class User implements ArrayAccess
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;

    /** @Column(type="string") **/
    private $username;

    public function offsetExists($offset): bool
    {
        return isset($this->$offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value): void
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset): void
    {
        $this->$offset = null;
    }
}
