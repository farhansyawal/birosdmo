<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserVerifiedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun KEMENLH Anda Telah Diverifikasi')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Selamat! Akun Anda telah diverifikasi oleh admin.')
            ->line('Anda telah diverifikasi sebagai bagian dari **' . ($notifiable->unit_kerja ?? 'Unit Kerja Tidak Diketahui') . '**.')
            ->line('Sekarang Anda sudah bisa login ke sistem.')
            ->action('Masuk Sekarang', url('/login'))
            ->line('Terima kasih telah mendaftar di sistem kami.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
