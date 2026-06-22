<script lang="ts">
    import Directory from './Directory.svelte';
    import {IndexController} from "$lib/controller/project/index-controller";
    import type {ProjectResponse} from "$lib/response/project/project-response";

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
    <Directory dir={project.controllers} expanded={true}/>
{:else if error}
    Error: {error}
{:else}
    Loading...
{/if}
