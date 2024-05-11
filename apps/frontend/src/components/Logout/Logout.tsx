import { Navigate } from "react-router-dom";

export default function Logout ({logout}: LogoutProps) {
  logout();

  return (<Navigate to={'/login'} />);
}
