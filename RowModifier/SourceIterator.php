<?php
/**
 * Copyright (c) 2016 H&O E-commerce specialisten B.V. (http://www.h-o.nl/)
 * See LICENSE.txt for license details.
 */
namespace Ho\Import\RowModifier;

use Closure;
use Iterator;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class SourceIterator
 * @package Ho\Import\RowModifier
 */
class SourceIterator extends AbstractRowModifier
{

    /**
     * The row Identifier
     *
     * @var Closure
     */
    protected $identifier;

    /**
     * The fow Iterator
     *
     * @var Iterator
     */
    protected $iterator;

    /**
     * SourceIterator constructor.
     *
     * @param ConsoleOutput $consoleOutput
     * @param Closure       $identifier
     * @param Iterator      $iterator
     */
    public function __construct(
        ConsoleOutput $consoleOutput,
        Closure $identifier,
        Iterator $iterator
    ) {
        parent::__construct($consoleOutput);
        $this->identifier = $identifier;
        $this->iterator   = $iterator;
    }

    /**
     * Load the source into the array
     *
     * @return void
     */
    public function process()
    {
        $identifier = $this->identifier;
        foreach ($this->iterator as $item) {
            $id = $identifier($item);
            if (!isset($this->items[$id])) {
                $this->items[$id] = [];
            }
            $this->items[$id] += $item;
        }
    }

    /**
     * Returns a generator of the data instead of processing the items
     * @return \Generator
     */
    public function generator()
    {
        $identifier = $this->identifier;
        foreach ($this->iterator as $item) {
            yield [$identifier($item), $item];
        }
    }
}
