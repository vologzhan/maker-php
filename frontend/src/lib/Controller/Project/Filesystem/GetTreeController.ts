import type {DirItemResponse} from "$lib/Response/Project/Filesystem/DirItemResponse";

export async function GetTree(projectId: number): Promise<DirItemResponse> {
    const response = await fetch(`/api/project/${projectId}/filesystem/tree`, {
        method: 'GET',
    });

    return await response.json();
}
