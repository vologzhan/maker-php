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
    <div class="layout">
        <aside class="sidebar">
            <Directory dir={project.controllers} expanded={true} />
        </aside>

        <main class="content">
            <!-- todo форма редактирования -->
            Select item
        </main>
    </div>
{:else if error}
    Error: {error}
{:else}
    Loading...
{/if}

<style>
    .layout {
        display: grid;
        grid-template-columns: 320px 1fr;
        height: 100vh;
    }

    .sidebar {
        border-right: 1px solid #ddd;
        overflow: auto;
    }

    .content {
        overflow: auto;
        padding: 12px;
    }
</style>
