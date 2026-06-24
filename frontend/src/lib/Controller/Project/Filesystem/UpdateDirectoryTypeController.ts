import type {UpdateDirectoryTypeRequest} from "$lib/Request/Project/Filesystem/UpdateDirectoryTypeRequest";
import type {SuccessResponse} from "$lib/Response/SuccessResponse";

export async function UpdateDirectoryType(directoryId: number, body: UpdateDirectoryTypeRequest): Promise<SuccessResponse> {
    const response = await fetch(`/api/filesystem/directory/${directoryId}/type`, {
        method: 'PUT',
        body: JSON.stringify(body)
    });

    return await response.json();
}
