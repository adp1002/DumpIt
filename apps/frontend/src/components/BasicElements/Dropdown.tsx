import { useState } from "react";

type DropdownProps = {
    options: { [label: string]: string | number }[],
    initialValue?: string | number,
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
    const [value, setValue] = useState(initialValue);

    return (
        <div {...rest}>
            {label && <label className="mr-5">{label}</label>}
            <select
                value={value}
                onChange={(event) => {
                    setValue(event.target.value);
                    onChange(event.target.value);
                }}
            >
                <option className="display:none" hidden />
                {options.map((option, i) => (
                    <option key={i} value={option[accessors.value]}>
                        {option[accessors.label]}
                    </option>
                ))}
            </select>
        </div>
    )
}
