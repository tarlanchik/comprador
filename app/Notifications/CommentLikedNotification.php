<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Comment;
use App\Models\User;

class CommentLikedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;
    public $liker;

    public function __construct(Comment $comment, User $liker)
    {
        $this->comment = $comment;
        $this->liker = $liker;
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
            ->subject($this->liker->name . ' liked your comment')
            ->line($this->liker->name . ' liked your comment on "' . $this->comment->news->title . '"')
            ->line('"' . \Str::limit($this->comment->content, 100) . '"')
            ->action('View Comment', url('/news/' . $this->comment->news->slug . '#comment-' . $this->comment->id))
            ->line('Thank you for engaging with our community!');
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'comment_reply',
            'message' => $this->reply->user->name . ' replied to your comment',
            'comment_id' => $this->parentComment->id,
            'reply_id' => $this->reply->id,
            'news_id' => $this->reply->news_id,
            'news_title' => $this->reply->news->title,
            'news_slug' => $this->reply->news->slug,
            'replier_name' => $this->reply->user->name,
            'replier_avatar' => $this->reply->user->avatar_url,
            'reply_content' => \Str::limit($this->reply->content, 100),
            'action_url' => url('/news/' . $this->reply->news->slug . '#comment-' . $this->parentComment->id)
        ];
    }
}
