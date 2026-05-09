<?php

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{
    private $valueHolder18863 = null;
    private $initializer802c5 = null;
    private static $publicPropertiesa6fc4 = [
        
    ];
    public function getConnection()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getConnection', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getConnection();
    }
    public function getMetadataFactory()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getMetadataFactory', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getMetadataFactory();
    }
    public function getExpressionBuilder()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getExpressionBuilder', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getExpressionBuilder();
    }
    public function beginTransaction()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'beginTransaction', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->beginTransaction();
    }
    public function getCache()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getCache', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getCache();
    }
    public function transactional($func)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'transactional', array('func' => $func), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->transactional($func);
    }
    public function wrapInTransaction(callable $func)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'wrapInTransaction', array('func' => $func), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->wrapInTransaction($func);
    }
    public function commit()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'commit', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->commit();
    }
    public function rollback()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'rollback', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->rollback();
    }
    public function getClassMetadata($className)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getClassMetadata', array('className' => $className), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getClassMetadata($className);
    }
    public function createQuery($dql = '')
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'createQuery', array('dql' => $dql), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->createQuery($dql);
    }
    public function createNamedQuery($name)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'createNamedQuery', array('name' => $name), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->createNamedQuery($name);
    }
    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->createNativeQuery($sql, $rsm);
    }
    public function createNamedNativeQuery($name)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->createNamedNativeQuery($name);
    }
    public function createQueryBuilder()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'createQueryBuilder', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->createQueryBuilder();
    }
    public function flush($entity = null)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'flush', array('entity' => $entity), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->flush($entity);
    }
    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->find($className, $id, $lockMode, $lockVersion);
    }
    public function getReference($entityName, $id)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getReference($entityName, $id);
    }
    public function getPartialReference($entityName, $identifier)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getPartialReference($entityName, $identifier);
    }
    public function clear($entityName = null)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'clear', array('entityName' => $entityName), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->clear($entityName);
    }
    public function close()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'close', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->close();
    }
    public function persist($entity)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'persist', array('entity' => $entity), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->persist($entity);
    }
    public function remove($entity)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'remove', array('entity' => $entity), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->remove($entity);
    }
    public function refresh($entity)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'refresh', array('entity' => $entity), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->refresh($entity);
    }
    public function detach($entity)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'detach', array('entity' => $entity), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->detach($entity);
    }
    public function merge($entity)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'merge', array('entity' => $entity), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->merge($entity);
    }
    public function copy($entity, $deep = false)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->copy($entity, $deep);
    }
    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->lock($entity, $lockMode, $lockVersion);
    }
    public function getRepository($entityName)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getRepository', array('entityName' => $entityName), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getRepository($entityName);
    }
    public function contains($entity)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'contains', array('entity' => $entity), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->contains($entity);
    }
    public function getEventManager()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getEventManager', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getEventManager();
    }
    public function getConfiguration()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getConfiguration', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getConfiguration();
    }
    public function isOpen()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'isOpen', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->isOpen();
    }
    public function getUnitOfWork()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getUnitOfWork', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getUnitOfWork();
    }
    public function getHydrator($hydrationMode)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getHydrator($hydrationMode);
    }
    public function newHydrator($hydrationMode)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->newHydrator($hydrationMode);
    }
    public function getProxyFactory()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getProxyFactory', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getProxyFactory();
    }
    public function initializeObject($obj)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'initializeObject', array('obj' => $obj), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->initializeObject($obj);
    }
    public function getFilters()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getFilters', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getFilters();
    }
    public function isFiltersStateClean()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'isFiltersStateClean', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->isFiltersStateClean();
    }
    public function hasFilters()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'hasFilters', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->hasFilters();
    }
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;
        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);
        $instance->initializer802c5 = $initializer;
        return $instance;
    }
    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;
        if (! $this->valueHolder18863) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHolder18863 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
        }
        $this->valueHolder18863->__construct($conn, $config, $eventManager);
    }
    public function & __get($name)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, '__get', ['name' => $name], $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        if (isset(self::$publicPropertiesa6fc4[$name])) {
            return $this->valueHolder18863->$name;
        }
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder18863;
            $backtrace = debug_backtrace(false, 1);
            trigger_error(
                sprintf(
                    'Undefined property: %s::$%s in %s on line %s',
                    $realInstanceReflection->getName(),
                    $name,
                    $backtrace[0]['file'],
                    $backtrace[0]['line']
                ),
                \E_USER_NOTICE
            );
            return $targetObject->$name;
        }
        $targetObject = $this->valueHolder18863;
        $accessor = function & () use ($targetObject, $name) {
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();
        return $returnValue;
    }
    public function __set($name, $value)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, '__set', array('name' => $name, 'value' => $value), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder18863;
            $targetObject->$name = $value;
            return $targetObject->$name;
        }
        $targetObject = $this->valueHolder18863;
        $accessor = function & () use ($targetObject, $name, $value) {
            $targetObject->$name = $value;
            return $targetObject->$name;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = & $accessor();
        return $returnValue;
    }
    public function __isset($name)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, '__isset', array('name' => $name), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder18863;
            return isset($targetObject->$name);
        }
        $targetObject = $this->valueHolder18863;
        $accessor = function () use ($targetObject, $name) {
            return isset($targetObject->$name);
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $returnValue = $accessor();
        return $returnValue;
    }
    public function __unset($name)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, '__unset', array('name' => $name), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder18863;
            unset($targetObject->$name);
            return;
        }
        $targetObject = $this->valueHolder18863;
        $accessor = function () use ($targetObject, $name) {
            unset($targetObject->$name);
            return;
        };
        $backtrace = debug_backtrace(true, 2);
        $scopeObject = isset($backtrace[1]['object']) ? $backtrace[1]['object'] : new \ProxyManager\Stub\EmptyClassStub();
        $accessor = $accessor->bindTo($scopeObject, get_class($scopeObject));
        $accessor();
    }
    public function __clone()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, '__clone', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        $this->valueHolder18863 = clone $this->valueHolder18863;
    }
    public function __sleep()
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, '__sleep', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return array('valueHolder18863');
    }
    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }
    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializer802c5 = $initializer;
    }
    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializer802c5;
    }
    public function initializeProxy() : bool
    {
        return $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'initializeProxy', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
    }
    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHolder18863;
    }
    public function getWrappedValueHolderValue()
    {
        return $this->valueHolder18863;
    }
}
