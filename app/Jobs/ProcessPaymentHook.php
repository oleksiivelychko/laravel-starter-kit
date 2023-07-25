<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessPaymentHook implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private int $orderId;
    private string $orderStatus;

    public function __construct(int $orderId, string $orderStatus)
    {
        $this->orderId = $orderId;
        $this->orderStatus = $orderStatus;
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $order = Order::find($this->orderId);

        if ($order && in_array($this->orderStatus, array_keys(Order::STATUSES))) {
            $order->status = Order::STATUSES[$this->orderStatus];
            $order->save();

            Log::channel('hooks')->info(__CLASS__.':orderId:'.$this->orderId);
        } else {
            throw new \Exception('Error processing '.__CLASS__);
        }
    }
}
