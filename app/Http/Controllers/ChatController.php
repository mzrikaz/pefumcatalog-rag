<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PerfumeConcierge;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Ai\Messages\Message;
use Symfony\Component\HttpFoundation\Response as StreamResponse;

class ChatController extends Controller
{
    /**
     * Show the chat homepage.
     */
    public function index(): Response
    {
        return Inertia::render('chat');
    }

    /**
     * Stream a response from the perfume concierge for the given conversation.
     */
    public function store(Request $request): StreamResponse
    {
        $messages = $request->array('messages');

        $lastUserIndex = collect($messages)->keys()
            ->last(fn (int $index) => ($messages[$index]['role'] ?? null) === 'user');

        abort_if($lastUserIndex === null, 422, 'No user message provided.');

        $prompt = $this->textFromParts($messages[$lastUserIndex]['parts'] ?? []);

        $history = collect($messages)
            ->slice(0, $lastUserIndex)
            ->map(fn (array $message) => new Message(
                $message['role'],
                $this->textFromParts($message['parts'] ?? []),
            ))
            ->filter(fn (Message $message) => filled($message->content))
            ->values()
            ->all();

        return (new PerfumeConcierge($history))
            ->stream($prompt)
            ->usingVercelDataProtocol()
            ->toResponse($request);
    }

    /**
     * Concatenate the text parts of a UI message into a single string.
     *
     * @param  array<int, array{type: string, text?: string}>  $parts
     */
    protected function textFromParts(array $parts): string
    {
        return (new Collection($parts))
            ->where('type', 'text')
            ->pluck('text')
            ->implode('');
    }
}
