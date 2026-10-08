{extends file="layout.tpl"}

{block name="title"}{$post->title}{/block}

{block name="content"}
    <p><a href="/">&larr; На главную</a></p>

    <article>
        <img src="{$post->imagePath|storage_link:$postDefaultImage}" alt="{$post->title}">
        <h1>{$post->title}</h1>

        <p><em>{$post->description}</em></p>
        <div class="content">{$post->content}</div>

        <small>{$post->publishedAt->format('Y-m-d')} · {$post->viewCount} просмотров</small>
    </article>
{/block}
