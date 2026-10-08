{extends file="layout.tpl"}

{block name="content"}
    {foreach $categoriesWithPosts as $category}
        <section class="mb-5">
            <h2 class="heading h3">{$category->name}</h2>
            <p class="intro">{$category->description}</p>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-4 mb-3">
                {foreach $category->posts as $post}
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
                {/foreach}
            </div>

            <a class="all-articles-btn" href="/categories/{$category->id}/posts">Все статьи &rarr;</a>
        </section>
    {foreachelse}
        <p>Пока нет категорий со статьями.</p>
    {/foreach}
{/block}
