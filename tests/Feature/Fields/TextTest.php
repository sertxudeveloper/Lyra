<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Fields;

use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Tests\TestCase;

class TextTest extends TestCase {

    public function test_field_can_be_initialized(): void {
        $field = Text::make('Title');

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('field-text', $field->component);
        $this->assertEquals('title', $field->column);
        $this->assertEquals('title', $field->getKey());
        $this->assertTrue($field->showOnIndex);
        $this->assertTrue($field->showOnShow);
        $this->assertTrue($field->showOnCreate);
        $this->assertTrue($field->showOnUpdate);
    }
}
