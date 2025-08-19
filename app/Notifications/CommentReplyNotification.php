<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;

class CommentReplyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $reply;
    public $parentComment;

    public function __construct(Comment $reply)
    {
        $this->reply = $reply;
        $this->parentComment = $reply->parent;
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
            ->subject($this->reply->user->name . ' replied to your comment')
            ->line($this->reply->user->name . ' replied to your comment on "' . $this->reply->news->title . '"')
            ->line('Your comment: "' . \Str::limit($this->parentComment->content, 100) . '"')
            ->line('Reply: "' . \Str::limit($this->reply->content, 100) . '"')
            ->action('View Reply', url('/news/' . $this->reply->news->slug . '#comment-' . $this->parentComment->id))
            ->line('Thank you for being part of our community!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'comment_reply',
            'reply_id' => $this->reply->id,
            'parent_comment_id' => $this->parentComment->id,
            'news_id' => $this->reply->news->id,
            'news_title' => $this->reply->news->title,
            'news_slug' => $this->reply->news->slug,
            'replier_name' => $this->reply->user->name,
            'replier_id' => $this->reply->user->id,
            'reply_content' => \Str::limit($this->reply->content, 100),
            'parent_content' => \Str::limit($this->parentComment->content, 100),
            'created_at' => now(),
        ];
    }

    public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    }
}
