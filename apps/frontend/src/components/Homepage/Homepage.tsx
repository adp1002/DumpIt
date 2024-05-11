import { useNavigate } from "react-router-dom";
import Card from "../BasicElements/Card";
import NavBar from "../NavBar";

export default function Homepage() {
    const navigate = useNavigate();

    return (
        <>
            <NavBar />
            <div className="flex flex-row flex-nowrap items-center gap-10 justify-center py-12">
                <Card title='My filters' onClick={() => navigate('/filters')} />
                <Card title='Filter tabs' onClick={() => navigate('/filter-tabs')} />
            </div>
        </>

    )
}