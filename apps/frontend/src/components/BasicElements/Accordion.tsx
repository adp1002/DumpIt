import { ReactElement, useState } from "react";
import Icon from '@mdi/react';
import { mdiChevronDown, mdiChevronUp } from '@mdi/js';

type AccordionProps = {
    title: string,
    children: string | ReactElement,
}

export default function Accordion({ title, children }: AccordionProps) {
    const [isOpen, setIsOpen] = useState(false);

    return (
        <div className="w-1/2 p-4 mx-auto">
            <h2>
                <button
                    className="flex items-center justify-between w-1/3 text-left font-semibold py-2"
                    onClick={(e) => { e.preventDefault(); setIsOpen(!isOpen); }}
                >
                    {title}
                    <Icon path={isOpen ? mdiChevronUp : mdiChevronDown} size={1} />
                </button>
            </h2>
            <div
                className={`w-1/2 text-sm text-slate-600 overflow-hidden transition-all duration-300 ease-in-out ${isOpen ? 'opacity-100' : 'opacity-0'}`}
            >
                {children}
            </div>
        </div>
    )
};