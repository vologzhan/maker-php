<script lang="ts">
    import type { Controller, ControllersTree } from '../types/project';
    import DirectoryNode from './DirectoryNode.svelte';

    interface Props {
        tree: ControllersTree;
        onSelect: (controller: Controller) => void;
    }

    let { tree, onSelect }: Props = $props();
</script>

<h2>{tree.name}</h2>

<ul>
    {#each tree.files as controller}
        <li>
            <button onclick={() => onSelect(controller)}>
                {controller.name}
            </button>
        </li>
    {/each}

    {#each tree.directories as directory}
        <DirectoryNode
                directory={directory}
                onSelect={onSelect}
        />
    {/each}
</ul>