{extends file="layout.tpl"}

{block name="title"}{$post->title}{/block}

{block name="content"}
    <nav class="mb-4" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item"><a href="/categories/{$category->id}/posts">{$category->name}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{$post->title}</li>
        </ol>
    </nav>

    <article>
        <img class="img-fluid w-100 mb-4" src="{$post->imagePath|storage_link:$postDefaultImage}" alt="{$post->title}">
        <h1 class="heading">{$post->title}</h1>
        <p class="meta">{$post->publishedAt->format('d.m.Y')} · {$post->viewCount} просмотров</p>
        <p class="lead">{$post->description}</p>
        <div class="content">{$post->content}</div>
    </article>
{/block}
