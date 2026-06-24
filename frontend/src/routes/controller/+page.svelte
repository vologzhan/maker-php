<script lang="ts">
    import Directory from './Directory.svelte';
    import {IndexController} from "$lib/controller/project/index-controller";
    import type {ProjectResponse} from "$lib/response/project/project-response";
    import Viewer from "./Viewer.svelte";

    let project = $state<ProjectResponse|null>(null);
    let error = $state('');

    IndexController()
        .then(data => {
            project = data;
        })
        .catch(err => {
            error = err instanceof Error ? err.message : String(err);
        });
</script>

{#if project}
    <div>
        <aside>
            <Directory dir={project.controllers} expanded={true} />
        </aside>

        <main>
            <Viewer />
        </main>
    </div>
{:else if error}
    Error: {error}
{:else}
    Loading...
{/if}

<style>
    div {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: 100vh;
    }

    aside {
        border-right: 1px solid #ddd;
        overflow: auto;
    }

    main {
        overflow: auto;
        padding: 12px;
    }
</style>
