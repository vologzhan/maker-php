import type {CreateFileRequest} from "$lib/Request/Fs/CreateFileRequest";
import type {FileItem} from "$lib/Response/Fs/Tree/FileItem";

export async function CreateFile(body: CreateFileRequest): Promise<FileItem> {
    const response = await fetch(`/api/filesystem/file`, {
        method: 'POST',
        body: JSON.stringify(body),
    });

    return await response.json();
}
