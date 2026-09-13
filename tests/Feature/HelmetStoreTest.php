<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\ProductSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class HelmetStoreTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            ProductSeeder::class,
        ]);
    }

    public function test_home_page_loads_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Fan Helm');
    }

    public function test_product_detail_page_loads_successfully()
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $response = $this->get('/product/' . $product->slug);
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_cart_workflow()
    {
        $product = Product::first();

        // Add to cart
        $response = $this->post('/cart/add', [
            'product_id' => $product->id,
            'size' => 'L',
            'quantity' => 2,
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('cart');

        // View cart
        $cartResponse = $this->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee($product->name);
    }

    public function test_checkout_workflow()
    {
        $product = Product::first();

        // Set session cart
        $cartItemKey = $product->id . '_L';
        $cart = [
            $cartItemKey => [
                'product_id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'size' => 'L',
                'price' => (float) $product->price,
                'quantity' => 1,
                'image' => $product->image,
                'subtotal' => (float) $product->price,
            ]
        ];

        $response = $this->withSession(['cart' => $cart])->post('/checkout', [
            'namaLengkap' => 'Test Pembeli',
            'emailAddress' => 'test@example.com',
            'noHP' => '081234567890',
            'alamatLengkap' => 'Jl. Jenderal Sudirman No. 1, Jakarta',
        ]);

        $order = Order::where('customer_email', 'test@example.com')->first();
        $this->assertNotNull($order);
        $response->assertRedirect(route('checkout.success', ['order_code' => $order->order_code]));

        // Check success page
        $successResponse = $this->get('/checkout/success/' . $order->order_code);
        $successResponse->assertStatus(200);
        $successResponse->assertSee($order->order_code);

        // Check invoice page
        $invoiceResponse = $this->get('/order/' . $order->order_code . '/invoice');
        $invoiceResponse->assertStatus(200);
        $invoiceResponse->assertSee($order->order_code);
        $invoiceResponse->assertSee('Test Pembeli');
    }

    public function test_contact_form_submission()
    {
        $response = $this->post('/contact', [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'mobile' => '0811223344',
            'message' => 'Halo, apakah helm Full Fan ukuran XL masih tersedia?',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('contact_messages', [
            'email' => 'john@example.com',
            'first_name' => 'John',
        ]);
    }

    public function test_user_authentication_flow()
    {
        // Login as customer
        $response = $this->post('/login', [
            'email' => 'customer@fanhelm.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticated();

        // Customer orders page
        $orderPage = $this->get('/customer/orders');
        $orderPage->assertStatus(200);

        // Logout
        $logoutResponse = $this->post('/logout');
        $logoutResponse->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_admin_access_control()
    {
        // Guest blocked from admin
        $guestResponse = $this->get('/admin/dashboard');
        $guestResponse->assertRedirect(route('login'));

        // Customer blocked from admin
        $customer = User::where('role', 'customer')->first();
        $userResponse = $this->actingAs($customer)->get('/admin/dashboard');
        $userResponse->assertRedirect(route('home'));

        // Admin allowed
        $admin = User::where('role', 'admin')->first();
        $adminResponse = $this->actingAs($admin)->get('/admin/dashboard');
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Dashboard');
    }

    public function test_admin_product_crud()
    {
        $admin = User::where('role', 'admin')->first();

        // Create product
        $createResponse = $this->actingAs($admin)->post('/admin/products', [
            'name' => 'Apex Carbon Series',
            'category' => 'Full-Face',
            'price' => 1500000,
            'subtitle' => 'Helm balap super ringan',
            'sizes' => ['M', 'L', 'XL'],
            'is_weekly_featured' => 1,
        ]);

        $createResponse->assertRedirect(route('admin.products.index'));
        $product = Product::where('name', 'Apex Carbon Series')->first();
        $this->assertNotNull($product);

        // Update product
        $updateResponse = $this->actingAs($admin)->put('/admin/products/' . $product->id, [
            'name' => 'Apex Carbon Series V2',
            'category' => 'Full-Face',
            'price' => 1650000,
            'sizes' => ['L', 'XL'],
            'is_weekly_featured' => 1,
        ]);

        $updateResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Apex Carbon Series V2',
        ]);

        // Delete product
        $deleteResponse = $this->actingAs($admin)->delete('/admin/products/' . $product->id);
        $deleteResponse->assertRedirect(route('admin.products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_admin_order_status_update()
    {
        $admin = User::where('role', 'admin')->first();

        $order = Order::create([
            'order_code' => 'FAN-TEST-001',
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@example.com',
            'customer_phone' => '0812345678',
            'customer_address' => 'Jl. Merdeka No. 10',
            'bank_name' => 'Mandiri',
            'account_number' => '1234',
            'recipient_name' => 'Akbar',
            'total_amount' => 900000,
            'status' => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch('/admin/orders/' . $order->id . '/status', [
            'status' => 'shipped',
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals('shipped', $order->fresh()->status);
    }
}
