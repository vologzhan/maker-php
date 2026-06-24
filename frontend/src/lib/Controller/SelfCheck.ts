import type {SuccessResponse} from "$lib/Response/SuccessResponse";

export async function SelfCheck(): Promise<SuccessResponse> {
    const response = await fetch(`/api`, {
        method: 'GET',
    });

    return await response.json();
}
