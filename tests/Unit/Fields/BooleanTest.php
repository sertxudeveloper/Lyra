<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use SertxuDeveloper\Lyra\Fields\Boolean;
use SertxuDeveloper\Lyra\Tests\Models\User;

class BooleanTest extends TestCase
{
    /**
     * Check the field can be initialized.
     *
     * @return void
     */
    public function test_can_be_initialized(): void {
        $field = Boolean::make('Boolean');

        $this->assertInstanceOf(Boolean::class, $field);
        $this->assertEquals('Boolean', $field->name);
        $this->assertEquals('boolean', $field->getKey());
    }

    /**
     * Check the field value is correctly showing as true.
     *
     * @return void
     */
    public function test_current_value_is_correctly_shown_as_true(): void {
        $field = Boolean::make('Active');

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => true,
        ]);

        $this->assertTrue($field->get($model));
    }

    /**
     * Check the field value is correctly showing as false.
     *
     * @return void
     */
    public function test_current_value_is_correctly_shown_as_false(): void {
        $field = Boolean::make('Active');

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => false,
        ]);

        $this->assertFalse($field->get($model));
    }

    /**
     * Check the field value is updated to true.
     *
     * @return void
     */
    public function test_value_updated_to_true(): void {
        $field = Boolean::make('Active');

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => false,
        ]);

        $data = [
            'active' => true,
        ];

        $field->save($model, $data);
        $this->assertTrue($model->active);
    }

    /**
     * Check the field value is updated to false.
     *
     * @return void
     */
    public function test_value_updated_to_false(): void {
        $field = Boolean::make('Active');

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => true,
        ]);

        $data = [
            'active' => false,
        ];

        $field->save($model, $data);
        $this->assertFalse($model->active);
    }

    /**
     * Check can be set custom true and false values.
     *
     * @return void
     */
    public function test_can_be_set_custom_true_and_false_values(): void {
        $field = Boolean::make('Active')
            ->trueValue('Yes')
            ->falseValue('No');

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => 'Yes',
        ]);

        $this->assertTrue($field->get($model));

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => 'No',
        ]);

        $this->assertFalse($field->get($model));
    }

    /**
     * Check value is updated with custom true and false values.
     *
     * @return void
     */
    public function test_value_updated_with_custom_true_and_false_values(): void {
        $field = Boolean::make('Active')
            ->trueValue('Yes')
            ->falseValue('No');

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => 'No',
        ]);

        $data = [
            'active' => true,
        ];

        $field->save($model, $data);

        $this->assertEquals('Yes', $model->active);

        $model = new User([
            'name' => 'Sertxu Dev',
            'active' => 'Yes',
        ]);

        $data = [
            'active' => false,
        ];

        $field->save($model, $data);

        $this->assertEquals('No', $model->active);
    }
}
