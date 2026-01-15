<?php

namespace App\Tests\Book\Shelfmark;

use App\Book\Shelfmark\AuthorStrategy;
use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class AuthorStrategyTest extends TestCase {
    public function testGenerateFirstnameLastname(): void {
        $book = new Book()
            ->setAuthors(['Max Mustermann']);

        $strategy = new AuthorStrategy();

        $this->assertEquals('Muste', $strategy->generate($book, "5"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "0"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "-5"));
    }

    public function testGenerateLastnameCommaFirstname(): void {
        $book = new Book()
            ->setAuthors(['Mustermann, Max']);

        $strategy = new AuthorStrategy();

        $this->assertEquals('Muste', $strategy->generate($book, "5"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "0"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "-5"));
    }

    public function testGenerateFirstnameLastnameMultipleAuthors() {
        $book = new Book()
            ->setAuthors(['Max Mustermann', 'John Doe']);

        $strategy = new AuthorStrategy();

        $this->assertEquals('Muste', $strategy->generate($book, "5"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "0"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "-5"));
    }

    public function testGenerateLastnameCommaFirstnameAuthors() {
        $book = new Book()
            ->setAuthors(['Mustermann, Max', 'Doe, John']);

        $strategy = new AuthorStrategy();

        $this->assertEquals('Muste', $strategy->generate($book, "5"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "0"));
        $this->assertEquals('Mustermann', $strategy->generate($book, "-5"));
    }
}
