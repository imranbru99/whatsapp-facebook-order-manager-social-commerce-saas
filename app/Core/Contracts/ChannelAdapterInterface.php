<?php

namespace App\Core\Contracts;

interface ChannelAdapterInterface
{
    public function provider(): string;

    /**
     * @param  array<string, mixed>  $payload
     * @return array{external_id: string, status: string}
     */
    public function sendMessage(string $threadId, string $body, array $payload = []): array;

    /**
     * @param  array<string, mixed>  $webhook
     * @return array{thread_id: string, external_id: string, body: string, sender: string}|null
     */
    public function parseIncoming(array $webhook): ?array;
}
