<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\News;
use App\Models\User;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $news = News::all();

        foreach ($news as $article) {
            // Create parent comments
            $parentComments = Comment::factory()
                ->count(rand(3, 8))
                ->create([
                    'news_id' => $article->id,
                    'user_id' => $users->random()->id,
                    'approved' => true,
                ]);

            // Create replies
            foreach ($parentComments as $parent) {
                if (rand(1, 100) <= 60) { // 60% chance of having replies
                    Comment::factory()
                        ->count(rand(1, 3))
                        ->create([
                            'news_id' => $article->id,
                            'parent_id' => $parent->id,
                            'user_id' => $users->random()->id,
                            'approved' => true,
                        ]);
                }
            }

            // Update news comment count
            $article->update([
                'comments_count' => $article->comments()->approved()->count(),
                'last_commented_at' => $article->comments()->latest()->first()?->created_at,
            ]);
        }
    }
}
