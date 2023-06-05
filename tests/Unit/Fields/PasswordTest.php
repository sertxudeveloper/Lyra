<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use PHPUnit\Framework\TestCase;
use SertxuDeveloper\Lyra\Fields\Password;
use SertxuDeveloper\Lyra\Tests\Models\User;

class PasswordTest extends TestCase
{
    /**
     * Check the field can be initialized.
     *
     * @return void
     */
    public function test_can_be_initialized(): void {
        $field = Password::make('Password');

        $this->assertInstanceOf(Password::class, $field);
        $this->assertEquals('Password', $field->name);
        $this->assertEquals('password', $field->getKey());
    }

    /**
     * Check the field value is not shown.
     *
     * @return void
     */
    public function test_current_value_is_not_shown(): void {
        $field = Password::make('Password');

        $model = new User([
            'name' => 'Sertxu Dev',
            'password' => 'password',
        ]);

        $this->assertNull($field->get($model));
    }

    public function test_value_updated_if_provided(): void {
        $field = Password::make('Password');
        $model = new User([
            'name' => 'Sertxu Dev',
            'password' => 'password',
        ]);

        $data = [
            'password' => 'new-password',
        ];

        $field->save($model, $data);
        $this->assertEquals('new-password', $model->password);
    }

    public function test_value_not_updated_if_empty(): void {
        $field = Password::make('Password');
        $model = new User([
            'name' => 'Sertxu Dev',
            'password' => 'password',
        ]);

        $data = [
            'password' => '',
        ];

        $field->save($model, $data);
        $this->assertEquals('password', $model->password);
    }
}
