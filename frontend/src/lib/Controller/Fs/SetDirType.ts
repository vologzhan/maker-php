import type {SetDirTypeRequest} from "$lib/Request/Fs/SetDirTypeRequest";
import type {FileItem} from "$lib/Response/Fs/Tree/FileItem";

export async function SetDirType(directoryId: number, body: SetDirTypeRequest): Promise<FileItem> {
    const response = await fetch(`/api/dir/${directoryId}/type`, {
        method: 'POST',
        body: JSON.stringify(body)
    });

    return await response.json();
}
