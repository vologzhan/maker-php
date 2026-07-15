<script lang="ts">
    import {page} from '$app/state';
    import FileViewer from "./FileViewer.svelte";
    import {GetFileContent} from "$lib/Controller/Fs/GetFileContent";
    import type {FileContent} from "$lib/Response/Fs/Content/FileContent";
    import ControllerViewer from './ControllerViewer.svelte';

    let content: FileContent|null = $state(null);
    let error = $state('');

    $effect(() => {
        const id = Number(page.params.id);

        if (!id) return;

        GetFileContent(id)
            .then((res: FileContent) => content = res)
            .catch(err => {
                error = err instanceof Error ? err.message : String(err);
            });
    });

</script>

<main>
    {#if content}
        {#if content.controller}
            <ControllerViewer controller={content.controller} />
            <br />
        {/if}
        <FileViewer content={content.tokens} />
    {:else if error}
        Error: {error}
    {:else}
        Loading...
    {/if}
</main>
