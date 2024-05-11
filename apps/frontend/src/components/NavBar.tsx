import { useNavigate } from "react-router-dom";

export default function NavBar() {
    const navigate = useNavigate();

    return (
        <nav className="flex items-center justify-between flex-wrap bg-teal-500 p-6">
            <div className="flex items-center flex-shrink-0 text-white mr-6 hover:cursor-pointer">
                <span className="font-semibold text-3xl tracking-tight" onClick={() => navigate('/')}>DumpIt</span>
            </div>
            <div className="w-full block flex-grow lg:flex lg:items-center lg:w-auto">
                <div className="text-sm lg:flex-grow">
                    <a href="/filters" className="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
                        My filters
                    </a>
                    <a href="/filter-tabs" className="block mt-4 lg:inline-block lg:mt-0 text-teal-200 hover:text-white mr-4">
                        Filter tabs
                    </a>
                </div>
                <div>
                    <a href="/logout" className="inline-block text-sm px-4 py-2 leading-none border rounded text-white border-white hover:border-transparent hover:text-teal-500 hover:bg-white mt-4 lg:mt-0">Logout</a>
                </div>
            </div>
        </nav>
    )
}