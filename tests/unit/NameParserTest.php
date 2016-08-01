<?php

use Guenther\Guenther\Parser\NameParser;
use PHPUnit\Framework\TestCase;

class NameParserTest extends TestCase
{
    /** @test */
    public function it_holds_pieces_as_lowercase()
    {
        $parser = new NameParser(['Foo', 'BAR', 'baz']);
        $this->assertEquals(['foo', 'bar', 'baz'], $parser->getPieces());
    }

    /** @test */
    public function it_can_output_the_name_as_uppercamelcase()
    {
        $parser = new NameParser(['Foo', 'BAR', 'baz']);
        $this->assertEquals('FooBarBaz', $parser->getAsUpperCamelCase());

        $parser = new NameParser(['FOO']);
        $this->assertEquals('Foo', $parser->getAsUpperCamelCase());
    }

    /** @test */
    public function it_can_output_the_name_as_titlecase()
    {
        $parser = new NameParser(['Foo', 'BAR', 'baz']);
        $this->assertEquals('Foo Bar Baz', $parser->getAsTitleCase());

        $parser = new NameParser(['Foo']);
        $this->assertEquals('Foo', $parser->getAsTitleCase());
    }

    /** @test */
    public function it_can_output_the_name_as_kebabcase()
    {
        $parser = new NameParser(['Foo', 'BAR', 'baz']);
        $this->assertEquals('foo-bar-baz', $parser->getAsKebabCase());

        $parser = new NameParser(['Foo']);
        $this->assertEquals('foo', $parser->getAsKebabCase());
    }

    /** @test */
    public function it_parses_from_camelcase()
    {
        $parser = NameParser::parseFromCamelCase('FooBarBaz');
        $this->assertEquals(['foo', 'bar', 'baz'], $parser->getPieces());

        $parser = NameParser::parseFromCamelCase('fooBarBaz');
        $this->assertEquals(['foo', 'bar', 'baz'], $parser->getPieces());

        $parser = NameParser::parseFromCamelCase('Foo');
        $this->assertEquals(['foo'], $parser->getPieces());
    }

    /** @test */
    public function it_parses_from_titlecase()
    {
        $parser = NameParser::parseFromTitleCase('Foo Bar Baz');
        $this->assertEquals(['foo', 'bar', 'baz'], $parser->getPieces());

        $parser = NameParser::parseFromTitleCase('foo Bar BAZ');
        $this->assertEquals(['foo', 'bar', 'baz'], $parser->getPieces());

        $parser = NameParser::parseFromTitleCase('Foo');
        $this->assertEquals(['foo'], $parser->getPieces());
    }

    /** @test */
    public function it_parses_from_kebabcase()
    {
        $parser = NameParser::parseFromKebabCase('foo-bar-baz');
        $this->assertEquals(['foo', 'bar', 'baz'], $parser->getPieces());

        $parser = NameParser::parseFromKebabCase('foo-Bar-BAZ');
        $this->assertEquals(['foo', 'bar', 'baz'], $parser->getPieces());

        $parser = NameParser::parseFromKebabCase('foo');
        $this->assertEquals(['foo'], $parser->getPieces());
    }
}
