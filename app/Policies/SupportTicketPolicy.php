<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, SupportTicket $supportTicket): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $supportTicket->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, SupportTicket $supportTicket): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, SupportTicket $supportTicket): bool
    {
        return $user->isAdmin();
    }

    public function assign(User $user, SupportTicket $supportTicket): bool
    {
        return $user->isAdmin();
    }

    public function reply(User $user, SupportTicket $supportTicket): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $supportTicket->user_id === $user->id;
    }
}
