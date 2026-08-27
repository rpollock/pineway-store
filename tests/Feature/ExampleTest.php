<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_club_pages_render(): void
    {
        foreach (['/', '/the-course', '/the-club', '/visit', '/book', '/gallery', '/contact'] as $uri) {
            $this->get($uri)->assertOk();
        }

        $this->get('/')->assertSee('Book a tee time');
        $this->get('/the-club')
            ->assertSee('A short history')
            ->assertSee('Adam Durie')
            ->assertSee('Chris Gaunt')
            ->assertSee('28 Woodthorpe Lane');
        $this->get('/visit')->assertSee('2026 green fees')->assertSee('£60');
        $this->get('/gallery')
            ->assertSee('js-gallery-open', false)
            ->assertSee('Championship parkland, Woodthorpe');
    }

    public function test_an_enquiry_can_be_sent(): void
    {
        $this->from('/contact')->post('/contact', [
            'name' => 'Alexander Whitmore',
            'email' => 'a.whitmore@email.com',
            'subject' => 'Visitor tee time',
            'message' => 'Four ball, Thursday morning if possible.',
        ])->assertRedirect('/contact')->assertSessionHas('enquiry_sent');
    }
}
