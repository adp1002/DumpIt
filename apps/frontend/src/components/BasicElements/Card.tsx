type CardProps = {
    title: string,
    description?: string,
    image?: string,
    onClick?: () => void,
    rest?: any,
}

export default function Card({ title, description, image, onClick, ...rest }: CardProps) {
    return (
        <>
            <div className={`rounded overflow-hidden shadow-lg hover:cursor-pointer`} onClick={onClick} {...rest}>
                {image && <img className="w-full" src={image} alt={image} />}
                <div className="px-6 py-4">
                    <div className="font-bold text-xl mb-2">{title}</div>
                    {description && (
                        <p className="text-gray-700 text-base">
                            {description}
                        </p>
                    )}
                </div>
            </div>
        </>
    )
}