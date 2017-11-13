<?php

namespace Hatest\Symfony\TestCase;

use Doctrine\ORM\EntityManager;
use Hatest\Interfaces\EntityInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


abstract class AbstractRepositoryTestCase extends KernelTestCase
{
    /** @var EntityManager */
    protected $em;

    /** @var array */
    protected $inMemory = [];

    /** {@inheritdoc} */
    protected function setUp()
    {
        self::bootKernel();
        $this->em = static::$kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @param EntityInterface $entity
     */
    protected function addEntity(EntityInterface $entity): void
    {
        $this->em->persist($entity);
        $this->em->flush($entity);
        $this->em->refresh($entity);

        $this->inMemory[] = $entity;
    }

    /**
     * @param $entity
     */
    protected function removeEntity(EntityInterface $entity): void
    {
        $this->inMemory[] = $entity;
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        foreach ($this->inMemory as $entity) {
            $this->em->remove($entity);
        }

        $this->em->flush();
        $this->em->close();
        $this->em = null;
    }
}
