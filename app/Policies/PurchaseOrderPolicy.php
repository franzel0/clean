<?php

namespace App\Policies;

use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins, purchasing staff, and managers can view all purchase orders
        return in_array($user->role, ['admin', 'purchasing_staff', 'sterilization_staff']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Admins and purchasing staff can view all
        if (in_array($user->role, ['admin', 'purchasing_staff'])) {
            return true;
        }

        // Sterilization staff can view orders related to their work
        if ($user->role === 'sterilization_staff') {
            return true;
        }

        // Users can view orders they requested or are involved in
        return $user->id === $purchaseOrder->requested_by 
            || $user->id === $purchaseOrder->approved_by
            || $user->id === $purchaseOrder->received_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admins, purchasing staff, and sterilization staff can create orders
        return in_array($user->role, ['admin', 'purchasing_staff', 'sterilization_staff']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Admins and purchasing staff can update all orders
        if (in_array($user->role, ['admin', 'purchasing_staff'])) {
            return true;
        }

        // Users can only update orders they requested (before delivery)
        return $user->id === $purchaseOrder->ordered_by 
            && !$purchaseOrder->received_at;
    }

    /**
     * Determine whether the user can update order status.
     */
    public function updateStatus(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Admins can always update status
        if ($user->role === 'admin') {
            return true;
        }

        // Purchasing staff can update most statuses
        if ($user->role === 'purchasing_staff') {
            return true;
        }

        // Sterilization staff can mark as received
        if ($user->role === 'sterilization_staff') {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Only admins can delete orders, and only if not yet delivered
        return $user->role === 'admin' 
            && !$purchaseOrder->is_delivered 
            && !$purchaseOrder->received_at;
    }

    /**
     * Determine whether the user can approve orders.
     */
    public function approve(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Only admins and purchasing staff can approve
        return in_array($user->role, ['admin', 'purchasing_staff'])
            && $purchaseOrder->status === 'requested';
    }

    /**
     * Determine whether the user can mark orders as received.
     */
    public function markReceived(User $user, PurchaseOrder $purchaseOrder): bool
    {
        // Admins, purchasing staff, and sterilization staff can mark as received
        return in_array($user->role, ['admin', 'purchasing_staff', 'sterilization_staff'])
            && in_array($purchaseOrder->status, ['shipped', 'ordered']);
    }
}
