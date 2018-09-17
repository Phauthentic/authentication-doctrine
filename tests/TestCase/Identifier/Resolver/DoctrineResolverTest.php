<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Phauthentic\Authentication\Test\TestCase\Identifier\Resolver;

use Authentication\Test\TestCase\AuthenticationTestCase;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use Phauthentic\Authentication\Identifier\Resolver\DoctrineResolver;
use Phauthentic\Authentication\Test\TestApp\Invalid;
use Phauthentic\Authentication\Test\TestApp\User;
use UnexpectedValueException;
use const ROOT;

class DoctrineResolverTest extends AuthenticationTestCase
{
    protected $repository;
    protected $entityManager;

    public function setUp(): void
    {
        parent::setUp();

        $config = Setup::createAnnotationMetadataConfiguration(array(ROOT . 'TestApp'), true);
        $dbalConfig = new Configuration();
        $connectionParams = [
            'pdo' => $this->getConnection()->getConnection(),
        ];

        $conn = DriverManager::getConnection($connectionParams, $dbalConfig);

        $this->entityManager = EntityManager::create($conn, $config);
        $this->repository = $this->entityManager->getRepository(User::class);
    }

    public function testFind()
    {
        $resolver = new DoctrineResolver($this->repository);

        $user = $resolver->find([
            'username' => 'florian'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('florian', $user['username']);
    }

    public function testFindConditions()
    {
        $resolver = new DoctrineResolver($this->repository, [
            'id' => 1,
        ]);

        $user = $resolver->find([
            'username' => 'florian'
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('florian', $user['username']);
    }

    public function testFindConditionsMissing()
    {
        $resolver = new DoctrineResolver($this->repository, [
            'id' => 2,
        ]);

        $user = $resolver->find([
            'username' => 'florian'
        ]);

        $this->assertNull($user);
    }

    public function testFindMissing()
    {
        $resolver = new DoctrineResolver($this->repository);

        $user = $resolver->find([
            'id' => 1,
            'username' => 'robert'
        ]);

        $this->assertNull($user);
    }

    public function testFindMultipleValues()
    {
        $resolver = new DoctrineResolver($this->repository);

        $user = $resolver->find([
            'username' => [
                'robert',
                'florian'
            ]
        ]);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user['id']);
    }

    public function testFindInvalid()
    {
        $resolver = new DoctrineResolver($this->entityManager->getRepository(Invalid::class));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Entity `Phauthentic\Authentication\Test\TestApp\Invalid` must implement `ArrayAccess` interface.');

        $user = $resolver->find([
            'username' => 'robert'
        ]);
    }
}
