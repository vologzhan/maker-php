import type {SuccessResponse} from "$lib/Response/SuccessResponse";

export async function DeleteController(id: number): Promise<SuccessResponse> {
    const response = await fetch(`/api/filesystem/file/${id}`, {
        method: 'DELETE',
    });

    return await response.json();
}
