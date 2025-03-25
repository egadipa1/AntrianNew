import InlineSvg from "vue-inline-svg";
/**
 * Initialize Inline-Svg component
 * @param app vue instance
 */
export function initInlineSvg(app) {
    app.component("inline-svg", InlineSvg);
}
