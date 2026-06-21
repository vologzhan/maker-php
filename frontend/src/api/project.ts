import type {
    Project,
    CreateControllerInput,
    UpdateControllerInput,
    CreateDirectoryInput,
    UpdateDirectoryInput,
} from '../types/project';

export async function loadProject(path: string): Promise<Project> {
    const response = await fetch('/api/project/index', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            path
        })
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return await response.json();
}

export async function createController(input: CreateControllerInput) {
    const response = await fetch('/api/controller/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(input)
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return await response.json();
}

export async function updateController(input: UpdateControllerInput) {
    const response = await fetch('/api/controller/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(input)
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return await response.json();
}

export async function deleteController(id: number) {
    const response = await fetch('/api/controller/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id })
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return await response.json();
}

export async function createDirectory(input: CreateDirectoryInput) {
    const response = await fetch('/api/directory/create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(input)
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return await response.json();
}

export async function updateDirectory(input: UpdateDirectoryInput) {
    const response = await fetch('/api/directory/update', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(input)
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return await response.json();
}

export async function deleteDirectory(id: number) {
    const response = await fetch('/api/directory/delete', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id })
    });

    if (!response.ok) {
        throw new Error(`HTTP ${response.status}`);
    }

    return await response.json();
}
