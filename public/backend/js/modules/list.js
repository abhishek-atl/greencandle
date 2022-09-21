const { createApp, ref, computed, onMounted, reactive } = Vue;

const app = createApp({
    mixins: [commonMixin],
    data() {
        return {
            items: [],

            // search and pagination
            current_page: 1,
            last_page: null,
            from: null,
            to: null,
            per_page: 25,
            total: null,
            order_by: 'id',
            order: 'desc',
            search_text: ''
        }
    },
    mounted() {
        this.fetchData()
    },
    methods: {
        async fetchData() {

            let vm = this
            let response = await $httpGet(route(indexRoute, {
                page: this.current_page,
                order_by: this.order_by,
                order: this.order,
                search: this.search_text,
                per_page: this.per_page,
            }));

            vm.items = response.data
            vm.current_page = response.current_page
            vm.last_page = response.last_page
            vm.from = response.from
            vm.to = response.to
            vm.per_page = response.per_page
            vm.total = response.total

            $(function () {
                $('[data-toggle="popover"]').popover({
                    trigger: 'hover',
                    html: true,
                });

            })


        }
    }
});
app.mount("#app");
