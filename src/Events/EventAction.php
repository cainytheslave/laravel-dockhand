<?php

namespace Cainy\Dockhand\Events;

enum EventAction: string
{
    case PULL = 'pull';
    case PUSH = 'push';
    case MOUNT = 'mount';
    case DELETE = 'delete';
}
