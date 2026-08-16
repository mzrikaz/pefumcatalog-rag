<?php

namespace Tests\Feature;

use App\Ai\Agents\PerfumeConcierge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_chat_homepage_renders()
    {
        $response = $this->get(route('home'));

        $response->assertOk();
    }

    public function test_it_streams_a_reply_to_a_new_message()
    {
        PerfumeConcierge::fake(['Hello! What kind of perfume are you looking for?']);

        $response = $this->post(route('chat.store'), [
            'messages' => [
                [
                    'id' => 'msg-1',
                    'role' => 'user',
                    'parts' => [
                        ['type' => 'text', 'text' => 'Hi there'],
                    ],
                ],
            ],
        ]);

        $response->assertOk();
        $this->assertStringStartsWith('text/event-stream', $response->headers->get('Content-Type'));
        $response->assertStreamed();
        $this->assertStringContainsString('Hello', $response->streamedContent());
    }
}
