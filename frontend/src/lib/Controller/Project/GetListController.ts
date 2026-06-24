import type {ListResponse} from "$lib/Response/Project/ListResponse";

export async function GetList(): Promise<ListResponse> {
    const response = await fetch(`/api/project`, {
        method: 'GET',
    });

    return await response.json();
}
