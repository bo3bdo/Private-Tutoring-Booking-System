<?php

namespace App\Helpers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class ForumHelper
{
    /**
     * Parse @mentions in forum content and convert to styled spans.
     * Matches @slug (e.g. @ahmed-alkhalifa) or @FirstName (e.g. @Ahmed).
     * Sort by slug length descending so longer matches are applied first.
     */
    public static function parseMentions(string $content, Collection $users): string
    {
        $escaped = e($content);
        $escaped = nl2br($escaped);

        $uniqueUsers = $users->unique('id')
            ->sortByDesc(fn ($u) => strlen(Str::slug($u->name)));

        foreach ($uniqueUsers as $user) {
            $slug = Str::slug($user->name);
            if ($slug === '') {
                continue;
            }
            $safeName = e($user->name);
            $replacement = '<span class="forum-mention inline-flex items-center px-1.5 py-0.5 rounded font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30" data-user-id="'.$user->id.'" title="'.$safeName.'">@'.$safeName.'</span>';
            $escaped = str_replace('@'.$slug, $replacement, $escaped);
        }

        return $escaped;
    }

    /**
     * Get all users that appear in a thread (author + post authors).
     *
     * @param  \App\Models\ForumThread  $thread
     * @return \Illuminate\Support\Collection<int, \App\Models\User>
     */
    public static function threadUsers($thread): Collection
    {
        $users = collect([$thread->user]);
        foreach ($thread->posts as $post) {
            $users->push($post->user);
        }

        return $users->unique('id')->values();
    }
}
