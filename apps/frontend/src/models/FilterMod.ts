export const CONDITIONS: {label: string, value: string}[] = [
    {label: '=', value: 'eq'},
    {label: '>', value: 'gt'},
    {label: '>=', value: 'gte'},
    {label: '<', value: 'lt'},
    {label: '<=', value: 'lte'},
]

export type FilterMod = {
    id: string,
    values: number[],
    condition: string,
    mod: string,
}

export type Mod = {
    id: string,
    text: string,
    placeholders: number,
}
