const ID_TOKEN_KEY = "auth_token";
/**
 * @description get token form localStorage
 */
export const getToken = () => {
    return window.localStorage.getItem(ID_TOKEN_KEY);
};
/**
 * @description save token into localStorage
 * @param token: string
 */
export const saveToken = (token) => {
    window.localStorage.setItem(ID_TOKEN_KEY, token);
};
/**
 * @description remove token form localStorage
 */
export const destroyToken = () => {
    window.localStorage.removeItem(ID_TOKEN_KEY);
};
export default { getToken, saveToken, destroyToken };
