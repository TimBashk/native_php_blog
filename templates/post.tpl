{extends file="layout.tpl"}

{block name="content"}

    <div class="post-single">
        <h1>{$post.title}</h1>

        <div class="post-meta">
            👁 {$post.views} просмотров
            | опубликовано: {$post.created_at}
        </div>

        {if $post.image}
            <div class="post-image">
                <img src="/assets/images/{$post.image|default:'placeholder.jpg'}" alt="{$post.title}">
            </div>
        {/if}

        <div class="post-content">
            {$post.content nofilter}
        </div>
    </div>

    {if $relatedPosts|count}
        <h2>Похожие статьи</h2>
        <div class="posts">
            {foreach $relatedPosts as $rpost}
                <div class="post-card">
                    <div class="post-card__image">
                        <img src="/assets/images/{$rpost.image|default:'placeholder.jpg'}" alt="">
                    </div>
                    <div class="post-card__body">
                        <h3>
                            <a href="/post.php?id={$rpost.id}">
                                {$rpost.title}
                            </a>
                        </h3>
                        <p class="post-card__description">
                            {$rpost.description}
                        </p>
                        <div class="post-card__meta">
                            👁 {$rpost.views} просмотров
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {/if}

{/block}
