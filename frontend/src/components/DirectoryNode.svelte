<script lang="ts">
    import type { Controller, ControllerDirectory } from '../types/project';
    import { createDirectory } from '../api/project';

    interface Props {
        directory: ControllerDirectory;
        onSelect: (controller: Controller) => void;
        onChanged: () => void;
    }

    let { directory, onSelect, onChanged }: Props = $props();

    async function handleAddDirectory() {
        const name = prompt('Directory name?');
        if (!name) return;

        await createDirectory({
            name,
            parentDirectoryId: directory.id
        });

        onChanged();
    }
</script>

<li style="margin-top: 8px;">
    <div style="display:flex; justify-content: space-between; align-items:center;">
        <strong>📁 {directory.name}</strong>

        <button on:click={handleAddDirectory}>
            + dir
        </button>
    </div>

    <ul style="margin-left: 16px;">
        {#each directory.files as controller}
            <li>
                <button on:click={() => onSelect(controller)}>
                    {controller.name}
                </button>
            </li>
        {/each}

        {#each directory.directories as subdir}
            <DirectoryNode
                    directory={subdir}
                    {onSelect}
                    {onChanged}
            />
        {/each}
    </ul>
</li>