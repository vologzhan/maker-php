import type {ContentResponse} from "$lib/Response/Filesystem/File/ContentResponse";

export async function GetContent(id: number): Promise<ContentResponse> {
    const response = await fetch(`/api/filesystem/file/${id}`, {
        method: 'GET',
    });

    return await response.json();
}
