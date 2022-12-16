<?php

class SomeObject
{
    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getHadlerName()
    {
        return 'handle_' . $this->name;
    }
}

class SomeObjectsHandler
{
    public function handleObjects(array $objects): array
    {
        $handlers = [];
        foreach ($objects as $object) {
            $handlers[] = $object->getHandlerName();
        }
        return $handlers;
    }
}

$objects = [
    new SomeObject('object_1'),
    new SomeObject('object_2')
];

$soh = new SomeObjectsHandler();
$soh->handleObjects($objects);
