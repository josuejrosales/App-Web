
import axios from "axios";
import { useEffect, useState } from "react";
import { LOADING_STATE } from "../data/app";


var tokenAccess = "";
var signalAxios = {}

function usehttpAxios(
    url, force = false, method = "GET",
    headers_ = {}, data = {}, signal = null
) {

    const signal_ = signal ?? url.toString();
    const [response, setResponse] = useState(null);
    const [error, setError] = useState(null);
    const [countForce, setCountForce] = useState(0);
    const [loading, setLoading] = useState(LOADING_STATE.INITIAL);
    const [start, setStart] = useState(false);
    const [params, setParams] = useState(data);

    const httpResolve = async () => {

        signalAxios[signal_] && signalAxios[signal_].cancel('onchange');

        signalAxios[signal_] = axios.CancelToken.source();

        const requestConfig = {
            url: `/api${url}`,
            method,
            data: params,
            headers: { ...headers_, Authorization: tokenAccess },
            cancelToken: signalAxios[signal_].token
        };

        try {
            const promise = await axios(requestConfig);
            delete signalAxios[signal_];
            (promise.data?.operation || false) ? setResponse(promise.data) : setError(promise)
            return true;

        } catch (error) {
            axios.isCancel(error);
            return false;
        }
    }

    const funHttp = async (stopLoad = true) => {

        setLoading(LOADING_STATE.PENNDING);

        const bool = await httpResolve();

        if (stopLoad || bool) {
            setStart(false);
            setLoading(LOADING_STATE.COMPLETE);
        }

        if (stopLoad && !bool) {
            setStart(false);
            setError({ id: signal_, message: "No se proceso la solicitud" });
        }

        (!bool && !stopLoad) && setTimeout(() => setCountForce(countForce + 1), 1000);

    }

    const startHttp = (token, newParams = {}) => {
        tokenAccess = token;
        setResponse(null);
        setError(null);
        setParams({ ...params, ...newParams });
        setStart(true);
    };

    useEffect(() => {
        if (start == false) return;

        if (force && countForce < 4) {
            funHttp(false);
        } else funHttp();

    }, [start, force, countForce, params])

    return { response, error, loading, startHttp }
}
export { usehttpAxios };
