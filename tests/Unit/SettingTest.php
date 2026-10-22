<?php

namespace Tests\Unit;

use App\Models\Banner;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Setting::flushCache();
    }

    public function test_get_returns_stored_value(): void
    {
        Setting::set('site_name', 'FERPLEC');

        $this->assertSame('FERPLEC', Setting::get('site_name'));
    }

    public function test_get_returns_default_for_missing_key(): void
    {
        $this->assertSame('por defecto', Setting::get('no_existe', 'por defecto'));
    }

    public function test_set_many_saves_and_invalidates_cache(): void
    {
        Setting::setMany([
            'site_name' => 'FERPLEC',
            'site_slogan' => 'Fácil',
        ]);

        $this->assertSame('FERPLEC', Setting::get('site_name'));
        $this->assertSame('Fácil', Setting::get('site_slogan'));
    }

    public function test_banner_active_scope_excludes_expired_banners(): void
    {
        Banner::factory()->create(['title' => 'Vigente']);
        Banner::factory()->expired()->create(['title' => 'Vencido']);
        Banner::factory()->create(['title' => 'Inactivo', 'is_active' => false]);

        $titles = Banner::active()->pluck('title')->all();

        $this->assertContains('Vigente', $titles);
        $this->assertNotContains('Vencido', $titles);
        $this->assertNotContains('Inactivo', $titles);
    }
}
