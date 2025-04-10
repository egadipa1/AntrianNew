import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function useRuangan(options = {}) {
    return useQuery({
        queryKey: ["ruangan"],
        queryFn: async () =>
            await axios.get("/master/ruangan").then((res: any) => res.data.data),
        ...options,
    });
}
