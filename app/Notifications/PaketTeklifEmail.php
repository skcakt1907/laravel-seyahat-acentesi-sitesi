<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaketTeklifEmail extends Notification
{
    use Queueable;

    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $uye = $this->data['uye'];
        $paketler = $this->data['paketler'];
        $paraBirimi = $this->data['para_birimi'] ?? 'TL';
        $toplamParaBirimi = $this->data['toplam_para_birimi'] ?? 0;

        $mail = (new MailMessage)
            ->subject('📦 Size Özel Paket Teklifi')
            ->greeting('Merhaba ' . ($uye->ad . ' ' . $uye->soyad) . '!')
            ->line('Sizin için özel bir paket teklifi hazırladık.')
            ->line('Aşağıda seçilen paketleri ve toplam tutarı görebilirsiniz:');

        foreach ($paketler as $paket) {
            $mail->line('- ' . ($paket->adi ?? 'Paket') . ' | Fiyat (TL): ' . number_format((float)($paket->tutar ?? 0), 2));
        }

        $mail->line('---')
            ->line('Toplam (TL): ' . number_format((float)$this->data['toplam_tl'], 2));

        if ($paraBirimi !== 'TL') {
            $mail->line('Toplam (' . $paraBirimi . '): ' . number_format((float)$toplamParaBirimi, 2));
        }

        $mail->line('Teklifle ilgili sorularınız olursa bu maile yanıt verebilirsiniz.')
            ->line('İyi günler dileriz.');

        return $mail;
    }
}













