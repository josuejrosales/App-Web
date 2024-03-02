
import React, { Suspense, useEffect, useState } from 'react';
import ReactDOM from 'react-dom/client';
import { usehttpAxios } from './hooks/httpAxios.js';
import { getTokenUser } from './data/app.js';
import toast, { Toaster } from 'react-hot-toast';

function Index() {

    const { startHttp, error, response } = usehttpAxios("/getTypeUser", true);
    const [DynamicComponent, setDynamicComponent] = useState(null);

    const render = (type) => {

        if (!["user", "editor", "admin"].includes(type)) return;
        const DynamicComponent = React.lazy(() => import(`./pages/${type}.jsx`));
        setDynamicComponent(DynamicComponent);
    }

    useEffect(() => {
        response && render(atob(response.data));
    }, [response]);
    useEffect(() => {
        error && toast.error("connection error.");
    }, [error]);

    useEffect(() => {
        const access = getTokenUser();
        access ? startHttp(access) : toast.error("token error.");
    }, []);

    return (
        <React.Fragment>
            <Suspense fallback={<div>Cargando...</div>}>
                {DynamicComponent && <DynamicComponent />}
            </Suspense>
            <Toaster />
        </React.Fragment>
    );
}

ReactDOM.createRoot(document.getElementById('root')).render(<Index />);