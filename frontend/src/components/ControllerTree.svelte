<script lang="ts">
    import type { ControllersTree } from '../types/project';
    import DirectoryNode from './DirectoryNode.svelte';

    interface Props {
        tree: ControllersTree;
    }

    let { tree }: Props = $props();
</script>

<h2>{tree.name}</h2>

<ul>
    {#each tree.files as controller}
        <li>
            {controller.name}
        </li>
    {/each}

    {#each tree.directories as directory}
        <DirectoryNode directory={directory} />
    {/each}
</ul>