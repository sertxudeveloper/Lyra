<?php

namespace SertxuDeveloper\Lyra\Tests\Unit\Fields;

use SertxuDeveloper\Lyra\Fields\Image;
use SertxuDeveloper\Lyra\Tests\TestCase;

class ImageTest extends TestCase
{
    /**
     * Check the field can be initialized.
     *
     * @return void
     */
    public function test_can_be_initialized(): void {
        $field = Image::make('Image');

        $this->assertInstanceOf(Image::class, $field);
        $this->assertEquals('Image', $field->name);
        $this->assertEquals('image', $field->getKey());
    }

    /**
     * Check can set accept attribute.
     *
     * @return void
     */
    public function test_can_set_accept_attribute(): void {
        $field = Image::make('Image');

        $field->accept('image/png');
        $this->assertEquals('image/png', $field->accept);
    }

    /**
     * Check can set multiple attribute.
     *
     * @return void
     */
    public function test_can_set_multiple_attribute(): void {
        $field = Image::make('Image');

        $field->multiple();
        $this->assertTrue($field->multiple);
    }

    /**
     * Check can set disk attribute.
     *
     * @return void
     */
    public function test_can_set_disk_attribute(): void {
        $field = Image::make('Image');

        $field->disk('public');
        $this->assertEquals('public', $field->disk);

        $field->disk('local');
        $this->assertEquals('local', $field->disk);
    }

    /**
     * Check can set folder attribute.
     *
     * @return void
     */
    public function test_can_set_folder_attribute(): void {
        $field = Image::make('Image');

        $field->folder('avatars');
        $this->assertEquals('avatars', $field->folder);
    }

    /**
     * Check can be configured to keep original name.
     *
     * @return void
     */
    public function test_can_be_configured_to_keep_original_name(): void {
        $field = Image::make('Image');

        $field->keepOriginalName();
        $this->assertTrue($field->keepOriginalName);
    }

    /**
     * Check can be configured to be prunable.
     *
     * @return void
     */
    public function test_can_be_configured_to_be_prunable(): void {
        $field = Image::make('Image');

        $field->prunable();
        $this->assertTrue($field->prunable);
    }
}
