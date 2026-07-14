import type {DirItem} from "$lib/Response/Fs/Tree/DirItem";

export async function GetFsTree(): Promise<DirItem> {
    const response = await fetch(`/api/fs`, {
        method: 'GET',
    });

    return await response.json();
}
