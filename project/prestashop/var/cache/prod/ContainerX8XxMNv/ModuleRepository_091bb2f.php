<?php

class ModuleRepository_091bb2f extends \PrestaShop\PrestaShop\Core\Module\ModuleRepository implements \ProxyManager\Proxy\VirtualProxyInterface
{
    private $valueHolder7a331 = null;
    private $initializer5d609 = null;
    private static $publicProperties20bdd = [
        
    ];
    public function getList() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getList', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getList();
    }
    public function getInstalledModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getInstalledModules', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getInstalledModules();
    }
    public function getMustBeConfiguredModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getMustBeConfiguredModules', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getMustBeConfiguredModules();
    }
    public function getUpgradableModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getUpgradableModules', array(), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getUpgradableModules();
    }
    public function getModule(string $moduleName) : \PrestaShop\PrestaShop\Core\Module\ModuleInterface
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getModule', array('moduleName' => $moduleName), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getModule($moduleName);
    }
    public function getModulePath(string $moduleName) : ?string
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'getModulePath', array('moduleName' => $moduleName), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->getModulePath($moduleName);
    }
    public function setActionUrls(\PrestaShop\PrestaShop\Core\Module\ModuleCollection $collection) : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'setActionUrls', array('collection' => $collection), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->setActionUrls($collection);
    }
    public function clearCache(?string $moduleName = null, bool $allShops = false) : bool
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, 'clearCache', array('moduleName' => $moduleName, 'allShops' => $allShops), $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        return $this->valueHolder7a331->clearCache($moduleName, $allShops);
    }
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;
        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $instance, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($instance);
        $instance->initializer5d609 = $initializer;
        return $instance;
    }
    public function __construct(\PrestaShop\PrestaShop\Adapter\Module\ModuleDataProvider $moduleDataProvider, \PrestaShop\PrestaShop\Adapter\Module\AdminModuleDataProvider $adminModuleDataProvider, \Doctrine\Common\Cache\CacheProvider $cacheProvider, \PrestaShop\PrestaShop\Adapter\HookManager $hookManager, string $modulePath, int $contextLangId)
    {
        static $reflection;
        if (! $this->valueHolder7a331) {
            $reflection = $reflection ?? new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
            $this->valueHolder7a331 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $this, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($this);
        }
        $this->valueHolder7a331->__construct($moduleDataProvider, $adminModuleDataProvider, $cacheProvider, $hookManager, $modulePath, $contextLangId);
    }
    public function & __get($name)
    {
        $this->initializer5d609 && ($this->initializer5d609->__invoke($valueHolder7a331, $this, '__get', ['name' => $name], $this->initializer5d609) || 1) && $this->valueHolder7a331 = $valueHolder7a331;
        if (isset(self::$publicProperties20bdd[$name])) {
            return $this->valueHolder7a331->$name;
        }
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $this, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($this);
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
