<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CategoryAndItemImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_details_and_photo_are_saved_displayed_and_replaced(): void
    {
        Storage::fake('public');
        $this->actingAs($this->createUser());

        $this->post(route('categories.store'), [
            'name' => 'Elektronik',
            'description' => 'Perangkat elektronik',
            'image' => $this->fakePng('category.png'),
        ])->assertRedirect(route('categories.index'));

        $category = Category::query()->firstOrFail();
        $oldImagePath = $category->image_path;

        $this->assertSame('Perangkat elektronik', $category->description);
        Storage::disk('public')->assertExists($oldImagePath);

        $this->get(route('categories.show', $category))
            ->assertOk()
            ->assertSee('Perangkat elektronik')
            ->assertSee('storage/'.$oldImagePath, false);

        $this->put(route('categories.update', $category), [
            'name' => 'Elektronik',
            'description' => 'Perangkat elektronik terbaru',
            'image' => $this->fakePng('new-category.png'),
        ])->assertRedirect(route('categories.show', $category));

        $category->refresh();

        $this->assertSame('Perangkat elektronik terbaru', $category->description);
        $this->assertNotSame($oldImagePath, $category->image_path);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($category->image_path);
    }

    public function test_item_details_and_photo_are_saved_displayed_and_replaced(): void
    {
        Storage::fake('public');
        $this->actingAs($this->createUser());
        $category = Category::query()->create(['name' => 'Elektronik']);

        $this->post(route('items.store'), [
            'category_id' => $category->id,
            'title' => 'Laptop',
            'description' => 'Laptop untuk operasional',
            'quantity' => 2,
            'is_active' => 1,
            'image' => $this->fakePng('item.png'),
        ])->assertRedirect();

        $item = Item::query()->firstOrFail();
        $oldImagePath = $item->image_path;

        $this->assertSame('Laptop untuk operasional', $item->description);
        Storage::disk('public')->assertExists($oldImagePath);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Laptop untuk operasional')
            ->assertSee('storage/'.$oldImagePath, false);

        $this->put(route('items.update', $item), [
            'category_id' => $category->id,
            'title' => 'Laptop',
            'description' => 'Laptop diperbarui',
            'quantity' => 3,
            'is_active' => 1,
            'image' => $this->fakePng('new-item.png'),
        ])->assertRedirect();

        $item->refresh();

        $this->assertSame('Laptop diperbarui', $item->description);
        $this->assertSame(3, $item->quantity);
        $this->assertNotSame($oldImagePath, $item->image_path);
        Storage::disk('public')->assertMissing($oldImagePath);
        Storage::disk('public')->assertExists($item->image_path);
    }

    public function test_non_image_upload_is_rejected(): void
    {
        Storage::fake('public');
        $this->actingAs($this->createUser());

        $this->post(route('categories.store'), [
            'name' => 'Kategori',
            'image' => UploadedFile::fake()->createWithContent('not-an-image.txt', 'plain text'),
        ])
            ->assertRedirect()
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('categories', 0);
        $this->assertSame([], Storage::disk('public')->allFiles('categories'));
    }

    private function createUser(): User
    {
        return User::query()->create([
            'name' => 'Test User',
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('password'),
        ]);
    }

    private function fakePng(string $filename): UploadedFile
    {
        $content = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/p1sAAAAASUVORK5CYII=', true);

        if ($content === false) {
            throw new \RuntimeException('The test image could not be decoded.');
        }

        return UploadedFile::fake()->createWithContent($filename, $content);
    }
}
