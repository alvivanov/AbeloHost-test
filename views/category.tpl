{extends file="layout.tpl"}

{block name="title"}{$category->name}{/block}

{block name="content"}
    <nav class="mb-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">{$category->name}</li>
        </ol>
    </nav>

    <h1 class="heading">{$category->name}</h1>
    <p class="intro">{$category->description}</p>

    <div class="btn-group mb-4" role="group" aria-label="Сортировка">
        {if $sortBy == 'published_at'}
            <a class="btn btn-sm btn-primary" href="/categories/{$category->id}/posts?sortBy=published_at&sortDirection={if $sortDirection == 'ASC'}DESC{else}ASC{/if}">по дате публикации {if $sortDirection == 'ASC'}&uarr;{else}&darr;{/if}</a>
        {else}
            <a class="btn btn-sm btn-outline-secondary" href="/categories/{$category->id}/posts?sortBy=published_at&sortDirection=DESC">по дате публикации</a>
        {/if}
        {if $sortBy == 'view_count'}
            <a class="btn btn-sm btn-primary" href="/categories/{$category->id}/posts?sortBy=view_count&sortDirection={if $sortDirection == 'ASC'}DESC{else}ASC{/if}">по просмотрам {if $sortDirection == 'ASC'}&uarr;{else}&darr;{/if}</a>
        {else}
            <a class="btn btn-sm btn-outline-secondary" href="/categories/{$category->id}/posts?sortBy=view_count&sortDirection=DESC">по просмотрам</a>
        {/if}
    </div>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mb-4">
        {foreach $posts as $post}
            <div class="col">
                <article class="card post-card border-0 h-100">
                    <img class="card-img-top" src="{$post->previewImagePath|storage_link:$postDefaultImage}" alt="{$post->title}">
                    <div class="card-body px-0 pb-0">
                        <p class="meta">{$post->publishedAt->format('d.m.Y')} · {$post->viewCount} просмотров</p>
                        <h3 class="card-title h5"><a href="/categories/{$category->id}/posts/{$post->id}">{$post->title}</a></h3>
                        <p class="card-text">{$post->description}</p>
                    </div>
                </article>
            </div>
        {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>

    {if $totalPages > 1}
        <nav aria-label="Страницы">
            <ul class="pagination">
                {for $p=1 to $totalPages}
                    <li class="page-item {if $p == $page}active{/if}">
                        {if $p == $page}
                            <span class="page-link">{$p}</span>
                        {else}
                            <a class="page-link" href="/categories/{$category->id}/posts?sortBy={$sortBy}&sortDirection={$sortDirection}&page={$p}">{$p}</a>
                        {/if}
                    </li>
                {/for}
            </ul>
        </nav>
    {/if}
{/block}
