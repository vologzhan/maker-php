import type {SuccessResponse} from "$lib/Response/SuccessResponse";

export async function DeleteFile(id: number): Promise<SuccessResponse> {
    const response = await fetch(`/api/file/${id}`, {
        method: 'DELETE',
    });

    return await response.json();
}
