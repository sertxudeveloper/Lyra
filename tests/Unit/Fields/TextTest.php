<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use SertxuDeveloper\Lyra\Fields\Text;

class TextTest extends TestCase
{
    /**
     * Check the field can be initialized.
     *
     * @return void
     */
    public function test_can_be_initialized(): void {
        $field = Text::make('Title');

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('title', $field->getKey());
    }

    /**
     * Check the field can be set as HTML.
     *
     * @return void
     */
    public function test_can_be_set_as_html(): void {
        $field = Text::make('Title');

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertFalse($field->asHtml);

        $field->asHtml();
        $this->assertTrue($field->asHtml);
    }

    /**
     * Check the field can retrieve the additional data.
     *
     * @return void
     */
    public function test_can_retrieve_additional_data(): void {
        $field = Text::make('Title');

        $this->assertIsArray($field->additional());

        $this->assertArrayHasKey('asHtml', $field->additional());
        $this->assertFalse($field->additional()['asHtml']);
    }
}
