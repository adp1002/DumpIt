const apiFetch = (url: string, method: string, body = {}, headers = {}): Promise<any> => {
    if ('GET' === method) {
        return fetch(
            import.meta.env.VITE_API_URL + url,
            {
                headers: {
                    ...headers,
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${localStorage.getItem('jwt') ?? ''}`, 
                },
                method: method,
            }
        ).then((response) => response.json())
    }

    return fetch(
        import.meta.env.VITE_API_URL + url,
        {
            headers: {
                ...headers,
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${localStorage.getItem('jwt') ?? ''}`, 
            },
            method: method,
            body: JSON.stringify(body),
        }
    ).then((response) => response.json())
}

export const registerUser = (body: Object): Promise<any> => apiFetch('/api/users/register', 'POST', body);

export const authenticate = (body: Object): Promise<any> => apiFetch('/api/auth', 'POST', body);

export const getFilters = (): Promise<any> => apiFetch('/api/filters?include=mods', 'GET');

export const getFilter = (id: string): Promise<any> => apiFetch(`/api/filters/${id}`, 'GET');

export const getMods = () => apiFetch('/api/mods', 'GET');

export const saveFilter = (body: Object, id: string|null = null): Promise<any> => {
    if (null === id) {
        return apiFetch('/api/filters', 'POST', body);
    }

    return apiFetch(`/api/filters/${id}`, 'PATCH', body);
}

export const getLeagues = () => apiFetch('/api/leagues', 'GET');

export const getTabs = (leagueId: string) => apiFetch(`/api/tabs?league=${leagueId}`, 'GET');

export const getTab = (id: string) => apiFetch(`/api/tabs/${id}?include=items`, 'GET');
