<script lang="ts">
    import { onMount } from 'svelte';
    import { loadProject } from './api/project';
    import type { Project } from './types/project';
    import ControllerTree from './components/ControllerTree.svelte';

    let project: Project | null = null;
    let error: string | null = null;

    onMount(async () => {
        try {
            project = await loadProject('/app/tests/Fixtures/maker-php');
        } catch (e) {
            error = e instanceof Error ? e.message : 'Unknown error';
        }
    });
</script>

{#if error}
    <p>Error: {error}</p>
{:else if project}
    <h1>{project.name}</h1>

    <ControllerTree tree={project.controllers} />
{:else}
    <p>Loading...</p>
{/if}