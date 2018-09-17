<?php
namespace Phauthentic\Authentication\Test\TestApp;

/**
 * @Entity
 * @Table(name="users")
 **/
class Invalid
{
    /** @Id @Column(type="integer") @GeneratedValue **/
    private $id;

    /** @Column(type="string") **/
    private $username;
}
