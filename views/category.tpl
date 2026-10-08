{extends file="layout.tpl"}

{block name="title"}{$category->name}{/block}

{block name="content"}
    <p><a href="/">&larr; На главную</a></p>

    <h1>{$category->name}</h1>
    <p>{$category->description}</p>

    <div class="sort">
        Сортировать:
        {if $sortBy == 'published_at'}
            <a class="active" href="/categories/{$category->id}/posts?sortBy=published_at&sortDirection={if $sortDirection == 'ASC'}DESC{else}ASC{/if}">по дате публикации {if $sortDirection == 'ASC'}&uarr;{else}&darr;{/if}</a>
        {else}
            <a href="/categories/{$category->id}/posts?sortBy=published_at&sortDirection=DESC">по дате публикации</a>
        {/if}
        {if $sortBy == 'view_count'}
            <a class="active" href="/categories/{$category->id}/posts?sortBy=view_count&sortDirection={if $sortDirection == 'ASC'}DESC{else}ASC{/if}">по просмотрам {if $sortDirection == 'ASC'}&uarr;{else}&darr;{/if}</a>
        {else}
            <a href="/categories/{$category->id}/posts?sortBy=view_count&sortDirection=DESC">по просмотрам</a>
        {/if}
    </div>

    <div class="posts">
        {foreach $posts as $post}
            <article class="post-card">
                <img src="{$post->imagePath|storage_link:$postDefaultImage}" alt="{$post->title}">
                <h3><a href="/categories/{$category->id}/posts/{$post->id}">{$post->title}</a></h3>
                <p>{$post->description}</p>
                <small>{$post->publishedAt->format('Y-m-d')} · {$post->viewCount} просмотров</small>
            </article>
        {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    {if $totalPages > 1}
        <div class="pagination">
            {for $p=1 to $totalPages}
                {if $p == $page}
                    <strong>{$p}</strong>
                {else}
                    <a href="/categories/{$category->id}/posts?sortBy={$sortBy}&sortDirection={$sortDirection}&page={$p}">{$p}</a>
                {/if}
            {/for}
        </div>
    {/if}
{/block}
