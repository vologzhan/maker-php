import type {UpdateRequest} from "$lib/Request/Controller/UpdateRequest";
import type {ContentItemResponse} from "$lib/Response/Filesystem/File/ContentItemResponse";

export async function UpdateController(id: number, body: UpdateRequest): Promise<ContentItemResponse> {
    const response = await fetch(`/api/controller/${id}`, {
        method: 'PUT',
        body: JSON.stringify(body)
    });

    return await response.json();
}
