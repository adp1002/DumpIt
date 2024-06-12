import Select from 'react-select'

type DropdownProps = {
    options: { [label: string]: string }[],
    initialValue?: any,
    accessors?: { label: string, value: string },
    label?: string,
    onChange?: (value: any) => void,
    [rest: string]: any,
};

export default function Dropdown({
    options = [],
    initialValue = '',
    accessors = { label: 'label', value: 'value' },
    label = undefined,
    onChange = () => { },
    ...rest }: DropdownProps) {
        return (
        <div {...rest}>
            {label && <label className="mr-5">{label}</label>}
            <Select
                options={options}
                getOptionLabel={(option) => option[accessors.label]}
                getOptionValue={option => option[accessors.value]}
                defaultValue={initialValue}
                onChange={onChange}
                styles={{
                    control: (baseStyles, state) => ({
                      ...baseStyles,
                      borderColor: state.isFocused ? 'grey' : 'teal',
                    }),
                  }}
            />
        </div>
    )
}
