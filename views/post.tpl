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

    {if $relatedPosts}
        <section class="mt-5">
            <h2 class="heading h3">Похожие статьи</h2>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4">
                {foreach $relatedPosts as $relatedPost}
                    <div class="col">
                        <article class="card post-card border-0 h-100">
                            <img class="card-img-top" src="{$relatedPost->previewImagePath|storage_link:$postDefaultPreviewImage}" alt="{$relatedPost->title}">
                            <div class="card-body px-0 pb-0">
                                <p class="meta">{$relatedPost->publishedAt->format('d.m.Y')} · {$relatedPost->viewCount} просмотров</p>
                                <h3 class="card-title h5"><a href="/categories/{$category->id}/posts/{$relatedPost->id}">{$relatedPost->title}</a></h3>
                                <p class="card-text">{$relatedPost->description}</p>
                            </div>
                        </article>
                    </div>
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
