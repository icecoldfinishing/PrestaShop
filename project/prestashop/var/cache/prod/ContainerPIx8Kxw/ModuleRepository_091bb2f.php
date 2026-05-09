<?php

class ModuleRepository_091bb2f extends \PrestaShop\PrestaShop\Core\Module\ModuleRepository implements \ProxyManager\Proxy\VirtualProxyInterface
{
    private $valueHolder18863 = null;
    private $initializer802c5 = null;
    private static $publicPropertiesa6fc4 = [
        
    ];
    public function getList() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getList', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getList();
    }
    public function getInstalledModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getInstalledModules', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getInstalledModules();
    }
    public function getMustBeConfiguredModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getMustBeConfiguredModules', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getMustBeConfiguredModules();
    }
    public function getUpgradableModules() : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getUpgradableModules', array(), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getUpgradableModules();
    }
    public function getModule(string $moduleName) : \PrestaShop\PrestaShop\Core\Module\ModuleInterface
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getModule', array('moduleName' => $moduleName), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getModule($moduleName);
    }
    public function getModulePath(string $moduleName) : ?string
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'getModulePath', array('moduleName' => $moduleName), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->getModulePath($moduleName);
    }
    public function setActionUrls(\PrestaShop\PrestaShop\Core\Module\ModuleCollection $collection) : \PrestaShop\PrestaShop\Core\Module\ModuleCollection
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'setActionUrls', array('collection' => $collection), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->setActionUrls($collection);
    }
    public function clearCache(?string $moduleName = null, bool $allShops = false) : bool
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, 'clearCache', array('moduleName' => $moduleName, 'allShops' => $allShops), $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        return $this->valueHolder18863->clearCache($moduleName, $allShops);
    }
    public static function staticProxyConstructor($initializer)
    {
        static $reflection;
        $reflection = $reflection ?? new \ReflectionClass(__CLASS__);
        $instance   = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $instance, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($instance);
        $instance->initializer802c5 = $initializer;
        return $instance;
    }
    public function __construct(\PrestaShop\PrestaShop\Adapter\Module\ModuleDataProvider $moduleDataProvider, \PrestaShop\PrestaShop\Adapter\Module\AdminModuleDataProvider $adminModuleDataProvider, \Doctrine\Common\Cache\CacheProvider $cacheProvider, \PrestaShop\PrestaShop\Adapter\HookManager $hookManager, string $modulePath, int $contextLangId)
    {
        static $reflection;
        if (! $this->valueHolder18863) {
            $reflection = $reflection ?? new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
            $this->valueHolder18863 = $reflection->newInstanceWithoutConstructor();
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $this, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($this);
        }
        $this->valueHolder18863->__construct($moduleDataProvider, $adminModuleDataProvider, $cacheProvider, $hookManager, $modulePath, $contextLangId);
    }
    public function & __get($name)
    {
        $this->initializer802c5 && ($this->initializer802c5->__invoke($valueHolder18863, $this, '__get', ['name' => $name], $this->initializer802c5) || 1) && $this->valueHolder18863 = $valueHolder18863;
        if (isset(self::$publicPropertiesa6fc4[$name])) {
            return $this->valueHolder18863->$name;
        }
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        $realInstanceReflection = new \ReflectionClass('PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository');
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
        \Closure::bind(function (\PrestaShop\PrestaShop\Core\Module\ModuleRepository $instance) {
            unset($instance->moduleDataProvider, $instance->adminModuleDataProvider, $instance->hookManager, $instance->cacheProvider, $instance->modulePath, $instance->installedModules, $instance->modulesFromHook, $instance->contextLangId);
        }, $this, 'PrestaShop\\PrestaShop\\Core\\Module\\ModuleRepository')->__invoke($this);
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
