import { useState } from "react";

type InputProps = {
    initialValue?: string|number,
    onlyIntegers?: boolean,
    onChange?: (value: string|number) => void,
    [rest: string]: any,
}

export default function Input({
    initialValue = '',
    onlyIntegers = false,
    onChange = () => {},
    ...rest
}: InputProps) {
    const [value, setValue] = useState(initialValue);

    const numberInputProps = onlyIntegers ? {type: 'number', min: '0', step: '1'} : {};

    const parseValue = (value: string) => onlyIntegers ? parseInt(value) : value;

    return (
        <input
            value={value}
            onChange={(event) => {setValue(event.target.value)}}
            onBlur={(event) => {onChange(parseValue(event.target.value))}}
            {...numberInputProps}
            {...rest}
        />
    )
}