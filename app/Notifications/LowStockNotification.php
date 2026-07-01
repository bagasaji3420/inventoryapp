<?php

namespace App\Notifications;

use App\Models\Item;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    public function __construct(public Item $item) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $habis = $this->item->stok <= 0;

        return [
            'title'   => $habis
                ? "Stok {$this->item->nama_barang} habis!"
                : "Stok {$this->item->nama_barang} hampir habis",
            'message' => $habis
                ? "Stok {$this->item->nama_barang} sudah 0 {$this->item->satuan->nama_satuan}. Segera lakukan pembelian."
                : "Stok {$this->item->nama_barang} tersisa {$this->item->stok} {$this->item->satuan->nama_satuan}.",
            'icon'    => $habis ? 'bx-error-circle' : 'bx-package',
            'color'   => $habis ? 'danger' : 'warning',
            'type'    => 'system',
            'url'     => '/admin/items',
        ];
    }
}
