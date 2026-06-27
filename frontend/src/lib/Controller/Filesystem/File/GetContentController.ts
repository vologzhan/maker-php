import type {ContentItemResponse} from "$lib/Response/Filesystem/File/ContentItemResponse";

export async function GetContent(id: number): Promise<ContentItemResponse> {
    const response = await fetch(`/api/filesystem/file/${id}`, {
        method: 'GET',
    });

    return await response.json();
}
