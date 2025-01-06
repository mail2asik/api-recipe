<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminApprovedRecipe extends Notification implements ShouldQueue
{
    use Queueable;

    protected $recipe_uid;
    protected $recipe_title;

    /**
     * Create a new notification instance.
     * @param $recipe_uid
     * @param $recipe_title
     */
    public function __construct($recipe_uid, $recipe_title)
    {
        $this->recipe_uid = $recipe_uid;
        $this->recipe_title = $recipe_title;
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
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
                    ->subject('Your Recipe has been approved ' . $this->recipe_uid)
                    ->line('Thanks for posting your recipe! Administrator approved your following recipe.')
                    ->line('Recipe Title: '. $this->recipe_title);

        $url = config('constants.domain_web') . '/view-recipe/' . $this->recipe_uid;
        $mail = $mail->action('View Recipe', $url);

        $mail = $mail->line('Thank you for using our application!');

        return $mail;
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
