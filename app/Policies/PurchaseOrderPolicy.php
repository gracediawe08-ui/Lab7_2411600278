<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }

    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff']);
    }

    public function update(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return in_array($user->role, ['admin', 'manager'])
            && in_array($purchaseOrder->status, ['draft', 'rejected']);
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return $user->role === 'admin'
            && in_array($purchaseOrder->status, ['draft', 'rejected']);
    }

    public function submit(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff'])
            && in_array($purchaseOrder->status, ['draft', 'rejected']);
    }

    public function approve(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return in_array($user->role, ['admin', 'manager'])
            && $purchaseOrder->status === 'pending_approval';
    }

    public function reject(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return in_array($user->role, ['admin', 'manager'])
            && $purchaseOrder->status === 'pending_approval';
    }

    public function send(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return in_array($user->role, ['admin', 'manager'])
            && $purchaseOrder->status === 'approved';
    }

    public function receive(User $user, PurchaseOrder $purchaseOrder): bool
    {
        return in_array($user->role, ['admin', 'manager', 'staff'])
            && in_array($purchaseOrder->status, ['sent', 'partially_received']);
    }
}
