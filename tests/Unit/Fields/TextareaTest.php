<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use SertxuDeveloper\Lyra\Fields\Textarea;

class TextareaTest extends TestCase
{
    /**
     * Check the field can be initialized.
     *
     * @return void
     */
    public function test_can_be_initialized(): void {
        $field = Textarea::make('Excerpt');

        $this->assertInstanceOf(Textarea::class, $field);
        $this->assertEquals('Excerpt', $field->name);
        $this->assertEquals('excerpt', $field->getKey());
    }

    public function test_can_specify_rows(): void {
        $field = Textarea::make('Excerpt');

        $this->assertEquals(5, $field->rows);

        $field->rows(10);
        $this->assertEquals(10, $field->rows);
    }

    /**
     * Check the field can retrieve the additional data.
     *
     * @return void
     */
    public function test_can_retrieve_additional_data(): void {
        $field = Textarea::make('Excerpt');

        $this->assertIsArray($field->additional());

        $this->assertArrayHasKey('rows', $field->additional());
        $this->assertEquals(5, $field->additional()['rows']);
    }
}
