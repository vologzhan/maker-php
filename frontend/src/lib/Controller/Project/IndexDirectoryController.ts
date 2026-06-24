import type {IndexDirectoryRequest} from "$lib/Request/Project/IndexDirectoryRequest";
import type {ProjectItemResponse} from "$lib/Response/Project/ProjectItemResponse";

export async function IndexDirector(body: IndexDirectoryRequest): Promise<ProjectItemResponse> {
    const response = await fetch(`/api/project/index`, {
        method: 'POST',
        body: JSON.stringify(body)
    });

    return await response.json();
}
