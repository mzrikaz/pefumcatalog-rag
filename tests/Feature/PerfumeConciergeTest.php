<?php

namespace Tests\Feature;

use App\Ai\Agents\PerfumeConcierge;
use App\Ai\Tools\SearchPerfumes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\MessageRole;
use Tests\TestCase;

class PerfumeConciergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_is_equipped_with_the_search_perfumes_tool()
    {
        $tools = (new PerfumeConcierge)->tools();

        $this->assertCount(1, $tools);
        $this->assertInstanceOf(SearchPerfumes::class, $tools[0]);
    }

    public function test_it_exposes_the_given_conversation_history()
    {
        $history = [new Message(MessageRole::User, 'I need something fresh for summer.')];

        $agent = new PerfumeConcierge($history);

        $this->assertSame($history, $agent->messages());
    }

    public function test_it_can_be_faked_and_prompted()
    {
        PerfumeConcierge::fake(['The Rose Garden Eau de Parfum would suit a wedding beautifully.']);

        $response = (new PerfumeConcierge)->prompt('I need a perfume for a wedding ceremony.');

        PerfumeConcierge::assertPrompted(fn ($prompt) => $prompt->contains('wedding'));

        $this->assertStringContainsString('Rose Garden', $response->text);
    }
}
