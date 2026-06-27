import type {ControllerResponse} from "$lib/Response/Controller/ControllerResponse";

export async function GetOneController(fileId: number): Promise<ControllerResponse> {
    const response = await fetch(`/api/controller/${fileId}`, {
        method: 'GET',
    });

    return await response.json();
}
