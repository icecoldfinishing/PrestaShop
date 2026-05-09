<?php

class EntityManager_9a5be93 extends \Doctrine\ORM\EntityManager implements \ProxyManager\Proxy\VirtualProxyInterface
{
    private $valueHolder7a331 = null;
    private $initializer5d609 = null;
    private static $publicProperties20bdd = [
        
    ];
    public function getConnection()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getConnection', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getConnection();
    }
    public function getMetadataFactory()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getMetadataFactory', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getMetadataFactory();
    }
    public function getExpressionBuilder()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getExpressionBuilder', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getExpressionBuilder();
    }
    public function beginTransaction()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'beginTransaction', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->beginTransaction();
    }
    public function getCache()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getCache', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getCache();
    }
    public function transactional($func)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'transactional', array('func' => $func), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->transactional($func);
    }
    public function wrapInTransaction(callable $func)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'wrapInTransaction', array('func' => $func), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->wrapInTransaction($func);
    }
    public function commit()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'commit', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->commit();
    }
    public function rollback()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'rollback', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->rollback();
    }
    public function getClassMetadata($className)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getClassMetadata', array('className' => $className), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getClassMetadata($className);
    }
    public function createQuery($dql = '')
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'createQuery', array('dql' => $dql), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->createQuery($dql);
    }
    public function createNamedQuery($name)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'createNamedQuery', array('name' => $name), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->createNamedQuery($name);
    }
    public function createNativeQuery($sql, \Doctrine\ORM\Query\ResultSetMapping $rsm)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'createNativeQuery', array('sql' => $sql, 'rsm' => $rsm), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->createNativeQuery($sql, $rsm);
    }
    public function createNamedNativeQuery($name)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'createNamedNativeQuery', array('name' => $name), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->createNamedNativeQuery($name);
    }
    public function createQueryBuilder()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'createQueryBuilder', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->createQueryBuilder();
    }
    public function flush($entity = null)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'flush', array('entity' => $entity), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->flush($entity);
    }
    public function find($className, $id, $lockMode = null, $lockVersion = null)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'find', array('className' => $className, 'id' => $id, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->find($className, $id, $lockMode, $lockVersion);
    }
    public function getReference($entityName, $id)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getReference', array('entityName' => $entityName, 'id' => $id), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getReference($entityName, $id);
    }
    public function getPartialReference($entityName, $identifier)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getPartialReference', array('entityName' => $entityName, 'identifier' => $identifier), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getPartialReference($entityName, $identifier);
    }
    public function clear($entityName = null)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'clear', array('entityName' => $entityName), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->clear($entityName);
    }
    public function close()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'close', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->close();
    }
    public function persist($entity)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'persist', array('entity' => $entity), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->persist($entity);
    }
    public function remove($entity)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'remove', array('entity' => $entity), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->remove($entity);
    }
    public function refresh($entity)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'refresh', array('entity' => $entity), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->refresh($entity);
    }
    public function detach($entity)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'detach', array('entity' => $entity), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->detach($entity);
    }
    public function merge($entity)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'merge', array('entity' => $entity), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->merge($entity);
    }
    public function copy($entity, $deep = false)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'copy', array('entity' => $entity, 'deep' => $deep), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->copy($entity, $deep);
    }
    public function lock($entity, $lockMode, $lockVersion = null)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'lock', array('entity' => $entity, 'lockMode' => $lockMode, 'lockVersion' => $lockVersion), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->lock($entity, $lockMode, $lockVersion);
    }
    public function getRepository($entityName)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getRepository', array('entityName' => $entityName), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getRepository($entityName);
    }
    public function contains($entity)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'contains', array('entity' => $entity), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->contains($entity);
    }
    public function getEventManager()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getEventManager', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getEventManager();
    }
    public function getConfiguration()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getConfiguration', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getConfiguration();
    }
    public function isOpen()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'isOpen', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->isOpen();
    }
    public function getUnitOfWork()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getUnitOfWork', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getUnitOfWork();
    }
    public function getHydrator($hydrationMode)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getHydrator', array('hydrationMode' => $hydrationMode), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getHydrator($hydrationMode);
    }
    public function newHydrator($hydrationMode)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'newHydrator', array('hydrationMode' => $hydrationMode), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->newHydrator($hydrationMode);
    }
    public function getProxyFactory()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getProxyFactory', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getProxyFactory();
    }
    public function initializeObject($obj)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'initializeObject', array('obj' => $obj), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->initializeObject($obj);
    }
    public function getFilters()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getFilters', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getFilters();
    }
    public function isFiltersStateClean()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'isFiltersStateClean', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->isFiltersStateClean();
    }
    public function hasFilters()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'hasFilters', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->hasFilters();
    }
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;
        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $instance, 'Doctrine\\ORM\\EntityManager')->__invoke($instance);
        $instance->initializer5d609 = $initializer;
        return $instance;
    }
    protected function __construct(\Doctrine\DBAL\Connection $conn, \Doctrine\ORM\Configuration $config, \Doctrine\Common\EventManager $eventManager)
    {
        static $reflection;
        if (! $this->valueHolder7a331) {
            $reflection = $reflection ?? new \ReflectionClass('Doctrine\\ORM\\EntityManager');
            $this->valueHolder7a331 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
        }
        $this->valueHolder7a331->__construct($conn, $config, $eventManager);
    }
    public function & __get($name)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, '__get', ['name' => $name], $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        if (isset(self::$publicProperties20bdd[$name])) {
            return $this->valueHolder7a331->$name;
        }
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder7a331;
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
        $targetObject = $this->valueHolder7a331;
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
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, '__set', array('name' => $name, 'value' => $value), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder7a331;
            $targetObject->$name = $value;
            return $targetObject->$name;
        }
        $targetObject = $this->valueHolder7a331;
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
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, '__isset', array('name' => $name), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder7a331;
            return isset($targetObject->$name);
        }
        $targetObject = $this->valueHolder7a331;
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
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, '__unset', array('name' => $name), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        $realInstanceReflection = new \ReflectionClass('Doctrine\\ORM\\EntityManager');
        if (! $realInstanceReflection->hasProperty($name)) {
            $targetObject = $this->valueHolder7a331;
            unset($targetObject->$name);
            return;
        }
        $targetObject = $this->valueHolder7a331;
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
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, '__clone', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        $this->valueHolder7a331 = clone $this->valueHolder7a331;
    }
    public function __sleep()
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, '__sleep', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return array('valueHolder7a331');
    }
    public function __wakeup()
    {
        \Closure::bind(function (\Doctrine\ORM\EntityManager $instance) {
            unset($instance->config, $instance->conn, $instance->metadataFactory, $instance->unitOfWork, $instance->eventManager, $instance->proxyFactory, $instance->repositoryFactory, $instance->expressionBuilder, $instance->closed, $instance->filterCollection, $instance->cache);
        }, $this, 'Doctrine\\ORM\\EntityManager')->__invoke($this);
    }
    public function setProxyInitializer(\Closure $initializer = null) : void
    {
        $this->initializer5d609 = $initializer;
    }
    public function getProxyInitializer() : ?\Closure
    {
        return $this->initializer5d609;
    }
    public function initializeProxy() : bool
    {
        return $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'initializeProxy', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
    }
    public function isProxyInitialized() : bool
    {
        return null !== $this->valueHolder7a331;
    }
    public function getWrappedValueHolderValue()
    {
        return $this->valueHolder7a331;
    }
}
