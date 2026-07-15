import type {UpdateRequest} from "$lib/Request/Controller/UpdateRequest";
import type {FileContent} from "$lib/Response/Fs/Content/FileContent";

export async function UpdateController(id: number, body: UpdateRequest): Promise<FileContent> {
    const response = await fetch(`/api/controller/${id}`, {
        method: 'PUT',
        body: JSON.stringify(body)
    });

    return await response.json();
}
