{extends file="layout.tpl"}

{block name="content"}
    <h1>Блог</h1>

    {foreach $categories as $category}
        {if $category.posts|count}
            <div class="category">
                <div class="category__header">
                    <h2>{$category.name}</h2>
                    <a class="btn" href="/category.php?id={$category.id}">Все статьи</a>
                </div>

                <p class="category__description">{$category.description}</p>

                <div class="posts">
                    {foreach $category.posts as $post}
                        <div class="post-card">
                            <div class="post-card__image">
                                <img src="/assets/images/{$post.image}" alt="">
                            </div>

                            <div class="post-card__body">
                                <h3>
                                    <a href="/post.php?id={$post.id}">
                                        {$post.title}
                                    </a>
                                </h3>

                                <p class="post-card__description">
                                    {$post.description}
                                </p>

                                <div class="post-card__meta">
                                    👁 {$post.views} просмотров
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </div>
        {/if}
    {/foreach}
{/block}
