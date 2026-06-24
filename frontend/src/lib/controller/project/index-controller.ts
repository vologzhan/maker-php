import type {ProjectResponse} from "$lib/Response/Controller/project-response";

export async function IndexController(): Promise<ProjectResponse> {
    const response = await fetch(`/api/project/index`, {
        method: 'POST',
        body: JSON.stringify({
            path: '/app'
        })
    });

    return await response.json();
}
