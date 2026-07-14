import type {SetDirTypeRequest} from "$lib/Request/Fs/SetDirTypeRequest";
import type {SuccessResponse} from "$lib/Response/SuccessResponse";

export async function SetDirType(directoryId: number, body: SetDirTypeRequest): Promise<SuccessResponse> {
    const response = await fetch(`/api/dir/${directoryId}/type`, {
        method: 'POST',
        body: JSON.stringify(body)
    });

    return await response.json();
}
