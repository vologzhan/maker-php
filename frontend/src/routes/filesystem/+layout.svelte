<script lang="ts">
    import Directory from './Directory.svelte';
    import {GetTree} from "$lib/Controller/Project/Filesystem/GetTreeController";
    import {currentProject} from "$lib/Store/Project";
    import type {DirectoryItemResponse} from "$lib/Response/Project/Filesystem/DirectoryItemResponse";
    import ContextMenu from "./ContextMenu.svelte";

    let error = $state('');
    let dir = $state<DirectoryItemResponse|null>(null);

    $effect(() => {
        const project = $currentProject;

        if (!project) return;

        GetTree(project.id)
            .then((res: DirectoryItemResponse) => {
                dir = res;
                error = '';
            })
            .catch(err => {
                error = err instanceof Error ? err.message : String(err);
            });
    });

    let { children } = $props();
</script>

{#if dir}
    Current project: {$currentProject?.name ?? '??????'}
    <div>
        <aside>
            <Directory dir={dir} open={true} />
        </aside>

        <main>
            {#if $currentProject}
                {@render children()}
            {/if}
        </main>
    </div>
{:else if error}
    Error: {error}
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

<ContextMenu />
