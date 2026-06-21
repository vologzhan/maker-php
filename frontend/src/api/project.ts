import type { Project } from '../types/project';

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