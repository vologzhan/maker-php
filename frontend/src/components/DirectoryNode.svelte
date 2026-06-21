<script lang="ts">
    import type { ControllerDirectory } from '../types/project';

    interface Props {
        directory: ControllerDirectory;
    }

    let { directory }: Props = $props();
</script>

<li>
    📁 {directory.name}

    <ul>
        {#each directory.files as controller}
            <li>{controller.name}</li>
        {/each}
    </ul>
</li>