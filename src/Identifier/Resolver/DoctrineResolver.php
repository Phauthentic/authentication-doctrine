<?php
namespace Phauthentic\Authentication\Identifier\Resolver;

use ArrayAccess;
use Authentication\Identifier\Resolver\ResolverInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use UnexpectedValueException;

class DoctrineResolver implements ResolverInterface
{
    /**
     * @var ObjectRepository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $conditions = [];

    /**
     * Constructor.
     *
     * @param ObjectRepository $repository Repository.
     * @param array $conditions Extra conditions.
     */
    public function __construct(ObjectRepository $repository, array $conditions = [])
    {
        $this->repository = $repository;
        $this->conditions = $conditions;
    }

    /**
     * {@inheritDoc}
     */
    public function find(array $conditions)
    {
        $entity = $this->repository->findOneBy($conditions + $this->conditions);

        if ($entity == null) {
            return $entity;
        }

        if (!$entity instanceof ArrayAccess) {
            $class = get_class($entity);
            throw new UnexpectedValueException("Entity `$class` must implement `ArrayAccess` interface.");
        }

        return $entity;
    }
}
