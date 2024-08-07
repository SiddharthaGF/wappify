<?php

declare(strict_types=1);

namespace AiluraCode\Wappify\Entities\Interactive;

use AiluraCode\Wappify\Models\Whatsapp;

class InteractiveMessage extends BaseInteractiveMessage
{
    public string $type;
    private object $button_reply;

    /**
     * InteractiveMessage constructor.
     *
     * @param Whatsapp $whatsapp
     */
    public function __construct(Whatsapp $whatsapp)
    {
        $this->type = $whatsapp->getMessage()->type ?? '';
        $this->button_reply = $whatsapp->getMessage()->button_reply ?? null;
    }

    /**
     * Check if the interactive message is a button.
     */
    public function isButtonReply(): bool
    {
        return 'button_reply' === $this->type;
    }

    /**
     * Get the button reply id.
     */
    public function getButtonReplyId(): string
    {
        // @phpstan-ignore-next-line
        return $this->button_reply->id;
    }

    /**
     * Get the button reply title.
     */
    public function getButtonReplyTitle(): string
    {
        // @phpstan-ignore-next-line
        return $this->button_reply->title;
    }
}
