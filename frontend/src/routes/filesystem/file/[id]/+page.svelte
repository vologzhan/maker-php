<script lang="ts">
    import {page} from '$app/state';
    import Viewer from "./Viewer.svelte";
    import {GetContent} from "$lib/Controller/Filesystem/File/GetContentController";
    import type {ContentResponse} from "$lib/Response/Filesystem/File/ContentResponse";

    let content: ContentResponse|null = $state(null);
    let error = $state('');

    $effect(() => {
        const id = Number(page.params.id);

        if (!id) return;

        GetContent(id)
            .then((res: ContentResponse) => content = res)
            .catch(err => {
                error = err instanceof Error ? err.message : String(err);
            });
    });

</script>

<main>
    {#if content}
        <Viewer content={content} />
    {:else if error}
        Error: {error}
    {:else}
        Loading...
    {/if}
</main>
