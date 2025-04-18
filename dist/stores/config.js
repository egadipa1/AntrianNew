import { ref } from "vue";
import { defineStore } from "pinia";
import objectPath from "object-path";
import layoutConfig from "@/layouts/default-layout/config/DefaultLayoutConfig";
export const LS_CONFIG_NAME_KEY = "config_" + import.meta.env.VITE_APP_NAME;
export const useConfigStore = defineStore("config", () => {
    const config = ref(layoutConfig);
    const initial = ref(layoutConfig);
    function getLayoutConfig(path, defaultValue) {
        return objectPath.get(config.value, path, defaultValue);
    }
    function setLayoutConfigProperty(property, value) {
        objectPath.set(config.value, property, value);
        localStorage.setItem(LS_CONFIG_NAME_KEY, JSON.stringify(config.value));
    }
    function resetLayoutConfig() {
        config.value = Object.assign({}, initial.value);
    }
    function overrideLayoutConfig() {
        config.value = initial.value = Object.assign({}, initial.value, JSON.parse(window.localStorage.getItem(LS_CONFIG_NAME_KEY) || "{}"));
    }
    return {
        config,
        getLayoutConfig,
        setLayoutConfigProperty,
        resetLayoutConfig,
        overrideLayoutConfig,
    };
});
