<?php

namespace App\Enums;

enum FriendshipStatus: string
{
    case PendingSent = 'pending_sent';
    case PendingReceived = 'pending_received';
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Blocked = 'blocked';
}
