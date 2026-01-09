<?php

namespace App\Book\Shelfmark;

use App\Entity\Book;
use Symfony\Component\DependencyInjection\Attribute\AutowireIterator;

readonly class Generator {

    public const string MAGIC_STRING = 'automatisch';

    /**
     * @param StrategyInterface[] $strategies
     */
    public function __construct(
        #[AutowireIterator(StrategyInterface::AUTOCONFIGURE_TAG)] private iterable $strategies
    ) {

    }

    /**
     * @throws CategoryNotSetException
     * @throws InvalidStrategyException
     */
    public function generate(Book $book): string {
        if($book->getCategory() === null) {
            throw new CategoryNotSetException();
        }

        $category = $book->getCategory();

        foreach($this->strategies as $strategy) {
            if(get_class($strategy) === $category->getShelfmarkGenerator()) {
                return $strategy->generate($book, $category->getShelfmarkGeneratorParameter());
            }
        }

        throw new InvalidStrategyException();
    }

    /**
     * @return StrategyInterface[]
     */
    public function getStrategies(): array {
        return iterator_to_array($this->strategies);
    }
}
