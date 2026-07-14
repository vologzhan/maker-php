<script lang="ts">
    import {page} from '$app/state';
    import FileViewer from "./FileViewer.svelte";
    import {GetContent} from "$lib/Controller/Filesystem/File/GetContentController";
    import type {ContentItemResponse} from "$lib/Response/Filesystem/File/ContentItemResponse";

    let content: ContentItemResponse|null = $state(null);
    let error = $state('');

    $effect(() => {
        const id = Number(page.params.id);

        if (!id) return;

        GetContent(id)
            .then((res: ContentItemResponse) => content = res)
            .catch(err => {
                error = err instanceof Error ? err.message : String(err);
            });
    });

</script>

<main>
    {#if content}
        <FileViewer content={content.items} />
    {:else if error}
        Error: {error}
    {:else}
        Loading...
    {/if}
</main>
