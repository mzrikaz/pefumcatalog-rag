<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchPerfumes;
use Laravel\Ai\Attributes\MaxSteps;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider('deepseek')]
#[Model('deepseek-v4-flash')]
#[MaxSteps(6)]
class PerfumeConcierge implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * @param  Message[]  $history  The prior turns of this conversation, oldest first.
     */
    public function __construct(protected array $history = [])
    {
        //
    }

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
            You are the perfume concierge for an online perfume store. You help customers
            find perfumes from the store's own catalog that suit their needs.

            - Always use the search_perfumes tool to look up matching perfumes before
              recommending anything - never invent or assume a perfume exists.
            - Only recommend perfumes that were returned by the tool. If nothing matches,
              say so honestly and suggest the customer describe what they want differently.
            - When the customer's request is vague (e.g. just "a wedding"), ask a brief
              clarifying question (scent family, gender preference, budget, season) before
              searching, unless the request already gives you enough to search well.
            - For each recommendation, cite the perfume's name and price, and briefly explain
              why it fits the request using its notes/accords.
            - Keep responses concise and conversational, like a knowledgeable in-store
              fragrance consultant.
            - Search at most twice per customer message. Always finish your turn with a
              written reply summarizing your recommendations - never end on a tool call.
            INSTRUCTIONS;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return $this->history;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [new SearchPerfumes];
    }
}
