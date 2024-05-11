import { useState } from "react"
import Input from "./Input";
import Icon from '@mdi/react';
import { mdiPencil, mdiCheck } from '@mdi/js';

type LabelInputProps = {
    initialValue?: string | number,
    onChange?: (vale: string | number) => void,
    [rest: string]: any,
}

export default function LabelInput({ initialValue = '', onChange = () => { }, ...rest }: LabelInputProps) {
    const [value, setValue] = useState(initialValue);
    const [isInput, setIsInput] = useState(false);

    return (
        <div {...rest}>
            {isInput ? (
                <>
                    <Input
                        className='border rounded-l-lg px-2 border-black'
                        initialValue={value}
                        onChange={(value) => {
                            onChange(value);
                            setValue(value);
                        }}
                    />
                    <span className='border rounded-r-lg px-2 border-black' onClick={() => setIsInput(false)}><Icon path={mdiCheck} size={1} /></span>
                </>
            ) : (
                <>
                    <span className="mr-1">{value}</span>
                    <span onClick={() => setIsInput(true)}><Icon path={mdiPencil} size={1} /></span>
                </>
            )}
        </div>
    );
}
