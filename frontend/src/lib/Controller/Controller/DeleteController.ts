import type {SuccessResponse} from "$lib/Response/SuccessResponse";

export async function DeleteController(fileId: number): Promise<SuccessResponse> {
    const response = await fetch(`/api/controller/${fileId}`, {
        method: 'DELETE',
    });

    return await response.json();
}
