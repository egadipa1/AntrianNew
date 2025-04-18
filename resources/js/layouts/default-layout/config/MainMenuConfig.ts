import type { MenuItem } from "@/layouts/default-layout/config/types";

const MainMenuConfig: Array<MenuItem> = [
    {
        pages: [
            {
                heading: "Dashboard",
                name: "dashboard",
                route: "/dashboard",
                keenthemesIcon: "element-11",
            },
        ],
    },

    // WEBSITE
    {
        heading: "Website",
        route: "/dashboard/website",
        name: "website",
        pages: [
            // MASTER
            {
                sectionTitle: "Master",
                route: "/master",
                keenthemesIcon: "cube-3",
                name: "master",
                sub: [
                    {
                        sectionTitle: "User",
                        route: "/users",
                        name: "master-user",
                        sub: [
                            {
                                heading: "Role",
                                name: "master-role",
                                route: "/dashboard/master/users/roles",
                            },
                            {
                                heading: "User",
                                name: "master-user",
                                route: "/dashboard/master/users",
                            },
                            {
                                heading: "Dokter",
                                name: "master-dokter",
                                route: "/dashboard/master/users/dokter",
                            },
                        ],
                    },
                    {
                        sectionTitle: "Antrian",
                        route: "/antrian",
                        name: "antrian",
                        sub: [
                            {
                                heading: "Poli",
                                name: "poli",
                                route: "/dashboard/master/antrians/poli",
                            },
                            {
                                heading: "Ruangan",
                                name: "ruangan",
                                route: "/dashboard/master/ruangans",
                            },
                        ],
                    },
                ],
            },
            {
                heading: "Menu Dokter",
                route: "/dashboard/dokter",
                name: "menu-dokter",
                keenthemesIcon: "setting-2",
            },
            {
                heading: "Setting",
                route: "/dashboard/setting",
                name: "setting",
                keenthemesIcon: "setting-2",
            },
        ],
    },
];

export default MainMenuConfig;
