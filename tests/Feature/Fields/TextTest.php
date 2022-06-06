<?php

namespace SertxuDeveloper\Lyra\Tests\Feature\Fields;

use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Tests\TestCase;

class TextTest extends TestCase
{
    /**
     * Check text field can be initialized.
     *
     * @return void
     */
    public function test_field_can_be_initialized(): void
    {
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

    /**
     * Check text field can be initialized with a custom column.
     *
     * @return void
     */
    public function test_field_can_be_initialized_with_a_custom_column(): void
    {
        $field = Text::make('Title', 'name');

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('field-text', $field->component);
        $this->assertEquals('name', $field->column);
        $this->assertEquals('name', $field->getKey());
    }

    /**
     * Check field can be initialized as computed field.
     *
     * @return void
     */
    public function test_field_can_be_initialized_as_computed_field(): void
    {
        $field = Text::make('Title', fn () => 'Custom value');

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('field-text', $field->component);
        $this->assertIsCallable($field->column);
        $this->assertEquals('title', $field->getKey());
        $this->assertTrue($field->showOnIndex);
        $this->assertTrue($field->showOnShow);
        $this->assertFalse($field->showOnCreate);
        $this->assertFalse($field->showOnUpdate);
    }

    /**
     * Check text field can contain HTML data.
     *
     * @return void
     */
    public function test_field_can_contain_html_data(): void
    {
        $field = Text::make('Title')
            ->asHtml();

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('field-text', $field->component);
        $this->assertEquals('title', $field->column);
        $this->assertEquals('title', $field->getKey());
        $this->assertTrue($field->asHtml);
    }

    /**
     * Check field can be hidden on index.
     *
     * @return void
     */
    public function test_field_can_be_hidden_on_index(): void
    {
        $field = Text::make('Title')
            ->hideOnIndex();

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('field-text', $field->component);
        $this->assertEquals('title', $field->column);
        $this->assertEquals('title', $field->getKey());
        $this->assertFalse($field->showOnIndex);
    }

    /**
     * Check field can be hidden on show.
     *
     * @return void
     */
    public function test_field_can_be_hidden_on_show(): void
    {
        $field = Text::make('Title')
            ->hideOnShow();

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('field-text', $field->component);
        $this->assertEquals('title', $field->column);
        $this->assertEquals('title', $field->getKey());
        $this->assertFalse($field->showOnShow);
    }

    /**
     * Check field can be hidden on forms.
     *
     * @return void
     */
    public function test_field_can_be_hidden_on_forms(): void
    {
        $field = Text::make('Title')
            ->hideOnForms();

        $this->assertInstanceOf(Text::class, $field);
        $this->assertEquals('Title', $field->name);
        $this->assertEquals('field-text', $field->component);
        $this->assertEquals('title', $field->column);
        $this->assertEquals('title', $field->getKey());
        $this->assertFalse($field->showOnCreate);
        $this->assertFalse($field->showOnUpdate);
    }
}
