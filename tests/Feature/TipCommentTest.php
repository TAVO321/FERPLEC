<?php

namespace Tests\Feature;

use App\Models\Tip;
use App\Models\TipComment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TipCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_submit_a_comment(): void
    {
        $tip = Tip::factory()->create();

        $this->post(route('tips.comments.store', $tip), [
            'name' => 'Ana',
            'email' => 'ana@example.com',
            'content' => 'Muy útil, gracias.',
        ])->assertRedirect();

        $this->assertDatabaseHas('tip_comments', [
            'tip_id' => $tip->id,
            'name' => 'Ana',
            'content' => 'Muy útil, gracias.',
            'is_approved' => false,
        ]);
    }

    public function test_comment_requires_name_and_content(): void
    {
        $tip = Tip::factory()->create();

        $this->post(route('tips.comments.store', $tip), [])
            ->assertSessionHasErrors(['name', 'content']);

        $this->assertDatabaseCount('tip_comments', 0);
    }

    public function test_honeypot_blocks_bots(): void
    {
        $tip = Tip::factory()->create();

        $this->post(route('tips.comments.store', $tip), [
            'name' => 'Bot',
            'content' => 'spam',
            'honeypot' => 'http://spam.example',
        ]);

        $this->assertDatabaseCount('tip_comments', 0);
    }

    public function test_only_approved_comments_are_visible(): void
    {
        $tip = Tip::factory()->create();

        TipComment::factory()->for($tip)->create(['content' => 'Comentario visible', 'is_approved' => true]);
        TipComment::factory()->for($tip)->create(['content' => 'Comentario pendiente', 'is_approved' => false]);

        $this->get(route('tips.show', $tip))
            ->assertOk()
            ->assertSee('Comentario visible')
            ->assertDontSee('Comentario pendiente');
    }

    public function test_admin_can_approve_a_comment(): void
    {
        $comment = TipComment::factory()->create(['is_approved' => false]);

        $this->actingAs(User::factory()->admin()->create())
            ->post("/admin/tip-comments/{$comment->id}/approve")
            ->assertRedirect();

        $this->assertTrue($comment->fresh()->is_approved);
    }

    public function test_admin_can_delete_a_comment(): void
    {
        $comment = TipComment::factory()->create();

        $this->actingAs(User::factory()->admin()->create())
            ->delete("/admin/tip-comments/{$comment->id}")
            ->assertRedirect();

        $this->assertDatabaseMissing('tip_comments', ['id' => $comment->id]);
    }
}
