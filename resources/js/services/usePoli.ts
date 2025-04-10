import { useQuery } from "@tanstack/vue-query";
import axios from "@/libs/axios";

export function usePoli(options = {}) {
    return useQuery({
        queryKey: ["polis"],
        queryFn: async () =>
            await axios.get("/master/poli").then((res: any) => res.data.data),
        ...options,
    });
}
