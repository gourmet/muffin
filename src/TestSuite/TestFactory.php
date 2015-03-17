<?php
namespace Gourmet\Muffin\TestSuite;

use Cake\Core\Configure;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

class TestFactory
{
    public function __invoke($callback, $count = null)
    {
        if (!$model = $this->getModelClass()) {
            throw new Exception();
        }

        if (!is_null($callback)) {
            if (is_int($callback)) {
                $count = $callback;
                $callback = null;
            } else if (!is_callable($callback)) {
                throw new InvalidArgumentException();
            }
        }

        $attrs = get_object_vars($this);
        $factory = $this->getFactoryClass();
        $args = [$model, $attrs];
        if (!is_null($callback)) {
            $args[] = $callback;
        }

        $this->setCustomMaker();
        $this->setCustomSaver();

        call_user_func_array([$factory, 'define'], $args);

        if (!$count) {
            return;
        }

        $method = 'create';
        array_pop($args);
        if ($count > 1) {
            $method = 'seed';
            array_unshift($args, $count);
        }
        return call_user_func_array([$factory, $method], $args);
    }

    public function getFactoryClass()
    {
        return 'League\FactoryMuffin\Facade';
    }

    public function setCustomMaker()
    {
        $factory = $this->getFactoryClass();
        $factory::setCustomMaker(function ($class) {
            $parts = explode('\\', $class);
            $object = new $class();
            $object->source(Inflector::pluralize(array_pop($parts)));
            return $object;
        });
    }

    public function setCustomSaver()
    {
        $factory = $this->getFactoryClass();
        $factory::setCustomSaver(function ($object) {
            $table = $this->getTable($object->source());
            return $table->save($object, ['atomic' => false]);
        });
    }

    public function getTable($name)
    {
        $table = TableRegistry::get($name);
        if ($this->useCallbacks()) {
            return $table;
        }

        return new Table([
            'table' => $table->table(),
            'connection' => $table->connection(),
            'schema' => $table->schema(),
            'entityClass' => $table->entityClass(),
        ]);
    }

    public function getModelClass()
    {
        $parts = explode('\\', get_class($this));
        $factory = array_pop($parts);
        $entity = preg_replace('/Factory$/', '', $factory);
        return Configure::read('App.namespace') . '\Model\Entity\\' . $entity;
    }

    public function useCallbacks()
    {
        return true;
    }
}
