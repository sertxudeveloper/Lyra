<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use SertxuDeveloper\Lyra\Fields\Slug;

class SlugTest extends TestCase
{
    /**
     * Check the field can be initialized.
     *
     * @return void
     */
    public function test_can_be_initialized(): void {
        $field = Slug::make('Slug');

        $this->assertInstanceOf(Slug::class, $field);
        $this->assertEquals('Slug', $field->name);
        $this->assertEquals('slug', $field->getKey());
    }

    /**
     * Check the field can set a from field.
     *
     * @return void
     */
    public function test_can_set_a_from_field(): void {
        $field = Slug::make('Slug');

        $this->assertInstanceOf(Slug::class, $field);
        $this->assertEquals('Slug', $field->name);
        $this->assertEquals('slug', $field->getKey());

        $field->from('title');
        $this->assertEquals('title', $field->from);
    }

    /**
     * Check the field can set a separator.
     *
     * @return void
     */
    public function test_can_set_a_separator(): void {
        $field = Slug::make('Slug');

        $this->assertInstanceOf(Slug::class, $field);
        $this->assertEquals('Slug', $field->name);
        $this->assertEquals('slug', $field->getKey());

        $field->separator('-');
        $this->assertEquals('-', $field->separator);
    }

    /**
     * Check the field can retrieve the additional data.
     *
     * @return void
     */
    public function test_can_retrieve_additional_data(): void {
        $field = Slug::make('Slug')
            ->from('title');

        $this->assertIsArray($field->additional());

        $this->assertArrayHasKey('from', $field->additional());
        $this->assertEquals('title', $field->additional()['from']);

        $this->assertArrayHasKey('separator', $field->additional());
        $this->assertEquals('-', $field->additional()['separator']);
    }
}
