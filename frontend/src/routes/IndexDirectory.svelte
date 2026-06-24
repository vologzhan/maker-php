<script lang="ts">
    import {IndexDirectory} from "$lib/Controller/Project/IndexDirectoryController";
    import type {ProjectItemResponse} from "$lib/Response/Project/ProjectItemResponse";
    import {projects} from "$lib/Store/Project";

    let error = $state('');
    let path = $state('');

    async function indexDirectory() {
        IndexDirectory({path: path})
            .then((res: ProjectItemResponse) => {
                projects.update((old) => [...old, res]);
                path = ''
                error = ''
            })
            .catch((err: any) => {
                error = err instanceof Error ? err.message : String(err)
            })
    }
</script>

<form onsubmit={indexDirectory}>
    <input
            bind:value={path}
            placeholder="/app"
            required
    />

    <button type="submit">
        Open exist directory
    </button>

    {#if error}
        <div style="color: red">Index error: {error}</div>
    {/if}

    <p>Путь в докере. Для maker используется /app</p>
</form>
