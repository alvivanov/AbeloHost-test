{extends file="layout.tpl"}

{block name="content"}
    <h1>Блог</h1>

    {foreach $categoriesWithPosts as $category}
        <section>
            <h2>{$category->name}</h2>
            <p>{$category->description}</p>

            <div class="posts">
                {foreach $category->posts as $post}
                    <article class="post-card">
                        <img src="{$post->imagePath|storage_link:$postDefaultImage}" alt="{$post->title}">
                        <h3><a href="/categories/{$category->id}/posts/{$post->id}">{$post->title}</a></h3>
                        <p>{$post->description}</p>
                        <small>{$post->publishedAt->format('Y-m-d')} · {$post->viewCount} просмотров</small>
                    </article>
                {/foreach}
            </div>

            <a class="all-articles-btn" href="/categories/{$category->id}/posts">Все статьи</a>
        </section>
    {foreachelse}
        <p>Пока нет категорий со статьями.</p>
    {/foreach}
{/block}
