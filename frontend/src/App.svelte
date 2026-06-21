<script lang="ts">
    import { onMount } from 'svelte';
    import { loadProject } from './api/project';
    import type { Project, Controller } from './types/project';
    import ControllerTree from './components/ControllerTree.svelte';

    let project: Project | null = null;
    let error: string | null = null;
    let selected: Controller | null = null;

    const PROJECT_PATH = '/app/tests/Fixtures/maker-php';

    async function reloadProject() {
        project = await loadProject(PROJECT_PATH);
    }

    onMount(async () => {
        try {
            await reloadProject();
        } catch (e) {
            error = e instanceof Error ? e.message : 'Unknown error';
        }
    });
</script>

{#if error}
    <p>Error: {error}</p>
{:else if project}
    <h1>{project.name}</h1>

    <div class="layout">
        <div class="sidebar">
            <ControllerTree
                tree={project.controllers}
                onSelect={(controller) => {
                    selected = controller;
                }}
                onChanged={
                    reloadProject
                }
            />
        </div>

        <div class="content">
            {#if selected}
                <h2>{selected.name}</h2>

                <div>Method: {selected.method}</div>
                <div>Path: {selected.path}</div>
                <div>Response ID: {selected.responseId}</div>
            {:else}
                <p>Select controller</p>
            {/if}
        </div>
    </div>
{:else}
    <p>Loading...</p>
{/if}

<style>
    .layout {
        display: flex;
        gap: 2rem;
    }

    .sidebar {
        width: 300px;
        border-right: 1px solid #ccc;
        padding-right: 1rem;
    }

    .content {
        flex: 1;
    }
</style>
