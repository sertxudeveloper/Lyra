<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use SertxuDeveloper\Lyra\Fields\Text;
use SertxuDeveloper\Lyra\Tests\Models\User;

class TextAlignTest extends TestCase
{
    /**
     * Check if the default text align is left.
     *
     * @return void
     */
    public function test_default_text_align_is_left(): void {
        $field = Text::make('Name');

        $this->assertEquals('left', $field->align);
    }

    /**
     * Check align can be set to right.
     *
     * @return void
     */
    public function test_align_can_be_set_to_right(): void {
        $field = Text::make('Name')->textRight();

        $this->assertEquals('right', $field->align);
    }

    /**
     * Check align can be set to center.
     *
     * @return void
     */
    public function test_align_can_be_set_to_center(): void {
        $field = Text::make('Name')->textCenter();

        $this->assertEquals('center', $field->align);
    }

    /**
     * Check align can be set to justify.
     *
     * @return void
     */
    public function test_align_can_be_set_to_justify(): void {
        $field = Text::make('Name')->textJustify();

        $this->assertEquals('justify', $field->align);
    }

    /**
     * Check align can be set to left.
     *
     * @return void
     */
    public function test_align_can_be_set_to_initial(): void {
        $field = Text::make('Name')->textRight();

        $this->assertEquals('right', $field->align);

        $field->textLeft();
        $this->assertEquals('left', $field->align);
    }

    /**
     * Check align field sets the property in the array.
     *
     * @return void
     */
    public function test_align_field_sets_the_property_in_the_array(): void {
        $field = Text::make('Name')->textRight();

        $model = new User([
            'name' => 'Sertxu Dev',
        ]);

        $this->assertArrayHasKey('align', $field->toArray($model));
        $this->assertEquals('right', $field->toArray($model)['align']);
    }
}
