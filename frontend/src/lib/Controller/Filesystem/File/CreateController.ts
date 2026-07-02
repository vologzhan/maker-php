import type {CreateRequest} from "$lib/Request/Filesystem/File/CreateRequest";
import type {CreateResponse} from "$lib/Response/Filesystem/File/CreateResponse";

export async function CreateController(body: CreateRequest): Promise<CreateResponse> {
    const response = await fetch(`/api/filesystem/file`, {
        method: 'POST',
        body: JSON.stringify(body),
    });

    return await response.json();
}
