

// LOADING 
const LOADING_STATE = {
    INITIAL: "STATE_INITIAL",
    PENNDING: "STATE_PENNDING",
    COMPLETE: "STATE_COMPLETE",
}

// Obtener el token local
function getTokenUser() {
    const local = document.cookie.split(';').find(cookie => cookie.includes('token='));
    if (!local) return false;
    const token = local.split('=')[1];
    return `Bearer ${token.trim()}`;
}

export { LOADING_STATE, getTokenUser }