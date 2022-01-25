<?php

namespace GeoSot\BaseAdmin\App\Events;

use App\Models\Media\Medium;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MediumUploaded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Medium $medium;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Medium $medium)
    {
        $this->medium = $medium;
    }
}
