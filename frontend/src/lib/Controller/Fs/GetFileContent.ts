import type {FileContent} from "$lib/Response/Fs/Content/FileContent";

export async function GetFileContent(id: number): Promise<FileContent> {
    const response = await fetch(`/api/file/${id}`, {
        method: 'GET',
    });

    return await response.json();
}
