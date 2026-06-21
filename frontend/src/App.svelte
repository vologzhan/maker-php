<script lang="ts">
    import { onMount } from 'svelte';
    import { loadProject } from './api/project';
    import type { Project } from './types/project';
    import ControllerTree from './components/ControllerTree.svelte';
    import type { Controller } from './types/project';

    let project: Project | null = null;
    let error: string | null = null;

    onMount(async () => {
        try {
            project = await loadProject('/app/tests/Fixtures/maker-php');
        } catch (e) {
            error = e instanceof Error ? e.message : 'Unknown error';
        }
    });

    let selected: Controller | null = null;
</script>

{#if error}
    <p>Error: {error}</p>
{:else if project}
    <h1>{project.name}</h1>

    <ControllerTree
            tree={project.controllers}
            onSelect={(controller) => {
		        selected = controller;
	        }}
    />

    {#if selected}
        <hr>

        <h2>{selected.name}</h2>

        <div>Method: {selected.method}</div>
        <div>Path: {selected.path}</div>
        <div>Response ID: {selected.responseId}</div>
    {/if}
{:else}
    <p>Loading...</p>
{/if}