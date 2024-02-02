<?php

namespace Tests\Feature;

use App\Enums\StandardActiveStatus;
use App\Filament\Resources\StoreResource\Pages\CreateStore;
use App\Models\Admin\User;
use App\Models\Image;
use App\Models\Store;
use App\Filament\Resources\ImageResource\Pages\CreateImage;
use Livewire\Livewire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

use Illuminate\Support\Facades\Storage;
use Str;
use Tests\TestCase;

class ManageImagesInAdminPanelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp() : void
    {
        parent::setUp();

        $adminUser = User::factory()->create();
        $this->actingAs($adminUser);
    }

    public function test_should_successfully_create_image_with_variants_images() : void
    {
        $image = UploadedFile::fake()->image('example.jpg', 500, 500);
        $watermark = UploadedFile::fake()->image('watermark.jpg', 200, 200);
        $expectedFileName = Str::slug('test image');

        Storage::fake(config('filament.default_filesystem_disk'));

        $stores = Store::factory(2)->make();
        foreach ($stores as $store) {

            Livewire::test(CreateStore::class)
                ->fillForm($store->getAttributes())
                ->set('data.watermark_filename', [$watermark])
                ->call('create')
                ->assertHasNoFormErrors();
        }

        Livewire::test(CreateImage::class)
            ->fillForm([
                'name' => 'test image',
                'slug' => Str::slug('test-image'),
                'status' => StandardActiveStatus::ACTIVE->value,
            ])
            ->set('data.attachment_file_name', $image)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('images', [
            'name' => 'test image'
        ]);

        $filesToCheck = [
            config('images.product_image_path')
            . config('images.product_original_path_append')
            . '/' . $expectedFileName . '.' . $image->guessExtension(),

            //the thumbnail is being saved as png image
            config('images.product_image_path')
            . config('images.product_thumbnail_path_append')
            . '/' . $expectedFileName . '.png',
        ];

        foreach ($stores as $store) {

            //domain variants are being saved as png image
            $filesToCheck[] = config('images.product_image_path')
                . '/' . Str::slug($store->domain)
                . '/' . $expectedFileName . '.png';
        }

        Storage::disk(config('filament.default_filesystem_disk'))
            ->assertExists($filesToCheck);
    }
}
