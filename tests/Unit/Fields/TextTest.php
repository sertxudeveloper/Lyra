<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Tests\Models\User;

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

    /**
     * Check computed field cannot be shown at forms.
     *
     * @return void
     */
    public function test_cannot_be_shown_at_forms(): void {
        /** @var User $this */
        $field = Text::make('Fullname', fn () => "$this->name $this->surname");

        $this->assertTrue($field->showOnIndex);
        $this->assertTrue($field->showOnShow);
        $this->assertFalse($field->showOnCreate);
        $this->assertFalse($field->showOnUpdate);
    }

    /**
     * Check computed field cannot be saved.
     *
     * @return void
     */
    public function test_cannot_be_saved(): void {
        /** @var User $this */
        $field = Text::make('Fullname', fn () => "$this->name $this->surname");

        $model = new User([
            'name' => 'Sertxu',
            'surname' => 'Dev',
            'fullname' => 'Sertxu Dev',
        ]);

        $this->assertEquals('Sertxu Dev', $field->get($model));

        $field->save($model, [
            'name' => 'Sertxu',
            'surname' => 'Dev',
            'fullname' => 'Sertxu Developer',
        ]);

        $this->assertEquals('Sertxu Dev', $model->fullname);
    }

    /**
     * Check field can be hidden at forms.
     *
     * @return void
     */
    public function test_can_be_hidden_at_forms(): void {
        $field = Text::make('Title')->hideOnForms();

        $this->assertTrue($field->showOnIndex);
        $this->assertTrue($field->showOnShow);
        $this->assertFalse($field->showOnCreate);
        $this->assertFalse($field->showOnUpdate);
    }

    /**
     * Check field can be hidden at index.
     *
     * @return void
     */
    public function test_can_be_hidden_at_index(): void {
        $field = Text::make('Title')->hideOnIndex();

        $this->assertFalse($field->showOnIndex);
        $this->assertTrue($field->showOnShow);
        $this->assertTrue($field->showOnCreate);
        $this->assertTrue($field->showOnUpdate);
    }

    /**
     * Check field can be hidden at show.
     *
     * @return void
     */
    public function test_can_be_hidden_at_show(): void {
        $field = Text::make('Title')->hideOnShow();

        $this->assertTrue($field->showOnIndex);
        $this->assertFalse($field->showOnShow);
        $this->assertTrue($field->showOnCreate);
        $this->assertTrue($field->showOnUpdate);
    }
}
