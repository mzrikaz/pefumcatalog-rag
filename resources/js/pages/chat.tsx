import { useChat } from '@ai-sdk/react';
import { Head } from '@inertiajs/react';
import { DefaultChatTransport } from 'ai';
import { SendIcon, SparklesIcon } from 'lucide-react';
import type { ComponentProps, FormEvent } from 'react';
import { useState } from 'react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import ChatController from '@/actions/App/Http/Controllers/ChatController';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import { cn } from '@/lib/utils';

/**
 * Renders assistant markdown (bold, lists, links, etc.) with lightweight,
 * bubble-appropriate styling since the project has no typography plugin.
 */
function Markdown({ children }: { children: string }) {
    const components: ComponentProps<typeof ReactMarkdown>['components'] = {
        p: ({ children }) => <p className="mb-2 last:mb-0">{children}</p>,
        strong: ({ children }) => (
            <strong className="font-semibold">{children}</strong>
        ),
        ul: ({ children }) => (
            <ul className="mb-2 list-disc space-y-1 pl-5 last:mb-0">
                {children}
            </ul>
        ),
        ol: ({ children }) => (
            <ol className="mb-2 list-decimal space-y-1 pl-5 last:mb-0">
                {children}
            </ol>
        ),
        a: ({ children, href }) => (
            <a
                href={href}
                target="_blank"
                rel="noreferrer"
                className="underline underline-offset-2"
            >
                {children}
            </a>
        ),
        code: ({ children }) => (
            <code className="rounded bg-black/10 px-1 py-0.5 text-xs dark:bg-white/10">
                {children}
            </code>
        ),
    };

    return (
        <ReactMarkdown remarkPlugins={[remarkGfm]} components={components}>
            {children}
        </ReactMarkdown>
    );
}

type Perfume = {
    id: number;
    name: string;
    description: string;
    notes: string[];
    accords: string[];
    price: string;
    image_url: string;
};

function csrfToken(): string {
    const match = document.cookie.match(/(?:^|;\s*)XSRF-TOKEN=([^;]+)/);

    return match ? decodeURIComponent(match[1]) : '';
}

function PerfumeCard({ perfume }: { perfume: Perfume }) {
    return (
        <Card className="w-56 shrink-0 gap-3 overflow-hidden py-0">
            <img
                src={perfume.image_url}
                alt={perfume.name}
                className="aspect-square w-full object-cover"
            />
            <div className="flex flex-col gap-2 px-4 pb-4">
                <div className="flex items-start justify-between gap-2">
                    <h3 className="text-sm leading-tight font-semibold">
                        {perfume.name}
                    </h3>
                    <span className="text-sm font-medium whitespace-nowrap">
                        ${perfume.price}
                    </span>
                </div>
                <div className="flex flex-wrap gap-1">
                    {perfume.accords.slice(0, 3).map((accord) => (
                        <Badge key={accord} variant="secondary">
                            {accord}
                        </Badge>
                    ))}
                </div>
                <p className="line-clamp-2 text-xs text-muted-foreground">
                    {perfume.notes.join(', ')}
                </p>
            </div>
        </Card>
    );
}

export default function Chat() {
    const [input, setInput] = useState('');

    const { messages, sendMessage, status } = useChat({
        transport: new DefaultChatTransport({
            api: ChatController.store.url(),
            headers: () => ({ 'X-XSRF-TOKEN': csrfToken() }),
        }),
    });

    const isStreaming = status === 'submitted' || status === 'streaming';

    function handleSubmit(event: FormEvent) {
        event.preventDefault();

        if (!input.trim() || isStreaming) {
            return;
        }

        sendMessage({ text: input });
        setInput('');
    }

    return (
        <>
            <Head title="Perfume Concierge" />

            <div className="mx-auto flex h-screen w-full max-w-3xl flex-col">
                <header className="flex items-center gap-2 border-b border-border px-6 py-4">
                    <SparklesIcon className="size-5" />
                    <h1 className="text-lg font-semibold">Perfume Concierge</h1>
                </header>

                <div className="flex-1 space-y-6 overflow-y-auto px-6 py-6">
                    {messages.length === 0 && (
                        <p className="text-sm text-muted-foreground">
                            Tell me what you&apos;re looking for &mdash; an
                            occasion, a mood, a scent family &mdash; and
                            I&apos;ll find perfumes from our catalog for you.
                        </p>
                    )}

                    {messages.map((message) => (
                        <div
                            key={message.id}
                            className={cn(
                                'flex flex-col gap-3',
                                message.role === 'user'
                                    ? 'items-end'
                                    : 'items-start',
                            )}
                        >
                            {message.parts.map((part, index) => {
                                if (part.type === 'text') {
                                    return (
                                        <div
                                            key={index}
                                            className={cn(
                                                'max-w-[80%] rounded-2xl px-4 py-2 text-sm',
                                                message.role === 'user'
                                                    ? 'bg-primary whitespace-pre-wrap text-primary-foreground'
                                                    : 'bg-muted text-foreground',
                                            )}
                                        >
                                            {message.role === 'user' ? (
                                                part.text
                                            ) : (
                                                <Markdown>{part.text}</Markdown>
                                            )}
                                        </div>
                                    );
                                }

                                if (part.type === 'tool-search_perfumes') {
                                    if (part.state === 'output-available') {
                                        let perfumes: Perfume[] = [];

                                        try {
                                            const parsed = JSON.parse(
                                                String(part.output),
                                            );
                                            perfumes = Array.isArray(parsed)
                                                ? parsed
                                                : [];
                                        } catch {
                                            perfumes = [];
                                        }

                                        if (perfumes.length === 0) {
                                            return null;
                                        }

                                        return (
                                            <div
                                                key={index}
                                                className="flex w-full gap-3 overflow-x-auto pb-2"
                                            >
                                                {perfumes.map((perfume) => (
                                                    <PerfumeCard
                                                        key={perfume.id}
                                                        perfume={perfume}
                                                    />
                                                ))}
                                            </div>
                                        );
                                    }

                                    if (
                                        part.state === 'output-error' ||
                                        part.state === 'output-denied'
                                    ) {
                                        return (
                                            <div
                                                key={index}
                                                className="text-xs text-destructive"
                                            >
                                                Couldn&apos;t search the catalog
                                                just now &mdash; try asking
                                                again.
                                            </div>
                                        );
                                    }

                                    return (
                                        <div
                                            key={index}
                                            className="flex items-center gap-2 text-xs text-muted-foreground"
                                        >
                                            <Spinner className="size-3" />
                                            Searching the catalog&hellip;
                                        </div>
                                    );
                                }

                                return null;
                            })}
                        </div>
                    ))}

                    {status === 'submitted' && (
                        <div className="flex items-center gap-2 text-sm text-muted-foreground">
                            <Spinner className="size-4" />
                            Thinking&hellip;
                        </div>
                    )}
                </div>

                <form
                    onSubmit={handleSubmit}
                    className="flex items-end gap-2 border-t border-border p-4"
                >
                    <textarea
                        value={input}
                        onChange={(event) => setInput(event.target.value)}
                        onKeyDown={(event) => {
                            if (event.key === 'Enter' && !event.shiftKey) {
                                event.preventDefault();
                                handleSubmit(event);
                            }
                        }}
                        placeholder="I need a perfume for a wedding ceremony..."
                        rows={1}
                        className="flex-1 resize-none rounded-md border border-input bg-background px-3 py-2 text-sm shadow-xs outline-none focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50"
                    />
                    <Button
                        type="submit"
                        size="icon"
                        disabled={isStreaming || !input.trim()}
                    >
                        <SendIcon />
                    </Button>
                </form>
            </div>
        </>
    );
}
