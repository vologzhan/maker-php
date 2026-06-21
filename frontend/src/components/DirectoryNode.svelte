<script lang="ts">
    import type { Controller, ControllerDirectory } from '../types/project';

    interface Props {
        directory: ControllerDirectory;
        onSelect: (controller: Controller) => void;
    }

    let { directory, onSelect }: Props = $props();
</script>

<li>
    📁 {directory.name}

    <ul>
        {#each directory.files as controller}
            <li>
                <button onclick={() => onSelect(controller)}>
                    {controller.name}
                </button>
            </li>
        {/each}
    </ul>
</li>