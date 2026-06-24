import type {DirectoryItemResponse} from "$lib/Response/Project/Filesystem/DirectoryItemResponse";

export async function GetTree(projectId: number): Promise<DirectoryItemResponse> {
    const response = await fetch(`/api/project/${projectId}/filesystem/tree`, {
        method: 'GET',
    });

    return await response.json();
}
