<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function via($notifiable)
    {
        $channels = ['database'];

        if ($notifiable->email_notifications) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New comment on your article')
            ->line($this->comment->user->name . ' commented on your article "' . $this->comment->news->title . '"')
            ->line('"' . \Str::limit($this->comment->content, 150) . '"')
            ->action('View Comment', url('/news/' . $this->comment->news->slug . '#comment-' . $this->comment->id))
            ->line('Thank you for creating engaging content!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'comment_liked',
            'message' => $this->liker->name . ' liked your comment',
            'comment_id' => $this->comment->id,
            'news_id' => $this->comment->news_id,
            'news_title' => $this->comment->news->title,
            'news_slug' => $this->comment->news->slug,
            'liker_name' => $this->liker->name,
            'liker_avatar' => $this->liker->avatar_url,
            'action_url' => url('/news/' . $this->comment->news->slug . '#comment-' . $this->comment->id)
        ];
    }
}
