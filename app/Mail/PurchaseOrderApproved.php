<?php

namespace App\Mail;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PurchaseOrderApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public PurchaseOrder $purchaseOrder
    ) {
        $this->purchaseOrder->loadMissing([
            'supplier',
            'creator',
            'approver',
            'items.product',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Purchase Order Approved: ' . $this->purchaseOrder->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.purchase-orders.approved',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
