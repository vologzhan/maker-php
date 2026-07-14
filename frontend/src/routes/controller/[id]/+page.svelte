<script lang="ts">
    import {page} from '$app/state';
    import ControllerViewer from "./ControllerViewer.svelte";
    import {GetOneController} from "$lib/Controller/Controller/GetOneController";
    import type {ControllerResponse} from "$lib/Response/Controller/ControllerResponse";

    let controller: ControllerResponse|null = $state(null);
    let error = $state('');

    $effect(() => {
        const id = Number(page.params.id);

        if (!id) return;

        GetOneController(id)
            .then((res: ControllerResponse) => controller = res)
            .catch(err => {
                error = err instanceof Error ? err.message : String(err);
            });
    });

</script>

<main>
    {#if controller}
        <ControllerViewer controller={controller} />
    {:else if error}
        Error: {error}
    {:else}
        Loading...
    {/if}
</main>
