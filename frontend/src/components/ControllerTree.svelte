<script lang="ts">
    import type { ControllersTree, Controller } from '../types/project';
    import DirectoryNode from './DirectoryNode.svelte';
    import { createController, deleteController, loadProject } from '../api/project';

    interface Props {
        tree: ControllersTree;
        onSelect: (controller: Controller) => void;
        onReload: (project: ControllersTree) => void;
    }

    let { tree, onSelect, onReload }: Props = $props();

    async function handleDelete(id: number) {
        await deleteController(id);
        const project = await loadProject('/app/tests/Fixtures/maker-php');
        onReload(project.controllers);
    }

    async function handleAdd() {
        const name = prompt('Controller name?');
        if (!name) return;

        await createController({
            name,
            method: 'GET',
            path: '/',
            responseId: 1,
            directoryId: null
        });

        const project = await loadProject('/app/tests/Fixtures/maker-php');
        onReload(project.controllers);
    }
</script>

<div>
    <div style="display:flex; justify-content: space-between; align-items:center;">
        <strong>{tree.name}</strong>

        <button on:click={handleAdd}>
            + Add controller
        </button>
    </div>

    <ul>
        {#each tree.files as controller}
            <li style="display:flex; gap:8px; align-items:center;">
                <button on:click={() => onSelect(controller)}>
                    {controller.name}
                </button>

                <button on:click={() => handleDelete(controller.id)}>
                    🗑
                </button>
            </li>
        {/each}

        {#each tree.directories as dir}
            <DirectoryNode
                    directory={dir}
                    {onSelect}
                    {onReload}
            />
        {/each}
    </ul>
</div>