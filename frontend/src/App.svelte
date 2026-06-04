<script lang="ts">
    import axios from 'axios'
    import { onMount } from 'svelte'

    let content: string = ''

    interface FileContentResponse {
        content: string
    }

    async function fileContentController() {
        const response = await axios.get<FileContentResponse>('/api/file/content')

        content = response.data.content
    }

    onMount(() => {
        fileContentController()
    })
</script>

<textarea bind:value={content} rows="10" cols="50"></textarea>
