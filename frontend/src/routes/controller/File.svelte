<script>
    let { name } = $props();
</script>

<span>{name}</span>

<style>
    span {
        padding: 0 0 0 1.5em;
        background: url($lib/icons/file.svg) 0 0.1em no-repeat;
        background-size: 1em 1em;
        font-size: 14px;
        cursor: pointer;
    }
</style>
